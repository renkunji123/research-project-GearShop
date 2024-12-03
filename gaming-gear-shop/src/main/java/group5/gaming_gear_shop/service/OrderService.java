package group5.gaming_gear_shop.service;

import group5.gaming_gear_shop.dto.order.OrderDTO;
import group5.gaming_gear_shop.entity.*;
import group5.gaming_gear_shop.exception.cart.CartNotFoundException;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import group5.gaming_gear_shop.exception.order.NoCartItemSelectedException;
import group5.gaming_gear_shop.exception.order.OrderCannotBeCancelledException;
import group5.gaming_gear_shop.exception.order.OrderNotFoundException;
import group5.gaming_gear_shop.exception.payment.PaymentMethodNotFoundException;
import group5.gaming_gear_shop.exception.user.UnauthorizedOrderAccessException;
import group5.gaming_gear_shop.repository.*;
import jakarta.transaction.Transactional;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
public class OrderService {
    private final OrderRepository orderRepository;
    private final UserService userService;
    private final UserRepository userRepository;
    private final ProductService productService;
    private final Payment_MethodsRepository paymentMethodsRepository;
    private final CartRepository cartRepository;

    // Tạo đơn hàng mới
    @Transactional
    public Order createOrder(List<Long> selectedCartItemIds, OrderDTO orderDTO) {
        // Tìm user hiện tại
        User user = userService.getCurrentUser();

        // Lấy Cart của user
        Cart userCart = cartRepository.findByUser(user)
                .orElseThrow(() -> new CartNotFoundException(ErrorCode.CART_NOT_FOUND));

        // Lọc và lấy các CartItem được chọn
        List<Cart_Item> selectedCartItems = userCart.getCartItems().stream()
                .filter(cartItem -> selectedCartItemIds.contains(cartItem.getCartItemId()))
                .toList();

        // Kiểm tra nếu không có CartItem nào được chọn
        if (selectedCartItems.isEmpty()) {
            throw new NoCartItemSelectedException(ErrorCode.NO_CART_ITEM_SELECTED);
        }

        // Tạo order
        Order order = new Order();
        order.setUser(user);
        order.setOrderDate(LocalDate.now());
        order.setShipmentDate(LocalDate.now().plusDays(1));
        order.setOrderStatus(Order.OrderStatus.IN_PROGRESS);
        order.setShipmentStatus(Order.ShipmentStatus.IN_TRANSIT);

        // Set payment method
        order.setPaymentMethod(paymentMethodsRepository.findById(orderDTO.getPaymentMethodId())
                .orElseThrow(() -> new PaymentMethodNotFoundException(ErrorCode.PAYMENT_METHOD_NOT_FOUND)));

        // Xử lý payment status theo phương thức thanh toán
        if (order.getPaymentMethod().getPaymentMethodName().equalsIgnoreCase("COD")) {
            order.setPaymentStatus(Order.PaymentStatus.NOT_PAID_YET);
        } else {
            order.setPaymentStatus(Order.PaymentStatus.PENDING);
        }

        // Set địa chỉ giao hàng
        order.setShipmentAddress(orderDTO.getShipmentAddress());

        // Tạo order items từ các CartItem được chọn
        List<Order_Item> orderItems = selectedCartItems.stream()
                .map(cartItem -> {
                    Order_Item orderItem = new Order_Item();
                    Product product = cartItem.getProduct();

                    // Giảm số lượng sản phẩm trong kho
                    productService.updateProductStock(product.getProductId(), cartItem.getCartItemQuantity());

                    orderItem.setProduct(product);
                    orderItem.setQuantity(cartItem.getCartItemQuantity());
                    orderItem.setOrder(order);
                    orderItem.setUnitPrice(product.getProductPrice());
                    orderItem.setSubTotalAmount(
                            product.getProductPrice().multiply(BigDecimal.valueOf(cartItem.getCartItemQuantity()))
                    );

                    return orderItem;
                })
                .collect(Collectors.toList());

        // Tính toán tổng giá trị đơn hàng
        BigDecimal totalAmount = orderItems.stream()
                .map(Order_Item::getSubTotalAmount)
                .reduce(BigDecimal.ZERO, BigDecimal::add);
        order.setTotalAmount(totalAmount);

        // Gán order items
        order.setOrderItems(orderItems);

        // Lưu order và xóa các CartItem đã chọn khỏi giỏ hàng
        Order savedOrder = orderRepository.save(order);

        // Xóa các CartItem đã chuyển thành OrderItem
        userCart.getCartItems().removeAll(selectedCartItems);
        cartRepository.save(userCart);

        return savedOrder;
    }

    @Transactional
    public Order cancelOrder(Long orderId) {
        // Lấy user hiện tại
        User currentUser = userService.getCurrentUser();

        // Tìm order
        Order order = orderRepository.findById(orderId)
                .orElseThrow(() -> new OrderNotFoundException(ErrorCode.ORDER_NOT_FOUND));

        // Kiểm tra xem order có phải của user hiện tại không
        if (!order.getUser().getUserId().equals(currentUser.getUserId())) {
            throw new UnauthorizedOrderAccessException(ErrorCode.UNAUTHORIZED_ORDER_ACCESS);
        }

        // Chỉ cho phép hủy khi đang ở trạng thái IN_PROGRESS
        if (order.getOrderStatus() != Order.OrderStatus.IN_PROGRESS) {
            throw new OrderCannotBeCancelledException(ErrorCode.ORDER_CAN_NOT_BE_CANCELLED);
        }

        // Cập nhật trạng thái đơn hàng
        order.setOrderStatus(Order.OrderStatus.CANCELED);
        order.setShipmentStatus(Order.ShipmentStatus.FAILED);
        order.setDeliveryDate(null);

        // Hoàn trả số lượng sản phẩm
        order.getOrderItems().forEach(item ->
                productService.updateProductStock(
                        item.getProduct().getProductId(),
                        item.getQuantity()
                )
        );

        return orderRepository.save(order);
    }

    // Hoàn thành đơn hàng khi giao hàng thành công
    @Transactional
    public Order completeOrder(Long orderId) {
        Order order = orderRepository.findById(orderId)
                .orElseThrow(() -> new OrderNotFoundException(ErrorCode.ORDER_NOT_FOUND));

        // Cập nhật khi giao hàng thành công
        order.setDeliveryDate(LocalDate.now());
        order.setOrderStatus(Order.OrderStatus.COMPLETED);
        order.setShipmentStatus(Order.ShipmentStatus.DELIVERED);

        // Nếu là COD, cập nhật trạng thái thanh toán
        if (order.getPaymentMethod().getPaymentMethodName().equalsIgnoreCase("COD")) {
            order.setPaymentStatus(Order.PaymentStatus.PAID);
            order.setPaymentDate(LocalDate.now());
        }

        return orderRepository.save(order);
    }

    // Tra cứu đơn hàng cho CUSTOMER (chỉ xem các order của chính mình)
    public List<Order> searchCustomerOrders(Order.OrderStatus status) {
        // Lấy user hiện tại
        User currentUser = userService.getCurrentUser();

        if (status != null) {
            return orderRepository.findByUser_UserIdAndOrderStatus(currentUser.getUserId(), status);
        }
        return orderRepository.findByUser_UserId(currentUser.getUserId());
    }

    // Tra cứu đơn hàng cho ADMIN (có thể tìm theo tên người dùng)
    public List<Order> searchAdminOrders(String userFullName, Order.OrderStatus status) {
        // Kiểm tra xem user hiện tại có phải ADMIN không
        User currentUser = userService.getCurrentUser();
        if (!currentUser.getRole().toString().equals("ADMIN")) {
            throw new UnauthorizedOrderAccessException(ErrorCode.UNAUTHORIZED_ORDER_ACCESS);
        }

        // Tìm kiếm user theo tên (nếu có)
        if (userFullName != null && !userFullName.isEmpty()) {
            List<User> users = userRepository.findByUserFullnameContaining(userFullName);

            if (status != null) {
                return orderRepository.findByUserInAndOrderStatus(users, status);
            }
            return orderRepository.findByUserIn(users);
        }

        // Nếu không có tên, trả về tất cả đơn hàng
        if (status != null) {
            return orderRepository.findByOrderStatus(status);
        }
        return orderRepository.findAll();
    }

    // Lấy chi tiết đơn hàng (chỉ cho phép xem order của chính mình)
    public Order getOrderDetails(Long orderId) {
        // Lấy user hiện tại
        User currentUser = userService.getCurrentUser();

        // Tìm order
        Order order = orderRepository.findById(orderId)
                .orElseThrow(() -> new OrderNotFoundException(ErrorCode.ORDER_NOT_FOUND));

        // Kiểm tra xem order có phải của user hiện tại không
        if (!order.getUser().getUserId().equals(currentUser.getUserId())) {
            throw new  UnauthorizedOrderAccessException(ErrorCode.UNAUTHORIZED_ORDER_ACCESS);
        }

        return order;
    }
}