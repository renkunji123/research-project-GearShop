package group5.gaming_gear_shop.dto.user;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import lombok.Data;

@Data
public class RegisterRequest {
    @NotBlank(message = "Full name cannot be blank.")
    private String fullname;

    @NotBlank(message = "Username cannot be blank.")
    private String username;

    @Email(message = "Invalid email format.")
    private String email;

    private String address;
    private String phoneNumber;
    private String gender; // MALE, FEMALE, OTHER

    @NotBlank(message = "Password cannot be blank.")
    private String password;
}
