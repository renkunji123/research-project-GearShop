package group5.gaming_gear_shop.repository;

import group5.gaming_gear_shop.entity.Order;
import group5.gaming_gear_shop.entity.User;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;

public interface OrderRepository extends JpaRepository<Order, Long> {
    List<Order> findByUser_UserIdAndOrderStatus(String userId, Order.OrderStatus status);

    List<Order> findByUser_UserId(String userId);

    List<Order> findByUserInAndOrderStatus(List<User> users, Order.OrderStatus status);

    List<Order> findByOrderStatus(Order.OrderStatus status);

    List<Order> findByUserIn(List<User> users);
}
