package group5.gaming_gear_shop.controller;

import group5.gaming_gear_shop.dto.ApiResponse;
import group5.gaming_gear_shop.dto.order.OrderDTO;
import group5.gaming_gear_shop.entity.Order;
import group5.gaming_gear_shop.service.OrderService;
import lombok.AllArgsConstructor;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@AllArgsConstructor
public class OrderController {
    private final OrderService orderService;

    // Lấy chi tiết đơn hàng
    @GetMapping("/orders/{orderId}")
    public ApiResponse<Order> getOrderDetails(@PathVariable Long orderId) {
        return ApiResponse.success(orderService.getOrderDetails(orderId));
    }

    // Tra cứu đơn hàng cho CUSTOMER
    @GetMapping("/orders")
    public ApiResponse<List<Order>> getAllOrders(@RequestParam(required = false) Order.OrderStatus status) {
        return ApiResponse.success(orderService.searchCustomerOrders(status));
    }

    // Tra cứu đơn hàng cho ADMIN
    @GetMapping("/admin/orders")
    public ApiResponse<List<Order>> searchAdminOrders(
            @RequestParam(required = false) String userFullName,
            @RequestParam(required = false) Order.OrderStatus status) {

        return ApiResponse.success(orderService.searchAdminOrders(userFullName, status));
    }

    // Tạo đơn hàng mới từ các CartItem được chọn
    @PostMapping("/orders/create")
    public ApiResponse<Order> createOrder(
            @RequestParam List<Long> selectedCartItemIds,
            @RequestBody OrderDTO orderDTO
    ) {
        return ApiResponse.success(orderService.createOrder(selectedCartItemIds, orderDTO));
    }

    // Hủy đơn hàng
    @PutMapping("/orders/cancel/{orderId}")
    public ApiResponse<Order> cancelOrder(@PathVariable Long orderId){
        return ApiResponse.success(orderService.cancelOrder(orderId));
    }

    // Hoàn thành đơn hàng (cho nhân viên/admin)
    @PutMapping("admin/orders/complete/{orderId}")
    public ApiResponse<Order> completeOrder(@PathVariable Long orderId) {
        return ApiResponse.success(orderService.completeOrder(orderId));
    }
}
