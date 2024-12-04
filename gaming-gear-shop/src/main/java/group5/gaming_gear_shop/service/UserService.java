package group5.gaming_gear_shop.service;

import group5.gaming_gear_shop.config.jwt.JwtService;
import group5.gaming_gear_shop.dto.user.ChangePasswordRequest;
import group5.gaming_gear_shop.dto.user.RegisterRequest;
import group5.gaming_gear_shop.dto.user.UpdateRequest;
import group5.gaming_gear_shop.dto.user.login.LoginRequest;
import group5.gaming_gear_shop.dto.user.login.LoginResponse;
import group5.gaming_gear_shop.entity.Cart;
import group5.gaming_gear_shop.entity.User;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import group5.gaming_gear_shop.exception.user.ExistedEmailException;
import group5.gaming_gear_shop.exception.user.IncorrectPasswordException;
import group5.gaming_gear_shop.exception.user.InvalidEmailOrPasswordException;
import group5.gaming_gear_shop.exception.user.UserNotFoundException;
import group5.gaming_gear_shop.repository.CartRepository;
import group5.gaming_gear_shop.repository.UserRepository;
import group5.gaming_gear_shop.utils.UserDetailsServiceImpl;
import jakarta.transaction.Transactional;
import lombok.AllArgsConstructor;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
@AllArgsConstructor

public class UserService {
    private final UserRepository userRepository;
    private final CartRepository cartRepository;
    private final PasswordEncoder passwordEncoder;
    private final JwtService jwtService;
    private final UserDetailsServiceImpl userDetailsService;

    public List<User> getAllUsers() {
        return userRepository.findAll();
    }

    @Transactional
    public User registerUser(RegisterRequest request) {
        if (userRepository.findByEmail(request.getEmail()).isPresent()) {
            throw new ExistedEmailException(ErrorCode.EXISTED_EMAIL);
        }

        User user = new User();
        user.setUserFullname(request.getUserFullname());
        user.setEmail(request.getEmail());
        user.setUserAddress(request.getUserAddress());
        user.setPhoneNumber(request.getPhoneNumber());
        user.setUserGender(User.Gender.valueOf(request.getUserGender().toUpperCase()));
        user.setPassword(passwordEncoder.encode(request.getPassword()));
        user.setRole(User.Role.CUSTOMER);

        // Lưu user
        User savedUser = userRepository.save(user);

        // Tạo giỏ hàng cho user
        Cart cart = new Cart();
        cart.setUser(savedUser);
        cartRepository.save(cart);

        return savedUser;
    }

    public LoginResponse login(LoginRequest request) {
        // Lấy thông tin UserDetails từ UserDetailsService
        UserDetails userDetails = userDetailsService.loadUserByUsername(request.getEmail());

        // Kiểm tra mật khẩu
        if (!passwordEncoder.matches(request.getPassword(), userDetails.getPassword())) {
            throw new InvalidEmailOrPasswordException(ErrorCode.INVALID_EMAIL_OR_PASSWORD);
        }

        // Tạo JWT Token
        String token = jwtService.generateToken(userDetails);

        // Lấy thêm thông tin từ database nếu cần
        userRepository.findByEmail(request.getEmail())
                .orElseThrow(() -> new UserNotFoundException(ErrorCode.USER_NOT_FOUND));

        // Trả về token
        return new LoginResponse(token);
    }

    @Transactional
    public User updateUser(UpdateRequest request) {
        // Lấy user hiện tại từ SecurityContext
        User user = getCurrentUser();

        // Cập nhật từng trường thông tin nếu được cung cấp
        if (request.getUserFullname() != null) user.setUserFullname(request.getUserFullname());
        if (request.getUserAddress() != null) user.setUserAddress(request.getUserAddress());
        if (request.getPhoneNumber() != null) user.setPhoneNumber(request.getPhoneNumber());
        if (request.getGender() != null)
            user.setUserGender(User.Gender.valueOf(request.getGender().toUpperCase()));

        return userRepository.save(user);
    }

    @Transactional
    public void changePassword(ChangePasswordRequest request) {
        // Lấy user hiện tại từ SecurityContext
        User user = getCurrentUser();

        // Kiểm tra mật khẩu cũ
        if (!passwordEncoder.matches(request.getOldPassword(), user.getPassword())) {
            throw new IncorrectPasswordException(ErrorCode.INCORRECT_PASSWORD);
        }

        // Mã hóa và cập nhật mật khẩu mới
        user.setPassword(passwordEncoder.encode(request.getNewPassword()));
        userRepository.save(user);
    }

    public void deleteUser(String userId) {
        userRepository.findById(userId).ifPresentOrElse(userRepository::delete,
                () -> {
                    throw new UserNotFoundException(ErrorCode.USER_NOT_FOUND);
                }
                );
    }

    public String getCurrentUserId() {
        return getCurrentUser().getUserId();
    }

    // Lấy thông tin User hiện tại từ SecurityContext
    public User getCurrentUser() {
        Authentication authentication = SecurityContextHolder.getContext().getAuthentication();
        if (authentication == null || !authentication.isAuthenticated()) {
            throw new IllegalStateException("No authenticated user found.");
        }

        String email = authentication.getName();
        return userRepository.findByEmail(email)
                .orElseThrow(() -> new UsernameNotFoundException("User not found with email: " + email));
    }
}
