function switchToRegisterModal() {
    // Đóng modal Đăng Nhập trước
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.hide();

    // Mở modal Đăng Ký
    const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
    registerModal.show();
  }

  const openChatBtn = document.getElementById('openChatBtn');
  const closeChatBtn = document.getElementById('closeChatBtn');
  const chatPopup = document.getElementById('chatPopup');
  const chatInput = document.getElementById('chatInput');
  const chatBody = document.querySelector('.chat-body');
  const sendBtn = document.getElementById('sendBtn');




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

  openChatBtn.addEventListener('click', () => {
  chatPopup.style.display = 'block';
  openChatBtn.style.display = 'none';
  console.log("Toggle button clicked"); // Ẩn nút mở chat
  });
  
  // Đóng khung chat
  closeChatBtn.addEventListener('click', () => {
  chatPopup.style.display = 'none';
  openChatBtn.style.display = 'flex';
  console.log("Toggle button clicked"); // Hiện lại nút mở chat
  });
  
  // Xử lý sự kiện gửi tin nhắn
  sendBtn.addEventListener('click', sendMessage);
  
  chatInput.addEventListener('keypress', (event) => {
  if (event.key === 'Enter') {
    sendMessage();
  }
  });
  
  function sendMessage() {
  const message = chatInput.value.trim();
  if (message) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');
    messageElement.innerHTML = `<strong>Bạn:</strong> ${message}`;
    chatBody.appendChild(messageElement);
    chatInput.value = ''; // Xóa nội dung input
    chatBody.scrollTop = chatBody.scrollHeight; // Cuộn xuống cuối khung chat
  }
}

document.getElementById("chatToggleBtn").addEventListener("click", () => {
  const chatPopup = document.getElementById("chatPopup");
  chatPopup.style.display = chatPopup.style.display === "flex" ? "none" : "flex";
});

document.getElementById("closeChatBtn").addEventListener("click", () => {
  document.getElementById("chatPopup").style.display = "none";
});

document.getElementById("sendBtn").addEventListener("click", () => {
  const inputField = document.getElementById("chatInput");
  const userMessage = inputField.value;

  if (userMessage.trim()) {
      // Hiển thị tin nhắn người dùng
      const userMessageEl = document.createElement("div");
      userMessageEl.className = "message user";
      userMessageEl.innerHTML = `<strong>Bạn:</strong> ${userMessage}`;
      document.getElementById("chatBody").appendChild(userMessageEl);

      inputField.value = ""; // Xóa tin nhắn sau khi gửi
      document.getElementById("chatBody").scrollTop = document.getElementById("chatBody").scrollHeight;
  }
});

const typingForm = document.querySelector(".typing-form");
const chatContainer = document.querySelector(".chat-list");
const suggestions = document.querySelectorAll(".suggestion");
const toggleThemeButton = document.querySelector("#theme-toggle-button");
const deleteChatButton = document.querySelector("#delete-chat-button");

// State variables
let userMessage = null;
let isResponseGenerating = false;

// API configuration // Your API key here
const API_URL = `https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=${API_KEY}`;

// Load theme and chat data from local storage on page load
const loadDataFromLocalstorage = () => {
  const savedChats = localStorage.getItem("saved-chats");
  const isLightMode = (localStorage.getItem("themeColor") === "light_mode");

  // Apply the stored theme
  document.body.classList.toggle("light_mode", isLightMode);
  toggleThemeButton.innerText = isLightMode ? "dark_mode" : "light_mode";

  // Restore saved chats or clear the chat container
  chatContainer.innerHTML = savedChats || '';
  document.body.classList.toggle("hide-header", savedChats);

  chatContainer.scrollTo(0, chatContainer.scrollHeight); // Scroll to the bottom
}

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

// Handle sending outgoing chat messages
const handleOutgoingChat = () => {
  userMessage = typingForm.querySelector(".typing-input").value.trim() || userMessage;
  if(!userMessage || isResponseGenerating) return; // Exit if there is no message or response is generating

  isResponseGenerating = true;

  const html = `<div class="message-content">
                  <img class="avatar" src="images/user.jpg" alt="User avatar">
                  <p class="text"></p>
                </div>`;

  const outgoingMessageDiv = createMessageElement(html, "outgoing");
  outgoingMessageDiv.querySelector(".text").innerText = userMessage;
  chatContainer.appendChild(outgoingMessageDiv);
  
  typingForm.reset(); // Clear input field
  document.body.classList.add("hide-header");
  chatContainer.scrollTo(0, chatContainer.scrollHeight); // Scroll to the bottom
  setTimeout(showLoadingAnimation, 500); // Show loading animation after a delay
}


// Set userMessage and handle outgoing chat when a suggestion is clicked
suggestions.forEach(suggestion => {
  suggestion.addEventListener("click", () => {
    userMessage = suggestion.querySelector(".text").innerText;
    handleOutgoingChat();
  });
});

// Prevent default form submission and handle outgoing chat
typingForm.addEventListener("submit", (e) => {
  e.preventDefault(); 
  handleOutgoingChat();
});

loadDataFromLocalstorage();
