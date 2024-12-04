package group5.gaming_gear_shop.dto.brand;

import jakarta.validation.constraints.NotBlank;
import lombok.AccessLevel;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import lombok.experimental.FieldDefaults;

@Data
@AllArgsConstructor
@NoArgsConstructor
@FieldDefaults(level = AccessLevel.PRIVATE)
public class CreateBrandRequest {
    @NotBlank(message = "Brand ID cannot be blank")
    String brandId;
    @NotBlank(message = "Brand name cannot be blank")
    String brandName;
    String brandDescription;
}
