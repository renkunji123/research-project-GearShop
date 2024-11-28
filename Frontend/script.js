function switchToRegisterModal() {
    // Đóng modal Đăng Nhập trước
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.hide();

    // Mở modal Đăng Ký
    const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
    registerModal.show();
  }

// Lấy tất cả các mục cha có class 'parent'
const parents = document.querySelectorAll('.parent');

parents.forEach((parent) => {
parent.addEventListener('click', () => {
  // Tìm danh sách con kế tiếp
  const subList = parent.nextElementSibling;
  const icon = parent.querySelector('.dropdown-icon'); // Lấy icon

  // Chuyển đổi trạng thái ẩn/hiện danh sách con
  if (subList && subList.classList.contains('sub-list')) {
    const isHidden = subList.style.display === 'none';
    subList.style.display = isHidden ? 'block' : 'none';

    // Thêm hoặc xóa class 'rotate' cho icon
    if (icon) icon.classList.toggle('rotate', isHidden);
    }
  });
});

// Chức năng cho Cart
const cartItemsContainer = document.querySelector('.cart-items');
const addProductButton = document.querySelector('.add-product-btn');

// Cập nhật tổng giá khi thay đổi
function updateTotal() {
  let total = 0;
  document.querySelectorAll('.cart-item').forEach((item) => {
    const price = parseFloat(item.querySelector('.product-price').textContent.replace('$', ''));
    total += price;
  });
  document.querySelector('.checkout-btn').textContent = `Checkout ($${total.toFixed(2)})`;
}

// Thêm sự kiện cho các nút tăng/giảm và xóa sản phẩm
function addEventListenersForProduct(item) {
  const minusButton = item.querySelector('.minus-btn');
  const plusButton = item.querySelector('.plus-btn');
  const deleteButton = item.querySelector('.delete-btn');
  const quantitySpan = item.querySelector('.quantity');
  const priceDiv = item.querySelector('.product-price');

  let quantity = parseInt(quantitySpan.textContent);
  const pricePerUnit = parseFloat(priceDiv.textContent.replace('$', '') / quantity);

  minusButton.addEventListener('click', () => {
    if (quantity > 1) {
      quantity--;
      quantitySpan.textContent = quantity;
      priceDiv.textContent = `$${(quantity * pricePerUnit).toFixed(2)}`;
      updateTotal();
    } 
  });

  plusButton.addEventListener('click', () => {
    quantity++;
    quantitySpan.textContent = quantity;
    priceDiv.textContent = `$${(quantity * pricePerUnit).toFixed(2)}`;
    updateTotal();
  });

  deleteButton.addEventListener('click', () => {
    const confirmDelete = confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');
    if (confirmDelete) {
      item.remove();
      updateTotal();
    }
  });
}

// Thêm sản phẩm mới
addProductButton.addEventListener('click', () => {
  const newProduct = document.createElement('div');
  newProduct.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-3', 'cart-item');
  newProduct.innerHTML = `
    <div class="product-name">New Product</div>
    <div>
      <button class="btn btn-sm btn-secondary minus-btn">-</button>
      <span class="quantity">1</span>
      <button class="btn btn-sm btn-secondary plus-btn">+</button>
    </div>
    <div class="product-price">$0.00</div>
    <button class="btn btn-sm btn-danger delete-btn">X</button>
  `;

  cartItemsContainer.appendChild(newProduct);
  addEventListenersForProduct(newProduct);
  updateTotal();
});

// Áp dụng sự kiện cho các sản phẩm có sẵn
document.querySelectorAll('.cart-item').forEach(addEventListenersForProduct);


// Adjust quantity
decreaseQty.addEventListener('click', () => {
    if (productQty.value > 1) {
        productQty.value = parseInt(productQty.value) - 1;
    }
});

increaseQty.addEventListener('click', () => {
    productQty.value = parseInt(productQty.value) + 1;
});

// Add to Cart
addToCartBtn.addEventListener('click', () => {
    const qty = productQty.value;
    alert(`Added ${qty} item(s) to cart!`);
    modal.style.display = 'none';
    backdrop.style.display = 'none';
});

document.querySelectorAll('#increaseQty').forEach(button => {
  button.addEventListener('click', function () {
      const input = this.parentElement.querySelector('#productQty');
      input.value = parseInt(input.value) + 1;
  });
});

document.querySelectorAll('#decreaseQty').forEach(button => {
  button.addEventListener('click', function () {
      const input = this.parentElement.querySelector('#productQty');
      if (parseInt(input.value) > 1) {
          input.value = parseInt(input.value) - 1;
      }
  });
});