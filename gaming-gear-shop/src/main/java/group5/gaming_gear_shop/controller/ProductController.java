package group5.gaming_gear_shop.controller;

import group5.gaming_gear_shop.dto.ApiResponse;
import group5.gaming_gear_shop.dto.product.CreateProductRequest;
import group5.gaming_gear_shop.dto.product.UpdateProductRequest;
import group5.gaming_gear_shop.entity.Product;
import group5.gaming_gear_shop.service.ProductService;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@AllArgsConstructor
public class ProductController {
    private final ProductService productService;

    // Search
    @GetMapping("/products/search-by-name")
    public ApiResponse<List<Product>> searchProductsByName(@RequestParam String productName) {
        return ApiResponse.success(productService.searchProductsByName(productName));
    }

    @GetMapping("/products/search-by-brand")
    public ApiResponse<List<Product>> searchByBrandName(@RequestParam String brandName) {
        return ApiResponse.success(productService.searchByBrandName(brandName));
    }

    @GetMapping("/products/search-by-category")
    public ApiResponse<List<Product>> searchByCategoryName(@RequestParam String categoryName) {
        return ApiResponse.success(productService.searchByCategoryName(categoryName));
    }

    // View
    @GetMapping("/products/all")
    public List<Product> getAllProducts() {
        return productService.getAllProducts();
    }

    @GetMapping("/products/view/{productId}")
    public Product getProductDetailView(@PathVariable String productId) {
        return productService.getProductById(productId);
    }

    // Create
    @PostMapping("/admin/products")
    public ApiResponse<Product> createProduct(@RequestBody @Valid CreateProductRequest product){
        ApiResponse<Product> apiResponse = new ApiResponse<>();
        apiResponse.setResult(productService.createNewProduct(product));
        return apiResponse;
    }

    // Update
    @PutMapping("/admin/{productId}")
    public Product updateProduct(@PathVariable String productId, @RequestBody UpdateProductRequest request) {
        return productService.updateProduct(productId, request);
    }

    // Delete
    @DeleteMapping("/admin/{productId}")
    public String deleteProduct(@PathVariable String productId) {
        productService.deleteProduct(productId);
        return "Deleted Product!";
    }
}
