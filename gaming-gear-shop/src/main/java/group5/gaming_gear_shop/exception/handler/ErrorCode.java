package group5.gaming_gear_shop.exception.handler;

import lombok.AllArgsConstructor;
import lombok.Getter;

@Getter
@AllArgsConstructor
public enum ErrorCode {
    PRODUCT_NOT_FOUND(400, "Product Not Found!");

    private final int code;
    private final String message;
}
