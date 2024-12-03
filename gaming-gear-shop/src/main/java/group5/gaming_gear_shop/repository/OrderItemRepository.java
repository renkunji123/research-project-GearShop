package group5.gaming_gear_shop.repository;

import group5.gaming_gear_shop.entity.Order_Item;
import org.springframework.data.jpa.repository.JpaRepository;

public interface OrderItemRepository extends JpaRepository<Order_Item, Long> {
}
