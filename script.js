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

// Mở khung chat
openChatBtn.addEventListener('click', () => {
chatPopup.style.display = 'block';
openChatBtn.style.display = 'none'; // Ẩn nút mở chat
});

// Đóng khung chat
closeChatBtn.addEventListener('click', () => {
chatPopup.style.display = 'none';
openChatBtn.style.display = 'flex'; // Hiện lại nút mở chat
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