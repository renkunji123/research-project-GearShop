package group5.gaming_gear_shop.controller;

import group5.gaming_gear_shop.dto.ApiResponse;
import group5.gaming_gear_shop.dto.cart.AddToCartRequest;
import group5.gaming_gear_shop.dto.cart.CartResponse;
import group5.gaming_gear_shop.dto.cart.UpdateQuantityRequest;
import group5.gaming_gear_shop.service.CartService;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
import org.springframework.web.bind.annotation.*;

@RestController
@AllArgsConstructor
@RequestMapping("/cart")
public class CartController {
    private final CartService cartService;

    @GetMapping
    public ApiResponse<CartResponse> viewCart() {
        return ApiResponse.success(cartService.viewCart());
    }

    @PostMapping("/add")
    public ApiResponse<CartResponse> addToCart(@Valid @RequestBody AddToCartRequest request) {
        return ApiResponse.success(cartService.addToCart(request));
    }

    @PutMapping("/update")
    public ApiResponse<CartResponse> updateCartItemQuantity(@Valid @RequestBody UpdateQuantityRequest request) {
        return ApiResponse.success(cartService.updateCartItemQuantity(request));
    }

    @DeleteMapping("/remove/{productId}")
    public ApiResponse<CartResponse> removeCartItem(@PathVariable String productId){
        return ApiResponse.success(cartService.removeCartItem(productId));
    }
}
