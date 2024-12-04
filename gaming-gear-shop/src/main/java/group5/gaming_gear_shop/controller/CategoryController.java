package group5.gaming_gear_shop.controller;

import group5.gaming_gear_shop.dto.ApiResponse;
import group5.gaming_gear_shop.dto.category.CreateCategoryRequest;
import group5.gaming_gear_shop.dto.category.UpdateCategoryRequest;
import group5.gaming_gear_shop.entity.Category;
import group5.gaming_gear_shop.service.CategoryService;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@AllArgsConstructor
@RequestMapping("admin/categories")
public class CategoryController {
    private final CategoryService categoryService;

    // View
    @GetMapping
    public ApiResponse<List<Category>> getAllCategories() {
        return ApiResponse.success(categoryService.getAllCategories());
    }

    @GetMapping("/{categoryId}")
    public ApiResponse<Category> getCategory(@PathVariable String categoryId) {
        return ApiResponse.success(categoryService.getCategoryById(categoryId));
    }

    // Create new
    @PostMapping
    public ApiResponse<Category> createCategory(@RequestBody @Valid CreateCategoryRequest category) {
        return ApiResponse.success(categoryService.createNewCategory(category));
    }

    // Update
    @PutMapping("/{categoryId}")
    public ApiResponse<Category> updateCategory(@PathVariable String categoryId, @RequestBody UpdateCategoryRequest request) {
        return ApiResponse.success(categoryService.updateCategory(categoryId, request));
    }

    // Delete
    @DeleteMapping("/{categoryId}")
    public ApiResponse<String> deleteCategory(@PathVariable String categoryId) {
        categoryService.deleteCategory(categoryId);
        return ApiResponse.success("Deleted Category");
    }
}
