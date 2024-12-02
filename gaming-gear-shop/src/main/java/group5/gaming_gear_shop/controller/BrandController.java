package group5.gaming_gear_shop.controller;

import group5.gaming_gear_shop.dto.ApiResponse;
import group5.gaming_gear_shop.dto.brand.CreateBrandRequest;
import group5.gaming_gear_shop.dto.brand.UpdateBrandRequest;
import group5.gaming_gear_shop.entity.Brand;
import group5.gaming_gear_shop.service.BrandService;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@AllArgsConstructor
@RequestMapping ("admin/brands")
public class BrandController {
    private final BrandService brandService;

    // View
    @GetMapping
    public ApiResponse<List<Brand>> getAllBrands() {
        return ApiResponse.success(brandService.getAllBrands());
    }

    @GetMapping("/{brandId}")
    public ApiResponse<Brand> getBrandById(@PathVariable String brandId) {
        return ApiResponse.success(brandService.getBrandById(brandId));
    }

    // Create
    @PostMapping
    public ApiResponse<Brand> createBrand(@RequestBody @Valid CreateBrandRequest brand) {
        return ApiResponse.success(brandService.createNewBrand(brand));
    }


    // Update
    @PutMapping("/{brandId}")
    public ApiResponse<Brand> updateBrand(@PathVariable String brandId, @RequestBody UpdateBrandRequest request) {
        return ApiResponse.success(brandService.updateBrand(brandId, request));
    }

    // Delete
    @DeleteMapping("/{brandId}")
    public ApiResponse<String> deleteBrand(@PathVariable String brandId){
        brandService.deleteBrand(brandId);
        return ApiResponse.success("Deleted brand!");
    }
}
