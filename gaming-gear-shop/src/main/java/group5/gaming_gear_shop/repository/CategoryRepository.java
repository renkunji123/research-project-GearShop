package group5.gaming_gear_shop.repository;

import group5.gaming_gear_shop.entity.Category;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface CategoryRepository extends JpaRepository<Category, String> {
    boolean existsByCategoryId(String categoryId);
}
