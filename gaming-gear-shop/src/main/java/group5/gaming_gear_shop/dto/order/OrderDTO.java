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
    private Long orderId;
    private Long userId;
    private LocalDate orderDate;
    private BigDecimal totalAmount;
    private Order.OrderStatus orderStatus;
    private Long paymentMethodId;
    private LocalDate paymentDate;
    private Order.PaymentStatus paymentStatus;
    private String shipmentAddress;
    private LocalDate shipmentDate;
    private LocalDate deliveryDate;
    private Order.ShipmentStatus shipmentStatus;
    private List<OrderItemDTO> orderItems;
}
