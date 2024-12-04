package group5.gaming_gear_shop.dto.category;

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
public class CreateCategoryRequest {
    @NotBlank(message = "Category ID cannot be blank. ")
    String categoryId;
    @NotBlank(message = "Category name cannot be blank.")
    String categoryName;
    String categoryDescription;
}
