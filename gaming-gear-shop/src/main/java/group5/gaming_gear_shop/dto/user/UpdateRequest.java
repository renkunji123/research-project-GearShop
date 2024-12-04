package group5.gaming_gear_shop.dto.user;

import jakarta.validation.constraints.Email;
import lombok.Data;

@Data
public class UpdateRequest {
    private String userFullname;

    private String userAddress;

    private String phoneNumber;

    private String gender; // MALE, FEMALE, OTHER
}
