package group5.gaming_gear_shop.exception.handler;


import group5.gaming_gear_shop.dto.ApiResponse;
import group5.gaming_gear_shop.exception.brand.BrandNotFoundException;
import group5.gaming_gear_shop.exception.brand.ExistedBrandException;
import group5.gaming_gear_shop.exception.cart.CartItemNotFoundException;
import group5.gaming_gear_shop.exception.cart.CartNotFoundException;
import group5.gaming_gear_shop.exception.cart.InvalidQuantityException;
import group5.gaming_gear_shop.exception.cart.ProductExistedInCartException;
import group5.gaming_gear_shop.exception.category.CategoryNotFoundException;
import group5.gaming_gear_shop.exception.category.ExistedCategoryException;
import group5.gaming_gear_shop.exception.order.NoCartItemSelectedException;
import group5.gaming_gear_shop.exception.order.OrderCannotBeCancelledException;
import group5.gaming_gear_shop.exception.order.OrderNotFoundException;
import group5.gaming_gear_shop.exception.payment.PaymentMethodNotFoundException;
import group5.gaming_gear_shop.exception.product.ExistedProductException;
import group5.gaming_gear_shop.exception.product.OutOfStockException;
import group5.gaming_gear_shop.exception.product.ProductNotFoundException;
import group5.gaming_gear_shop.exception.user.IncorrectPasswordException;
import group5.gaming_gear_shop.exception.user.InvalidEmailOrPasswordException;
import group5.gaming_gear_shop.exception.user.UnauthorizedOrderAccessException;
import group5.gaming_gear_shop.exception.user.UserNotFoundException;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;

@ControllerAdvice
public class GlobalExceptionHandler {
    @ExceptionHandler({
        //Common exceptions
        RuntimeException.class,
        IllegalStateException.class,
        //Category
        CategoryNotFoundException.class,
        ExistedCategoryException.class,
        //Brand
        BrandNotFoundException.class,
        ExistedBrandException.class,
        //Product
        ProductNotFoundException.class,
        ExistedProductException.class,
        OutOfStockException.class,
        //Cart
        CartNotFoundException.class,
        CartItemNotFoundException.class,
        InvalidQuantityException.class,
        ProductExistedInCartException.class,
        //Payment
        PaymentMethodNotFoundException.class,
        //Order
        OrderNotFoundException.class,
        OrderCannotBeCancelledException.class,
        NoCartItemSelectedException.class,
        //User
        UserNotFoundException.class,
        InvalidEmailOrPasswordException.class,
        ExistedBrandException.class,
        IncorrectPasswordException.class,
        UnauthorizedOrderAccessException.class,
    })
    ResponseEntity<ApiResponse<?>> handleCustomExceptions(RuntimeException ex) {
        if (ex instanceof BaseCustomException customEx) {
            ErrorCode errorCode = customEx.getErrorCode();
            return ResponseEntity.badRequest()
                    .body(ApiResponse.error(errorCode.getCode(), errorCode.getMessage()));
        }

        // Handle unexpected exceptions gracefully
        return ResponseEntity.status(500)
                .body(ApiResponse.error(500, "Internal Server Error: " + ex.getMessage()));
    }
}
