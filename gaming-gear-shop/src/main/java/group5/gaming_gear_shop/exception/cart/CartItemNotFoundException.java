package group5.gaming_gear_shop.exception.cart;

import group5.gaming_gear_shop.exception.handler.BaseCustomException;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
public class CartItemNotFoundException extends RuntimeException implements BaseCustomException {
    private final ErrorCode errorCode;
    public CartItemNotFoundException(ErrorCode errorCode) {
        super(errorCode.getMessage());
        this.errorCode = errorCode;
    }

    @Override
    public ErrorCode getErrorCode() { return errorCode; }
}
