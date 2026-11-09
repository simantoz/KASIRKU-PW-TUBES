// ========================================
// KASIRKU - AUTH LOGIC
// ========================================

// Check if user is logged in
function checkAuth() {
  const user = getCurrentUser();
  if (!user) {
    window.location.href = 'index.html';
    return null;
  }
  return user;
}

// Check if user has correct role for current page
function checkRole(requiredRole) {
  const user = checkAuth();
  if (!user) return;
  
  if (user.role !== requiredRole) {
    alert('Akses ditolak! Anda tidak memiliki izin untuk halaman ini.');
    logout();
  }
}

// Get current logged in user
function getCurrentUser() {
  const userJson = localStorage.getItem('currentUser');
  return userJson ? JSON.parse(userJson) : null;
}

// Login function
function login(email, password) {
  const user = USERS.find(u => u.email === email && u.password === password);
  
  if (user) {
    localStorage.setItem('currentUser', JSON.stringify(user));
    redirectByRole(user.role);
    return true;
  } else {
    return false;
  }
}

// Redirect based on user role
function redirectByRole(role) {
  switch(role) {
    case 'owner':
      window.location.href = 'owner.html';
      break;
    case 'gudang':
      window.location.href = 'gudang.html';
      break;
    case 'kasir':
      window.location.href = 'kasir.html';
      break;
    default:
      logout();
  }
}

// Logout function
function logout() {
  localStorage.removeItem('currentUser');
  localStorage.removeItem('cart');
  window.location.href = 'index.html';
}

// Display current user name in navbar
function displayUserName() {
  const user = getCurrentUser();
  if (user) {
    const userNameElement = document.getElementById('userName');
    if (userNameElement) {
      userNameElement.textContent = user.name;
    }
  }
}
