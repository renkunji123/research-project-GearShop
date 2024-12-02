package group5.gaming_gear_shop.dto.user;

import jakarta.validation.constraints.Email;
import lombok.Data;

@Data
public class UpdateRequest {
    private String fullname;

    private String username;

    @Email(message = "Invalid email format.")
    private String email;

    private String address;

    private String phoneNumber;

    private String gender; // MALE, FEMALE, OTHER
}
