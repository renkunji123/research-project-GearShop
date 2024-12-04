package group5.gaming_gear_shop.service;

import group5.gaming_gear_shop.dto.product.CreateProductRequest;
import group5.gaming_gear_shop.dto.product.UpdateProductRequest;
import group5.gaming_gear_shop.entity.Brand;
import group5.gaming_gear_shop.entity.Category;
import group5.gaming_gear_shop.entity.Product;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import group5.gaming_gear_shop.exception.product.ExistedProductException;
import group5.gaming_gear_shop.exception.product.OutOfStockException;
import group5.gaming_gear_shop.exception.product.ProductNotFoundException;
import group5.gaming_gear_shop.repository.BrandRepository;
import group5.gaming_gear_shop.repository.CategoryRepository;
import group5.gaming_gear_shop.repository.ProductRepository;
import jakarta.transaction.Transactional;
import lombok.AllArgsConstructor;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
@AllArgsConstructor
public class ProductService {
    private final ProductRepository productRepository;
    private final CategoryRepository categoryRepository;
    private final BrandRepository brandRepository;

    // Search methods
        // Search product by name
    public List<Product> searchProductsByName(String productName) {
        return productRepository.findByProductNameContainingIgnoreCase(productName);
    }

        // Search product by Brand name
    public List<Product> searchByBrandName(String brandName) {
        return productRepository.findByBrand_BrandNameContainingIgnoreCase(brandName);
    }

        // Search product by Category name
    public List<Product> searchByCategoryName(String categoryName) {
        return productRepository.findByCategory_CategoryNameContainingIgnoreCase(categoryName);
    }

    // Get methods
    public List<Product> getAllProducts(){
        return productRepository.findAll();
    }

    public Product getProductById(String productId){
        return productRepository.findById(productId)
                .orElseThrow(() -> new ProductNotFoundException(ErrorCode.PRODUCT_NOT_FOUND));
    }

    // Create method
    public Product createNewProduct(CreateProductRequest request) {
        Category category = categoryRepository.findById(request.getCategoryId())
                .orElseThrow(() -> new ProductNotFoundException(ErrorCode.CATEGORY_NOT_FOUND));
        Brand brand = brandRepository.findById(request.getBrandId())
                .orElseThrow(() -> new ProductNotFoundException(ErrorCode.BRAND_NOT_FOUND));

        Product product = new Product();

        if(productRepository.existsByProductId(request.getProductId()))
            throw new ExistedProductException(ErrorCode.EXISTED_PRODUCT);

        product.setProductId(request.getProductId());
        product.setProductName(request.getProductName());
        product.setProductDescription(request.getProductDescription());
        product.setProductImage(request.getProductImage());
        product.setProductPrice(request.getProductPrice());
        product.setStockQuantity(request.getStockQuantity());
        product.setCategory(category);
        product.setBrand(brand);

        return productRepository.save(product);
    }

    // Update method
    public Product updateProduct(String productId, UpdateProductRequest request) {
        Product product = getProductById(productId);

        product.setProductName(request.getProductName());
        product.setProductDescription(request.getProductDescription());
        product.setProductImage(request.getProductImage());
        product.setProductPrice(request.getProductPrice());
        product.setStockQuantity(request.getStockQuantity());

        Category category = categoryRepository.findById(request.getCategoryId())
                .orElseThrow(() -> new ProductNotFoundException(ErrorCode.CATEGORY_NOT_FOUND));
        Brand brand = brandRepository.findById(request.getBrandId())
                .orElseThrow(() -> new ProductNotFoundException(ErrorCode.BRAND_NOT_FOUND));

        product.setCategory(category);
        product.setBrand(brand);

        return productRepository.save(product);
    }

    // Delete method
    public void deleteProduct(String productId) {
        productRepository.findById(productId).ifPresentOrElse(productRepository::delete,
                () -> {
                    throw new ProductNotFoundException(ErrorCode.PRODUCT_NOT_FOUND);
                });
    }


    @Transactional
    public void updateProductStock(String productId, int quantityChange) {
        Product product = getProductById(productId);

        // Kiểm tra số lượng đủ để trừ không
        if (quantityChange > 0 && product.getStockQuantity() < quantityChange) {
            throw new OutOfStockException(ErrorCode.OUT_OF_STOCK);
        }

        // Cập nhật số lượng
        product.setStockQuantity(product.getStockQuantity() - quantityChange);
        productRepository.save(product);
    }
}
