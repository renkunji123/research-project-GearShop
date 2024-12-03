package group5.gaming_gear_shop.repository;

import group5.gaming_gear_shop.entity.Cart_Item;
import group5.gaming_gear_shop.entity.Product;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface CartItemRepository extends JpaRepository<Cart_Item, Long> {

    Optional<Cart_Item> findByCartCartIdAndProduct(Long cartId, Product product);
}
