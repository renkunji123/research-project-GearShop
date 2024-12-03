package group5.gaming_gear_shop.dto.cart;

import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;
import lombok.Data;

@Data
public class AddToCartRequest {
    @NotBlank(message = "Product ID cannot be blank.")
    private String productId; // ID sản phẩm
    @Min(value = 1, message = "Quantity must be at least 1.")
    private Integer quantity;  // Số lượng sản phẩm
}
