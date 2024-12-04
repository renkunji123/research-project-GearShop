package group5.gaming_gear_shop.repository;

import group5.gaming_gear_shop.entity.Cart;
import group5.gaming_gear_shop.entity.User;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface CartRepository extends JpaRepository<Cart, Long> {
    Optional<Cart> findByUser(User user);
}
