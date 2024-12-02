package group5.gaming_gear_shop.entity;

import com.fasterxml.jackson.annotation.JsonIgnore;
import jakarta.persistence.*;
import lombok.AccessLevel;
import lombok.Data;
import lombok.experimental.FieldDefaults;

import java.math.BigDecimal;

@Entity
@Table(name = "cart_item")
@Data
@FieldDefaults(level = AccessLevel.PRIVATE)
public class Cart_Item {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "cart_item_id")
    Long cartItemId;

    @ManyToOne
    @JsonIgnore
    @JoinColumn(name = "cart_id")
    Cart cart;

    @ManyToOne
    @JoinColumn(name = "product_id")
    Product product;

    @Column(name = "cart_item_quantity")
    Integer cartItemQuantity;

    @Column(name = "unit_price")
    BigDecimal unitPrice;  // Giá mỗi sản phẩm

    @Column(name = "sub_total_amount")
    BigDecimal subTotalAmount;  // Tiền tạm tính cho sản phẩm

    @PrePersist
    @PreUpdate
    public void calculateSubTotal() {
        if (this.product != null && this.cartItemQuantity != null) {
            this.unitPrice = this.product.getProductPrice();
            this.subTotalAmount = this.unitPrice.multiply(new BigDecimal(this.cartItemQuantity));
        }
    }
}
