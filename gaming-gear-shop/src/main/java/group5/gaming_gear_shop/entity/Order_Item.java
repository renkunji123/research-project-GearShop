package group5.gaming_gear_shop.entity;

import com.fasterxml.jackson.annotation.JsonIgnore;
import jakarta.persistence.*;
import lombok.AccessLevel;
import lombok.Data;
import lombok.experimental.FieldDefaults;

import java.math.BigDecimal;

@Entity
@Table(name = "order_item")
@Data
@FieldDefaults(level = AccessLevel.PRIVATE)
public class Order_Item {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "order_item_id")
    Long orderItemId;

    @ManyToOne
    @JsonIgnore
    @JoinColumn(name = "order_id")
    Order order;

    @ManyToOne
    @JoinColumn(name = "product_id")
    Product product;

    @Column(name = "quantity")
    Integer quantity;

    @Column(name = "unit_price")
    BigDecimal unitPrice;

    @Column(name = "sub_total_amount")
    BigDecimal subTotalAmount;  // Tiền tạm tính cho sản phẩm trong đơn hàng

    @PrePersist
    @PreUpdate
    public void calculateSubTotal() {
        if (this.product != null && this.quantity != null) {
            this.unitPrice = this.product.getProductPrice();
            this.subTotalAmount = this.unitPrice.multiply(new BigDecimal(this.quantity));
        }
    }
}
