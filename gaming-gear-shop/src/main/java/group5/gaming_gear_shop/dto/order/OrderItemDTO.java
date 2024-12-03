package group5.gaming_gear_shop.dto.order;

import lombok.Builder;
import lombok.Data;

import java.math.BigDecimal;

@Data
@Builder
public class OrderItemDTO {
    private Long orderItemId;
    private String productId;
    private Integer quantity;
    private BigDecimal unitPrice;
    private BigDecimal subTotalAmount;
}
