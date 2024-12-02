package group5.gaming_gear_shop.entity;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import lombok.AccessLevel;
import lombok.Data;
import lombok.experimental.FieldDefaults;

@Entity
@Data
@Table(name = "brands")
@FieldDefaults(level = AccessLevel.PRIVATE)
public class Brand {
    @Id
    @Column(name = "brand_id")
    String brandId;

    @Column(name = "brand_name")
    String brandName;

    @Column(name = "brand_description")
    String brandDescription;
}
