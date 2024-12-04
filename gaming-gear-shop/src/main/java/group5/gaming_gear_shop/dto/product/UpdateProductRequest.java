package group5.gaming_gear_shop.dto.product;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;

@Data
@AllArgsConstructor
@NoArgsConstructor
public class UpdateProductRequest {
    private String productName;
    private String productDescription;
    private String productImage;
    private BigDecimal productPrice;
    private Integer stockQuantity;
    private String categoryId;
    private String brandId;
}
