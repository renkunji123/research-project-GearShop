package group5.gaming_gear_shop.dto.cart;

import lombok.Data;

@Data
public class UpdateQuantityRequest {
    private String productId; // ID sản phẩm
    private Integer quantity;  // Số lượng sản phẩm
}
