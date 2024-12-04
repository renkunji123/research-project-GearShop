package group5.gaming_gear_shop.dto.product;

import jakarta.validation.constraints.DecimalMin;
import jakarta.validation.constraints.NotBlank;
import lombok.AccessLevel;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import lombok.experimental.FieldDefaults;

import java.math.BigDecimal;

@Data
@AllArgsConstructor
@NoArgsConstructor
@FieldDefaults(level = AccessLevel.PRIVATE)
public class CreateProductRequest {
    @NotBlank(message = "Product ID cannot be blank.")
    String productId;
    @NotBlank(message = "Product name cannot be blank.")
    String productName;
    String productDescription;
    String productImage;
    @DecimalMin(value = "0.0", inclusive = false, message = "Product price must be positive.")
    BigDecimal productPrice;
    Integer stockQuantity;
    @NotBlank(message = "Category ID cannot be blank.")
    String categoryId;
    @NotBlank(message = "Brand ID cannot be blank.")
    String brandId;
}
