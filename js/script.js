function showLoginAlert() {
  const alertBox = document.getElementById('login-alert');
  if (alertBox) {
    alertBox.style.display = 'flex';
  }
}

function closePopup() {
  const alertBox = document.getElementById('login-alert');
  if (alertBox) {
    alertBox.style.display = 'none';
  }
}
