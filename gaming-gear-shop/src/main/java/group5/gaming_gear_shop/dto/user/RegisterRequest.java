package group5.gaming_gear_shop.dto.user;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import lombok.Data;

@Data
public class RegisterRequest {
    @NotBlank(message = "Full name cannot be blank.")
    private String userFullname;

    @Email(message = "Invalid email format.")
    private String email;

    private String userAddress;
    private String phoneNumber;
    private String userGender; // MALE, FEMALE, OTHER

    @NotBlank(message = "Password cannot be blank.")
    private String password;
}
