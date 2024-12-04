package group5.gaming_gear_shop.entity;

import com.fasterxml.jackson.annotation.JsonBackReference;
import com.fasterxml.jackson.annotation.JsonIgnore;
import jakarta.persistence.*;
import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import lombok.AccessLevel;
import lombok.Data;
import lombok.experimental.FieldDefaults;

@Entity
@Data
@Table (name = "users")
@FieldDefaults(level = AccessLevel.PRIVATE)
public class User {
    @Id
    @GeneratedValue(strategy = GenerationType.UUID)
    @Column(name = "user_id")
    String userId;

    @Column(name = "user_fullname")
    @NotBlank
    String userFullname;

    @Column(name = "email", unique = true, nullable = false)
    @Email
    String email;

    @Column(name = "phone_number")
    String phoneNumber;

    @Column(name = "user_address")
    String userAddress;

    @Enumerated(EnumType.STRING)
    @Column(name = "user_gender")
    Gender userGender;

    @Column(name = "password")
    @JsonIgnore
    String password;

    @Enumerated(EnumType.STRING)
    @JsonIgnore
    @Column(name = "role")
    Role role;

    @JsonBackReference
    @OneToOne(mappedBy = "user", cascade = CascadeType.ALL)
    Cart cart;

    public enum Gender {
        MALE,
        FEMALE,
        OTHER
    }

    public enum Role {
        ADMIN,
        CUSTOMER
    }
}
