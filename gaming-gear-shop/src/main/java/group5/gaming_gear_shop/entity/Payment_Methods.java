package group5.gaming_gear_shop.entity;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import lombok.AccessLevel;
import lombok.Data;
import lombok.experimental.FieldDefaults;

@Entity
@Table(name = "payment_methods")
@Data
@FieldDefaults(level = AccessLevel.PRIVATE)
public class Payment_Methods {
    @Id
    @Column(name = "payment_method_id")
    Long paymentMethodId;

    @Column(name = "payment_method_name")
    String paymentMethodName;
}
