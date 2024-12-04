package group5.gaming_gear_shop.service;

import group5.gaming_gear_shop.dto.category.CreateCategoryRequest;
import group5.gaming_gear_shop.dto.category.UpdateCategoryRequest;
import group5.gaming_gear_shop.exception.category.CategoryNotFoundException;
import group5.gaming_gear_shop.exception.category.ExistedCategoryException;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import group5.gaming_gear_shop.repository.CategoryRepository;
import lombok.AllArgsConstructor;
import org.springframework.stereotype.Service;
import group5.gaming_gear_shop.entity.Category;
import java.util.List;

@Service
@AllArgsConstructor
public class CategoryService {
    private final CategoryRepository categoryRepository;

    // Get methods
    public List<Category> getAllCategories() {
        return categoryRepository.findAll();
    }

    public Category getCategoryById(String categoryId) {
        return categoryRepository.findById(categoryId)
                .orElseThrow(() -> new CategoryNotFoundException(ErrorCode.CATEGORY_NOT_FOUND));
    }

    // Create method
    public Category createNewCategory(CreateCategoryRequest request) {
        Category category = new Category();

        if (categoryRepository.existsByCategoryId(request.getCategoryId()))
            throw new ExistedCategoryException(ErrorCode.EXISTED_CATEGORY);

        category.setCategoryId(request.getCategoryId());
        category.setCategoryName(request.getCategoryName());
        category.setCategoryDescription(request.getCategoryDescription());

        return categoryRepository.save(category);
    }

    // Update method
    public Category updateCategory(String categoryId, UpdateCategoryRequest request) {
        Category category = getCategoryById(categoryId);

        category.setCategoryName(request.getCategoryName());
        category.setCategoryDescription(request.getCategoryDescription());

        return categoryRepository.save(category);
    }

    // Delete method
    public void deleteCategory(String categoryId) {
        categoryRepository.findById(categoryId).ifPresentOrElse(categoryRepository::delete,
                () -> {throw new CategoryNotFoundException(ErrorCode.CATEGORY_NOT_FOUND);});
    }
}
