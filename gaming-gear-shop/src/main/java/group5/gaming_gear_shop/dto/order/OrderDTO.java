package group5.gaming_gear_shop.dto.order;

import group5.gaming_gear_shop.entity.Order;
import lombok.Builder;
import lombok.Data;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.List;

@Data
@Builder
public class OrderDTO {
    private String shipmentAddress;
    private Long paymentMethodId;
    private List<OrderItemDTO> orderItems;
}
