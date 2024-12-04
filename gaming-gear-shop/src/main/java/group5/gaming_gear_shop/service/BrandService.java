package group5.gaming_gear_shop.service;

import group5.gaming_gear_shop.dto.brand.CreateBrandRequest;
import group5.gaming_gear_shop.dto.brand.UpdateBrandRequest;
import group5.gaming_gear_shop.entity.Brand;
import group5.gaming_gear_shop.exception.brand.BrandNotFoundException;
import group5.gaming_gear_shop.exception.brand.ExistedBrandException;
import group5.gaming_gear_shop.exception.handler.ErrorCode;
import group5.gaming_gear_shop.repository.BrandRepository;
import lombok.AllArgsConstructor;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
@AllArgsConstructor
public class BrandService {
    private final BrandRepository brandRepository;

    // Get methods
    public List<Brand> getAllBrands() {
        return brandRepository.findAll();
    }

    public Brand getBrandById(String brandId) {
        return brandRepository.findById(brandId)
                .orElseThrow(() -> new BrandNotFoundException(ErrorCode.BRAND_NOT_FOUND));
    }

    // Create method
    public Brand createNewBrand(CreateBrandRequest request) {
        Brand brand = new Brand();

        if (brandRepository.existsByBrandId(request.getBrandId()))
            throw new ExistedBrandException(ErrorCode.EXISTED_BRAND);

        brand.setBrandId(request.getBrandId());
        brand.setBrandName(request.getBrandName());
        brand.setBrandDescription(request.getBrandDescription());

        return brandRepository.save(brand);
    }

    // Update method
    public Brand updateBrand(String brandId, UpdateBrandRequest request) {
        Brand brand = getBrandById(brandId);

        brand.setBrandName(request.getBrandName());
        brand.setBrandDescription(request.getBrandDescription());

        return brandRepository.save(brand);
    }

    //Delete method
    public void deleteBrand(String brandId) {
        brandRepository.findById(brandId).ifPresentOrElse(brandRepository::delete,
                () -> {throw new BrandNotFoundException(ErrorCode.BRAND_NOT_FOUND);});
    }
}
