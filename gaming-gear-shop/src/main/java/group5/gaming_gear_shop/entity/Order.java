package group5.gaming_gear_shop.entity;

import jakarta.persistence.*;
import lombok.AccessLevel;
import lombok.Data;
import lombok.experimental.FieldDefaults;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

@Entity
@Table(name = "Orders")
@Data
@FieldDefaults(level = AccessLevel.PRIVATE)
public class Order {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "order_id")
    Long orderId;

    @ManyToOne
    @JoinColumn(name = "user_id")
    User user;

    @Column(name = "order_date")
    LocalDate orderDate;

    @Column(name = "total_amount")
    BigDecimal totalAmount;  // Tổng tiền của đơn hàng

    @Enumerated(EnumType.STRING)
    @Column(name = "order_status")
    OrderStatus orderStatus;

    @ManyToOne
    @JoinColumn(name = "payment_method_id")
    Payment_Methods paymentMethod;

    @Column(name = "payment_date")
    LocalDate paymentDate;

    @Enumerated(EnumType.STRING)
    @Column(name = "payment_status")
    PaymentStatus paymentStatus;

    @Column(name = "shipment_address")
    String shipmentAddress;

    @Column(name = "shipment_date")
    LocalDate shipmentDate;

    @Column(name = "delivery_date")
    LocalDate deliveryDate;

    @Enumerated(EnumType.STRING)
    @Column(name = "shipment_status")
    ShipmentStatus shipmentStatus;

    @OneToMany(mappedBy = "order", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    List<Order_Item> orderItems = new ArrayList<>();


    public enum OrderStatus {
        COMPLETED,
        IN_PROGRESS,
        CANCELED
    }

    public enum PaymentStatus {
        PAID,
        PENDING,
        NOT_PAID_YET
    }

    public enum ShipmentStatus {
        DELIVERED,
        IN_TRANSIT,
        FAILED
    }
}
