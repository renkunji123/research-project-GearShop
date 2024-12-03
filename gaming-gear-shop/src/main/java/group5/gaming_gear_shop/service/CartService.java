package group5.gaming_gear_shop.service;

import group5.gaming_gear_shop.dto.cart.AddToCartRequest;
import group5.gaming_gear_shop.dto.cart.CartItemResponse;
import group5.gaming_gear_shop.dto.cart.CartResponse;
import group5.gaming_gear_shop.dto.cart.UpdateQuantityRequest;
import group5.gaming_gear_shop.entity.Cart;
import group5.gaming_gear_shop.entity.Cart_Item;
import group5.gaming_gear_shop.entity.User;
import group5.gaming_gear_shop.entity.Product;
import group5.gaming_gear_shop.exception.cart.CartItemNotFoundException;
import group5.gaming_gear_shop.exception.cart.CartNotFoundException;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import group5.gaming_gear_shop.exception.product.OutOfStockException;
import group5.gaming_gear_shop.exception.product.ProductNotFoundException;
import group5.gaming_gear_shop.repository.CartItemRepository;
import group5.gaming_gear_shop.repository.CartRepository;
import group5.gaming_gear_shop.repository.ProductRepository;
import jakarta.transaction.Transactional;
import lombok.AllArgsConstructor;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;
import java.util.List;
import java.util.stream.Collectors;

@Service
@AllArgsConstructor
public class CartService {
    private final CartRepository cartRepository;
    private final CartItemRepository cartItemRepository;
    private final ProductRepository productRepository;
    private final UserService userService;

    @Transactional
    public CartResponse viewCart() {
        User currentUser = userService.getCurrentUser();
        Cart cart = cartRepository.findByUser(currentUser)
                .orElseThrow(() -> new CartNotFoundException(ErrorCode.CART_NOT_FOUND));

        // Tính lại subtotal cho toàn bộ giỏ hàng
        updateCartSubTotal(cart);

        return mapToCartResponse(cart);
    }

    @Transactional
    public CartResponse addToCart(AddToCartRequest request) {
        User currentUser = userService.getCurrentUser();
        Cart cart = cartRepository.findByUser(currentUser)
                .orElseThrow(() -> new IllegalStateException("Cart not found for user"));

        // Tìm sản phẩm
        Product product = productRepository.findById(request.getProductId())
                .orElseThrow(() -> new ProductNotFoundException(ErrorCode.PRODUCT_NOT_FOUND));

        // Kiểm tra số lượng tồn kho
        if (request.getQuantity() > product.getStockQuantity()) {
            throw new OutOfStockException(ErrorCode.OUT_OF_STOCK);
        }

        // Tìm cart item hiện tại nếu đã tồn tại
        Cart_Item cartItem = cart.getCartItems().stream()
                .filter(item -> item.getProduct().getProductId().equals(request.getProductId()))
                .findFirst()
                .orElse(null);

        if (cartItem != null) {
            // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
            int newQuantity = cartItem.getCartItemQuantity() + request.getQuantity();

            if (newQuantity > product.getStockQuantity()) {
                throw new OutOfStockException(ErrorCode.OUT_OF_STOCK);
            }

            cartItem.setCartItemQuantity(newQuantity);
            cartItemRepository.save(cartItem);
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, tạo mới
            cartItem = new Cart_Item();
            cartItem.setCart(cart);
            cartItem.setProduct(product);
            cartItem.setCartItemQuantity(request.getQuantity());
            cart.getCartItems().add(cartItem);
            cartItemRepository.save(cartItem);
        }

        // Tính lại subtotal
        updateCartSubTotal(cart);
        cartRepository.save(cart);

        return mapToCartResponse(cart);
    }

    @Transactional
    public CartResponse updateCartItemQuantity(UpdateQuantityRequest request) {
        User currentUser = userService.getCurrentUser();
        Cart cart = cartRepository.findByUser(currentUser)
                .orElseThrow(() -> new IllegalStateException("Cart not found for user"));

        // Tìm cart item
        Cart_Item cartItem = cart.getCartItems().stream()
                .filter(item -> item.getProduct().getProductId().equals(request.getProductId()))
                .findFirst()
                .orElseThrow(() -> new CartItemNotFoundException(ErrorCode.CART_ITEM_NOT_FOUND));

        // Tìm sản phẩm
        Product product = cartItem.getProduct();

        // Kiểm tra số lượng
        if (request.getQuantity() < 1 || request.getQuantity() > product.getStockQuantity()) {
            throw new OutOfStockException(ErrorCode.OUT_OF_STOCK);
        }

        // Cập nhật số lượng
        cartItem.setCartItemQuantity(request.getQuantity());
        cartItemRepository.save(cartItem);

        // Tính lại subtotal
        updateCartSubTotal(cart);
        cartRepository.save(cart);

        return mapToCartResponse(cart);
    }

    @Transactional
    public CartResponse removeCartItem(String productId) {
        User currentUser = userService.getCurrentUser();
        Cart cart = cartRepository.findByUser(currentUser)
                .orElseThrow(() -> new IllegalStateException("Cart not found for user"));

        // Tìm và xóa cart item
        cart.getCartItems().removeIf(item ->
                item.getProduct().getProductId().equals(productId)
        );

        // Tính lại subtotal
        updateCartSubTotal(cart);
        cartRepository.save(cart);

        return mapToCartResponse(cart);
    }

    // Phương thức hỗ trợ tính toán subtotal
    private void updateCartSubTotal(Cart cart) {
        BigDecimal total = cart.getCartItems().stream()
                .map(Cart_Item::getSubTotalAmount)
                .reduce(BigDecimal.ZERO, BigDecimal::add);

        cart.setSubTotal(total);
    }

    // Phương thức mapping Cart sang CartResponse
    private CartResponse mapToCartResponse(Cart cart) {
        List<CartItemResponse> cartItems = cart.getCartItems().stream()
                .map(item -> new CartItemResponse(
                        item.getCartItemId(),
                        item.getProduct().getProductId(),
                        item.getProduct().getProductName(),
                        item.getCartItemQuantity(),
                        item.getUnitPrice(),
                        item.getSubTotalAmount()
                ))
                .collect(Collectors.toList());

        return new CartResponse(
                cart.getCartId(),
                cartItems,
                cart.getSubTotal()
        );
    }
}
