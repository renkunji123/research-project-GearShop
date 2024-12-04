package group5.gaming_gear_shop.repository;

import group5.gaming_gear_shop.entity.Brand;
import jakarta.validation.constraints.NotBlank;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface BrandRepository extends JpaRepository<Brand, String> {
    boolean existsByBrandId(@NotBlank(message = "Brand ID cannot be blank") String brandId);
}
