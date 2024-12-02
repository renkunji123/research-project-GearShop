package group5.gaming_gear_shop.service;

import group5.gaming_gear_shop.config.jwt.JwtService;
import group5.gaming_gear_shop.dto.user.ChangePasswordRequest;
import group5.gaming_gear_shop.dto.user.RegisterRequest;
import group5.gaming_gear_shop.dto.user.UpdateRequest;
import group5.gaming_gear_shop.dto.user.login.LoginRequest;
import group5.gaming_gear_shop.dto.user.login.LoginResponse;
import group5.gaming_gear_shop.entity.User;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import group5.gaming_gear_shop.exception.user.ExistedEmailException;
import group5.gaming_gear_shop.exception.user.IncorrectPasswordException;
import group5.gaming_gear_shop.exception.user.InvalidEmailOrPasswordException;
import group5.gaming_gear_shop.exception.user.UserNotFoundException;
import group5.gaming_gear_shop.repository.UserRepository;
import group5.gaming_gear_shop.utils.UserDetailsServiceImpl;
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
    private final PasswordEncoder passwordEncoder;
    private final JwtService jwtService;
    private final UserDetailsServiceImpl userDetailsService;

    // 1. Lấy thông tin người dùng hiện tại
    public User getMyInfo(String userId) {
        return userRepository.findById(userId)
                .orElseThrow(() -> new UserNotFoundException(ErrorCode.USER_NOT_FOUND));
    }

    // 2. Lấy danh sách người dùng
    public List<User> getAllUsers() {
        return userRepository.findAll();
    }

    // 3. Đăng ký
    public User registerUser(RegisterRequest request) {
        if (userRepository.findByEmail(request.getEmail()).isPresent()) {
            throw new ExistedEmailException(ErrorCode.EXISTED_EMAIL);
        }

        User user = new User();
        user.setUserFullname(request.getFullname());
        user.setEmail(request.getEmail());
        user.setUserAddress(request.getAddress());
        user.setPhoneNumber(request.getPhoneNumber());
        user.setUserGender(User.Gender.valueOf(request.getGender().toUpperCase()));
        user.setPassword(passwordEncoder.encode(request.getPassword()));
        user.setRole(User.Role.CUSTOMER);

        return userRepository.save(user);
    }

    // 4. Đăng nhập
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


    // 5. Cập nhật thông tin người dùng
    public User updateUser(String userId, UpdateRequest request) {
        User user = getMyInfo(userId);

        if (request.getFullname() != null) user.setUserFullname(request.getFullname());
        if (request.getEmail() != null) user.setEmail(request.getEmail());
        if (request.getAddress() != null) user.setUserAddress(request.getAddress());
        if (request.getPhoneNumber() != null) user.setPhoneNumber(request.getPhoneNumber());
        if (request.getGender() != null)
            user.setUserGender(User.Gender.valueOf(request.getGender().toUpperCase()));

        return userRepository.save(user);
    }

    // 6. Đổi mật khẩu
    public void changePassword(String userId, ChangePasswordRequest request) {
        User user = getMyInfo(userId);

        if (!passwordEncoder.matches(request.getOldPassword(), user.getPassword())) {
            throw new IncorrectPasswordException(ErrorCode.INCORRECT_PASSWORD);
        }

        user.setPassword(passwordEncoder.encode(request.getNewPassword()));
        userRepository.save(user);
    }

    // 7. Xóa người dùng
    public void deleteUser(String userId) {
        User user = getMyInfo(userId);
        userRepository.delete(user);
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
