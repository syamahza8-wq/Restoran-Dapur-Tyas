/**
 * Authentication Module for Admin & Kasir
 * Dapur Tyas by Techno Garage
 */

const DEFAULT_ACCOUNTS = {
  admin: {
    username: 'admin',
    password: 'admin123tyas',
    name: 'Manager Tyas',
    role: 'admin'
  },
  kasir: {
    username: 'kasir',
    password: 'kasir123tyas',
    name: 'Kasir Front Office',
    role: 'kasir'
  }
};

const AuthService = {
  // Login method
  login(username, password) {
    const cleanUser = username.trim().toLowerCase();
    
    // Check credentials
    if (cleanUser === DEFAULT_ACCOUNTS.admin.username && password === DEFAULT_ACCOUNTS.admin.password) {
      const session = {
        username: cleanUser,
        name: DEFAULT_ACCOUNTS.admin.name,
        role: 'admin',
        loginTime: new Date().toISOString()
      };
      localStorage.setItem(STORAGE_KEYS.USER_SESSION, JSON.stringify(session));
      return { success: true, session };
    }
    
    if (cleanUser === DEFAULT_ACCOUNTS.kasir.username && password === DEFAULT_ACCOUNTS.kasir.password) {
      const session = {
        username: cleanUser,
        name: DEFAULT_ACCOUNTS.kasir.name,
        role: 'kasir',
        loginTime: new Date().toISOString()
      };
      localStorage.setItem(STORAGE_KEYS.USER_SESSION, JSON.stringify(session));
      return { success: true, session };
    }

    return { success: false, message: 'Username atau Password salah! Periksa kembali.' };
  },

  // Get current logged-in user
  getCurrentUser() {
    const raw = localStorage.getItem(STORAGE_KEYS.USER_SESSION);
    return raw ? JSON.parse(raw) : null;
  },

  // Check role guard
  requireAuth(allowedRoles = []) {
    const user = this.getCurrentUser();
    if (!user) {
      window.location.href = 'login.html';
      return null;
    }
    if (allowedRoles.length > 0 && !allowedRoles.includes(user.role)) {
      alert(`Akses ditolak! Halaman ini hanya untuk role: ${allowedRoles.join(', ')}`);
      if (user.role === 'kasir') {
        window.location.href = 'kasir.html';
      } else {
        window.location.href = 'admin.html';
      }
      return null;
    }
    return user;
  },

  // Logout
  logout() {
    localStorage.removeItem(STORAGE_KEYS.USER_SESSION);
    window.location.href = 'login.html';
  }
};

window.AuthService = AuthService;
