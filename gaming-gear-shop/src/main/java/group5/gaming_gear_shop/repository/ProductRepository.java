package group5.gaming_gear_shop.repository;

import group5.gaming_gear_shop.entity.Product;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface ProductRepository extends JpaRepository<Product, String> {
    boolean existsByProductId(String productId);

    List<Product> findByProductNameContainingIgnoreCase(String productName);

    List<Product> findByBrand_BrandNameContainingIgnoreCase(String brandName);

    List<Product> findByCategory_CategoryNameContainingIgnoreCase(String categoryName);

}
