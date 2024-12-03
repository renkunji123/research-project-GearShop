package group5.gaming_gear_shop.exception.handler;

import lombok.AllArgsConstructor;
import lombok.Getter;

@Getter
@AllArgsConstructor
public enum ErrorCode {
    //Category
    CATEGORY_NOT_FOUND(400, "Category not found."),
    EXISTED_CATEGORY(400, "Category already exists."),
    //Brand
    BRAND_NOT_FOUND(400, "Brand not found."),
    EXISTED_BRAND(400, "Brand already exists."),
    //Product
    PRODUCT_NOT_FOUND(400, "Product not found."),
    EXISTED_PRODUCT(400, "Product already exists."),
    OUT_OF_STOCK(400, "Product is out of stock"),
    //Cart
    PRODUCT_EXISTED_IN_CART(400, "Product is already in the cart"),
    INVALID_QUANTITY(400, "Invalid quantity."),
    CART_NOT_FOUND(400, "Cart not found."),
    CART_ITEM_NOT_FOUND(400, "Cart item not found"),
    //Payment
    PAYMENT_METHOD_NOT_FOUND(400, "Payment method not found."),
    //Order
    ORDER_NOT_FOUND(400, "Order not found."),
    ORDER_CAN_NOT_BE_CANCELLED(400, "Order cannot be canceled in current status"),
    NO_CART_ITEM_SELECTED(400, "No cart item selected."),
    //User
    USER_NOT_FOUND(400, "User not found."),
    EXISTED_EMAIL(400, "Email is already in use."),
    INCORRECT_PASSWORD(400, "Password is incorrect."),
    INVALID_EMAIL_OR_PASSWORD(400, "Invalid email or password."),
    UNAUTHORIZED_ORDER_ACCESS(400, "You do not have permission to perform this function."),
    ;

    private final int code;
    private final String message;
}
