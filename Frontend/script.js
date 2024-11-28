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


// API configuration // Your API key here
const API_URL = `https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=${API_KEY}`;

// Create a new message element and return it
const createMessageElement = (content, ...classes) => {
  const div = document.createElement("div");
  div.classList.add("message", ...classes);
  div.innerHTML = content;
  return div;
}

// Show typing effect by displaying words one by one
const showTypingEffect = (text, textElement, incomingMessageDiv) => {
  const words = text.split(' ');
  let currentWordIndex = 0;

  const typingInterval = setInterval(() => {
    // Append each word to the text element with a space
    textElement.innerText += (currentWordIndex === 0 ? '' : ' ') + words[currentWordIndex++];
    incomingMessageDiv.querySelector(".icon").classList.add("hide");

    // If all words are displayed
    if (currentWordIndex === words.length) {
      clearInterval(typingInterval);
      isResponseGenerating = false;
      incomingMessageDiv.querySelector(".icon").classList.remove("hide");
      localStorage.setItem("saved-chats", chatContainer.innerHTML); // Save chats to local storage
    }
    chatContainer.scrollTo(0, chatContainer.scrollHeight); // Scroll to the bottom
  }, 75);
}

// Fetch response from the API based on user message
const generateAPIResponse = async (incomingMessageDiv) => {
  const textElement = incomingMessageDiv.querySelector(".text"); // Getting text element

  try {
    // Send a POST request to the API with the user's message
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ 
        contents: [{ 
          role: "user", 
          parts: [{ text: userMessage }] 
        }] 
      }),
    });

    const data = await response.json();
    if (!response.ok) throw new Error(data.error.message);

    // Get the API response text and remove asterisks from it
    const apiResponse = data?.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, '$1');
    showTypingEffect(apiResponse, textElement, incomingMessageDiv); // Show typing effect
  } catch (error) { // Handle error
    isResponseGenerating = false;
    textElement.innerText = error.message;
    textElement.parentElement.closest(".message").classList.add("error");
  } finally {
    incomingMessageDiv.classList.remove("loading");
  }
}

// Show a loading animation while waiting for the API response
const showLoadingAnimation = () => {
  const html = `<div class="message-content">
                  <img class="avatar" src="images/gemini.svg" alt="Gemini avatar">
                  <p class="text"></p>
                  <div class="loading-indicator">
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                  </div>
                </div>
                <span onClick="copyMessage(this)" class="icon material-symbols-rounded">content_copy</span>`;

  const incomingMessageDiv = createMessageElement(html, "incoming", "loading");
  chatContainer.appendChild(incomingMessageDiv);

  chatContainer.scrollTo(0, chatContainer.scrollHeight); // Scroll to the bottom
  generateAPIResponse(incomingMessageDiv);
}

// Copy message text to the clipboard
const copyMessage = (copyButton) => {
  const messageText = copyButton.parentElement.querySelector(".text").innerText;

  navigator.clipboard.writeText(messageText);
  copyButton.innerText = "done"; // Show confirmation icon
  setTimeout(() => copyButton.innerText = "content_copy", 1000); // Revert icon after 1 second
}


const quickViewBtn = document.querySelector('.quick-view');
const modal = document.getElementById('quickViewModal');
const backdrop = document.getElementById('backdrop');
const closeModal = document.getElementById('closeModal');
const decreaseQty = document.getElementById('decreaseQty');
const increaseQty = document.getElementById('increaseQty');
const productQty = document.getElementById('productQty');
const addToCartBtn = document.getElementById('addToCart');

// Open modal
quickViewBtn.addEventListener('click', () => {
    modal.style.display = 'block';
    backdrop.style.display = 'block';
});

// Close modal
closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
    backdrop.style.display = 'none';
});

backdrop.addEventListener('click', () => {
    modal.style.display = 'none';
    backdrop.style.display = 'none';
});

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

// chatbot
const chatbotToggler = document.querySelector(".chatbot-toggler");
const closeBtn = document.querySelector(".close-btn");
const chatbox = document.querySelector(".chatbox");
const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");

let userMessage = null; // Variable to store user's message
const inputInitHeight = chatInput.scrollHeight;

// API configuration
const API_KEY = "AIzaSyDCYRoR8EN8hiq8H7_ol1sHkJCBZ5lS2MU"; // Your API key here

const createChatLi = (message, className) => {
  // Create a chat <li> element with passed message and className
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", `${className}`);
  let chatContent = className === "outgoing" ? `<p></p>` : `<span class="material-symbols-outlined">smart_toy</span><p></p>`;
  chatLi.innerHTML = chatContent;
  chatLi.querySelector("p").textContent = message;
  return chatLi; // return chat <li> element
};

const generateResponse = async (chatElement) => {
  const messageElement = chatElement.querySelector("p");

  // Define the properties and message for the API request
  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      contents: [
        {
          role: "user",
          parts: [{ text: userMessage }],
        },
      ],
    }),
  };

  // Send POST request to API, get response and set the reponse as paragraph text
  try {
    const response = await fetch(API_URL, requestOptions);
    const data = await response.json();
    if (!response.ok) throw new Error(data.error.message);

    // Get the API response text and update the message element
    messageElement.textContent = data.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, "$1");
  } catch (error) {
    // Handle error
    messageElement.classList.add("error");
    messageElement.textContent = error.message;
  } finally {
    chatbox.scrollTo(0, chatbox.scrollHeight);
  }
};

const handleChat = () => {
  userMessage = chatInput.value.trim(); // Get user entered message and remove extra whitespace
  if (!userMessage) return;

  // Clear the input textarea and set its height to default
  chatInput.value = "";
  chatInput.style.height = `${inputInitHeight}px`;

  // Append the user's message to the chatbox
  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);

  setTimeout(() => {
    // Display "Thinking..." message while waiting for the response
    const incomingChatLi = createChatLi("Thinking...", "incoming");
    chatbox.appendChild(incomingChatLi);
    chatbox.scrollTo(0, chatbox.scrollHeight);
    generateResponse(incomingChatLi);
  }, 600);
};

chatInput.addEventListener("input", () => {
  // Adjust the height of the input textarea based on its content
  chatInput.style.height = `${inputInitHeight}px`;
  chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
  // If Enter key is pressed without Shift key and the window
  // width is greater than 800px, handle the chat
  if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
    e.preventDefault();
    handleChat();
  }
});

sendChatBtn.addEventListener("click", handleChat);
closeBtn.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
