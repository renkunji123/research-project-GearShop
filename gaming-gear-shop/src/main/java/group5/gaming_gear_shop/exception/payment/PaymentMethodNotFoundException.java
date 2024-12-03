package group5.gaming_gear_shop.exception.payment;

import group5.gaming_gear_shop.exception.handler.BaseCustomException;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
public class PaymentMethodNotFoundException extends RuntimeException implements BaseCustomException {
    private ErrorCode errorCode;

    public PaymentMethodNotFoundException(ErrorCode errorCode) {
        super(errorCode.getMessage());
        this.errorCode = errorCode;
    }

    @Override
    public ErrorCode getErrorCode() { return errorCode; }
}
