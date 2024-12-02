package group5.gaming_gear_shop.controller;

import group5.gaming_gear_shop.dto.ApiResponse;
import group5.gaming_gear_shop.dto.user.ChangePasswordRequest;
import group5.gaming_gear_shop.dto.user.RegisterRequest;
import group5.gaming_gear_shop.dto.user.UpdateRequest;
import group5.gaming_gear_shop.dto.user.login.LoginRequest;
import group5.gaming_gear_shop.dto.user.login.LoginResponse;
import group5.gaming_gear_shop.entity.User;
import group5.gaming_gear_shop.service.UserService;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@AllArgsConstructor
public class UserController {
    private final UserService userService;

    // 1. Lấy thông tin người dùng hiện tại
    @GetMapping("/users/profile")
    public ApiResponse<User> getMyInfo() {
        return ApiResponse.success(userService.getCurrentUser());
    }

    // 2. Lấy danh sách người dùng
    @GetMapping("admin/view-all-users")
    public ApiResponse<List<User>> getAllUsers() {
        return ApiResponse.success(userService.getAllUsers());
    }

    // 3. Đăng ký
    @PostMapping("/auth/register")
    public ApiResponse<User> registerUser(@RequestBody @Valid RegisterRequest request) {
        return ApiResponse.success(userService.registerUser(request));
    }

    // 4. Đăng nhập
    @PostMapping("/auth/login")
    public ApiResponse<LoginResponse> login(@RequestBody @Valid LoginRequest request) {
        return ApiResponse.success(userService.login(request));
    }

    // 5. Cập nhật thông tin
    @PutMapping("/users/update")
    public ApiResponse<User> updateUser( @RequestBody UpdateRequest request) {
        String currentUserId = userService.getCurrentUserId();
        return ApiResponse.success(userService.updateUser(currentUserId, request));
    }

    // 6. Đổi mật khẩu
    @PutMapping("/users/change-password")
    public ApiResponse<String> changePassword( @RequestBody ChangePasswordRequest request) {
        String currentUserId = userService.getCurrentUserId();
        userService.changePassword(currentUserId, request);
        return ApiResponse.success("Password updated successfully.");
    }

    // 7. Xóa người dùng
    @DeleteMapping("admin/delete/{userId}")
    public ApiResponse<String> deleteUser(@PathVariable String userId) {
        userService.deleteUser(userId);
        return ApiResponse.success("User deleted successfully.");
    }
}
