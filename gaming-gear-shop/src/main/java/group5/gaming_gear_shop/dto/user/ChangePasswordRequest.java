package group5.gaming_gear_shop.dto.user;

import jakarta.validation.constraints.NotBlank;
import lombok.Data;

@Data
public class ChangePasswordRequest {
    @NotBlank(message = "Old password cannot be blank.")
    private String oldPassword;

    @NotBlank(message = "New password cannot be blank.")
    private String newPassword;
}
