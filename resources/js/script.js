// ==================== DATA DUMMY (Backup jika DB error) ====================
const users = [
    { email: 'admin@gmail.com', password: 'admin123', role: 'admin', name: 'Administrator' },
    { email: 'bidan@gmail.com', password: 'bidan123', role: 'bidan', name: 'Bidan Sari Dewi' },
    { email: 'dokter@gmail.com', password: 'dokter123', role: 'dokter', name: 'Dr. Ratna Wijaya' },
    { email: 'ibu@gmail.com', password: 'ibu123', role: 'pengguna', name: 'Siti Aminah' }
];

// ==================== SETUP API & KONEKSI BACKEND ====================
// Deteksi path otomatis (root atau subfolder)
const currentPath = window.location.pathname;
const API_URL_RELATIVE = currentPath.includes('/Admin/') || currentPath.includes('/Bidan/') || currentPath.includes('/Dokter/') || currentPath.includes('/pengguna/')
               ? '../koneksi.php'
               : 'koneksi.php';

const pathParts = window.location.pathname.split('/').filter(Boolean);
const projectRoot = pathParts.length > 0 ? pathParts[0] : 'Project';
const API_URL_ABSOLUTE = `${window.location.origin}/${projectRoot}/koneksi.php`;

// Base path for app (e.g. /momspire/). Used to replace legacy ../index.html redirects.
const APP_BASE = projectRoot ? `/${projectRoot}/` : '/';

console.log("Path API (relative):", API_URL_RELATIVE);
console.log("Path API (absolute fallback):", API_URL_ABSOLUTE);

// Fungsi generic untuk fetch API
async function apiRequest(action, formData = new FormData()) {
    if (!formData.has('action')) {
        formData.append('action', action);
    }

    try {
        const response = await fetch(API_URL_RELATIVE, { method: 'POST', body: formData });
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
    } catch (error) {
        console.warn('API relative gagal, coba absolute fallback...', error);
        try {
            const fallbackData = new FormData();
            for (const [key, value] of formData.entries()) {
                fallbackData.append(key, value);
            }

            const fallbackResponse = await fetch(API_URL_ABSOLUTE, { method: 'POST', body: fallbackData });
            if (!fallbackResponse.ok) throw new Error(`HTTP error! status: ${fallbackResponse.status}`);
            return await fallbackResponse.json();
        } catch (fallbackError) {
            console.error('API fallback error:', fallbackError);
            return { status: 'error', message: 'Tidak bisa connect ke server (Backend)' };
        }
    }
}

// ==================== SESSION HELPERS ====================
function saveCurrentUser(user) {
    sessionStorage.setItem('currentUser', JSON.stringify(user));
    window.MOMSPIRE_CURRENT_USER = user;
}
function clearCurrentUser() {
    sessionStorage.removeItem('currentUser');
    window.MOMSPIRE_CURRENT_USER = null;
}
function getCurrentUser() {
    const u = sessionStorage.getItem('currentUser');
    if (u) return JSON.parse(u);
    return window.MOMSPIRE_CURRENT_USER || null;
}

// ==================== FUNGSI CRUD & DASHBOARD ====================
let currentUserFilter = 'all';

function updateUserFilterButtons() {
    const filterButtons = document.querySelectorAll('.user-filter-btn[data-role]');
    if (filterButtons.length === 0) return;

    filterButtons.forEach(btn => {
        const role = btn.getAttribute('data-role') || 'all';
        const isActive = role === currentUserFilter;
        btn.classList.toggle('active', isActive);
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });
}

// 1. Load Statistik Dashboard
async function loadDashboardStats() {
    if (window.MOMSPIRE_USE_LEGACY_ADMIN_API === false) return;
    const result = await apiRequest('get_stats');
    if (result.status === 'success') {
        const data = result.data;
        // Update Main Cards
        if(document.getElementById('statTotalPengguna')) document.getElementById('statTotalPengguna').textContent = data.pengguna;
        if(document.getElementById('statTotalBidan')) document.getElementById('statTotalBidan').textContent = data.bidan;
        if(document.getElementById('statTotalDokter')) document.getElementById('statTotalDokter').textContent = data.dokter;
        if(document.getElementById('statIbuHamilAktif')) document.getElementById('statIbuHamilAktif').textContent = data.ibu_hamil_aktif || 0;
        
        // Update Sidebar Stats
        if(document.getElementById('sidePengguna')) document.getElementById('sidePengguna').textContent = data.pengguna;
        if(document.getElementById('sideBidan')) document.getElementById('sideBidan').textContent = data.bidan;
        if(document.getElementById('sideDokter')) document.getElementById('sideDokter').textContent = data.dokter;
    }
}

// 2. Load Tabel User
async function loadUserTable(roleFilter = currentUserFilter) {
    if (window.MOMSPIRE_USE_LEGACY_ADMIN_API === false) return;
    currentUserFilter = roleFilter || 'all';
    updateUserFilterButtons();

    const formData = new FormData();
    if (currentUserFilter === 'pengguna_hamil') {
        formData.append('role', 'pengguna');
        formData.append('is_hamil', '1');
    } else if (currentUserFilter !== 'all') {
        formData.append('role', currentUserFilter);
    }

    const result = await apiRequest('get_users', formData);
    const tbody = document.querySelector('#userManagementModal tbody') || document.querySelector('#userManagementTable tbody');
    if (!tbody) return;

    tbody.innerHTML = ''; // Hapus data dummy
    
    if (result.status === 'success') {
        result.data.forEach(user => {
            const isHamil = Number(user.is_hamil) === 1;
            const roleColor = user.role === 'admin' ? 'background:#6f42c1' : 
                              user.role === 'bidan' ? 'background:#00b894' : 
                              user.role === 'dokter' ? 'background:#00d4aa' : 'background:#e63980';
            const statusBadge = user.role === 'pengguna'
                ? `<span class="badge ${isHamil ? 'text-bg-danger' : 'text-bg-secondary'}">${isHamil ? 'Sedang Hamil' : 'Tidak Hamil'}</span>`
                : '<span class="badge text-bg-light">-</span>';
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${user.nama}</td>
                <td>${user.email}</td>
                <td><span class="badge" style="${roleColor}; color:white; text-transform:capitalize;">${user.role}</span></td>
                <td>${statusBadge}</td>
                <td>
                    <button class="btn btn-sm btn-warning me-1" onclick='openEditUserModal(${JSON.stringify(JSON.stringify(user))})' title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})"><i class="bi bi-trash"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
}

function togglePregnancyField(roleSelectId, groupId, checkboxId) {
    const roleSelect = document.getElementById(roleSelectId);
    const groupEl = document.getElementById(groupId);
    const checkboxEl = document.getElementById(checkboxId);
    if (!roleSelect || !groupEl || !checkboxEl) return;

    const isPengguna = roleSelect.value === 'pengguna';
    groupEl.classList.toggle('d-none', !isPengguna);
    if (!isPengguna) {
        checkboxEl.checked = false;
    }
}

function initPregnancyFieldToggles() {
    const addRole = document.getElementById('inputRole');
    const editRole = document.getElementById('editRole');

    if (addRole) {
        addRole.addEventListener('change', () => togglePregnancyField('inputRole', 'inputPregnancyGroup', 'inputStatusHamil'));
        togglePregnancyField('inputRole', 'inputPregnancyGroup', 'inputStatusHamil');
    }

    if (editRole) {
        editRole.addEventListener('change', () => togglePregnancyField('editRole', 'editPregnancyGroup', 'editStatusHamil'));
        togglePregnancyField('editRole', 'editPregnancyGroup', 'editStatusHamil');
    }
}

// 3. Hapus User
async function deleteUser(id) {
    if (confirm('Yakin hapus user ini?')) {
        const formData = new FormData();
        formData.append('id', id);
        const result = await apiRequest('delete_user', formData);
        if (result.status === 'success') {
            loadUserTable(currentUserFilter);
            loadDashboardStats();
        } else {
            alert('Error: ' + (result.message || 'Gagal hapus user'));
        }
    }
}

function openEditUserModal(userJson) {
    const user = typeof userJson === 'string' ? JSON.parse(userJson) : userJson;
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editUserType').value = user.type || 'pengguna';
    document.getElementById('editNama').value = user.name || '';
    document.getElementById('editEmail').value = user.email || '';
    document.getElementById('editRole').value = user.type || 'pengguna';
    document.getElementById('editPassword').value = '';
    const editStatusHamil = document.getElementById('editStatusHamil');
    if (editStatusHamil) {
        editStatusHamil.checked = Number(user.is_hamil) === 1;
    }
    togglePregnancyField('editRole', 'editPregnancyGroup', 'editStatusHamil');

    const modalEl = document.getElementById('editUserModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

// ==================== ADD USER HANDLER (CREATE) ====================
const addUserForm = document.getElementById('addUserForm');
if (addUserForm) {
    addUserForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Ambil data dari ID Form
        const nama = document.getElementById('inputNama').value;
        const email = document.getElementById('inputEmail').value;
        const telp = document.getElementById('inputTelp').value;
        const role = document.getElementById('inputRole').value;
        const isHamil = document.getElementById('inputStatusHamil')?.checked ? '1' : '0';
        const pass = document.getElementById('inputPassword').value;

        const formData = new FormData();
        formData.append('nama', nama);
        formData.append('email', email);
        formData.append('no_telp', telp);
        formData.append('role', role);
        formData.append('is_hamil', isHamil);
        formData.append('password', pass);

        const result = await apiRequest('add_user', formData);

        if (result.status === 'success') {
            alert(result.message);
            const modalEl = document.getElementById('addUserModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            addUserForm.reset();
            loadDashboardStats(); // Refresh angka
            loadUserTable(currentUserFilter); // Refresh tabel
        } else {
            alert("Error: " + result.message);
        }
    });
}

// ==================== EDIT USER HANDLER (UPDATE) ====================
const editUserForm = document.getElementById('editUserForm');
if (editUserForm) {
    editUserForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const id = document.getElementById('editUserId').value;
        const nama = document.getElementById('editNama').value.trim();
        const email = document.getElementById('editEmail').value.trim();
        const telp = document.getElementById('editTelp').value.trim();
        const role = document.getElementById('editRole').value;
        const isHamil = document.getElementById('editStatusHamil')?.checked ? '1' : '0';
        const password = document.getElementById('editPassword').value;

        if (!id || !nama || !email || !telp || !role) {
            alert('Harap isi data edit user dengan lengkap!');
            return;
        }

        const formData = new FormData();
        formData.append('id', id);
        formData.append('nama', nama);
        formData.append('email', email);
        formData.append('no_telp', telp);
        formData.append('role', role);
        formData.append('is_hamil', isHamil);
        formData.append('password', password);

        const result = await apiRequest('update_user', formData);
        if (result.status === 'success') {
            alert(result.message || 'User berhasil diupdate');
            const modalEl = document.getElementById('editUserModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            editUserForm.reset();
            loadDashboardStats();
            loadUserTable(currentUserFilter);
        } else {
            alert('Error: ' + (result.message || 'Gagal update user'));
        }
    });
}

// ==================== LOGIN HANDLER (CONNECTED TO DB) ====================
// NOTE: Login form now handled by Laravel Fortify (/login route)
// Form submission is native POST, not AJAX legacy backend

// ==================== REGISTER HANDLER ====================
const registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const name = document.getElementById('registerName').value.trim();
        const email = document.getElementById('registerEmail').value.trim();
        const phone = document.getElementById('registerPhone').value.trim();
        const password = document.getElementById('registerPassword').value;
        const agreeTerms = document.getElementById('agreeTerms').checked;
        
        if (!name || !email || !phone || !password) { alert('Harap isi semua field!'); return; }
        if (password.length < 8) { alert('Password minimal 8 karakter!'); return; }
        if (!agreeTerms) { alert('Harap setujui Syarat & Ketentuan!'); return; }

        const formData = new FormData();
        formData.append('nama', name);
        formData.append('email', email);
        formData.append('no_telp', phone);
        formData.append('password', password);
        formData.append('role', 'pengguna'); // Default role

        const result = await apiRequest('add_user', formData);

        if (result.status === 'success') {
            alert('Registrasi Berhasil!\n\nSilakan login.');
            const modal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
            if (modal) modal.hide();
            registerForm.reset();
            document.getElementById('login-tab').click();
        } else {
            alert("Error: " + result.message);
        }
    });
}

// ==================== CANVAS BACKGROUND (KODE LAMA) ====================
const canvas = document.getElementById('bgCanvas');
if (canvas) {
    const ctx = canvas.getContext('2d');
    let particles = [];
    let animationId = null;

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    function createParticle() {
        return {
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.max(1, Math.random() * 3),
            vx: (Math.random() - 0.5) * 0.5,
            vy: (Math.random() - 0.5) * 0.5,
            opacity: Math.random() * 0.5 + 0.2
        };
    }

    function initParticles() {
        particles = [];
        const count = Math.min(200, Math.floor((canvas.width * canvas.height) / 8000));
        for (let i = 0; i < count; i++) {
            particles.push(createParticle());
        }
    }

    function drawParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            const base = 'rgba(230, 57, 128,';
            ctx.fillStyle = base + p.opacity + ')';
            ctx.fill();
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
            if (p.y < 0 || p.y > canvas.height) p.vy *= -1;
        });
        animationId = requestAnimationFrame(drawParticles);
    }

    resizeCanvas(); initParticles(); drawParticles();
    window.addEventListener('resize', () => { resizeCanvas(); initParticles(); });
}

// ==================== NAVBAR SCROLL (KODE LAMA) ====================
const navbar = document.getElementById('mainNavbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) navbar.classList.add('scrolled');
        else navbar.classList.remove('scrolled');
    });
}

// ==================== SIDEBAR TOGGLE ====================
const toggleSidebar = document.getElementById('toggleSidebar');
const adminSidebar = document.getElementById('adminSidebar');
const adminMain = document.querySelector('.admin-main');
if (toggleSidebar && adminSidebar) {
    toggleSidebar.addEventListener('click', () => {
        if (window.innerWidth <= 992) {
            adminSidebar.classList.toggle('active');
            return;
        }

        adminSidebar.classList.toggle('collapsed');
        if (adminMain) adminMain.classList.toggle('sidebar-collapsed');
    });
}

// ==================== ADMIN SIDEBAR MOBILE CLOSE ====================
const adminNavLinks = document.querySelectorAll('.admin-sidebar .nav-link');
if (adminNavLinks.length > 0) {
    adminNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (adminSidebar && window.innerWidth < 992) adminSidebar.classList.remove('active');
        });
    });
}

// ==================== LOGOUT (Modal + Toast) ====================
const logoutConfirmBtn = document.getElementById('logoutConfirmBtn');
const adminToastEl = document.getElementById('adminToast');
if (logoutConfirmBtn) {
    logoutConfirmBtn.addEventListener('click', function() {
        clearCurrentUser();
        const modalEl = document.getElementById('logoutModal');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();
        const logoutForm = document.getElementById('adminLogoutForm');

        if (logoutForm) {
            logoutForm.submit();
            return;
        }

        if (adminToastEl) {
            const toast = new bootstrap.Toast(adminToastEl, { delay: 900 });
            toast.show();
            setTimeout(() => { window.location.href = window.location.origin + APP_BASE; }, 900);
        } else {
            window.location.href = window.location.origin + APP_BASE;
        }
    });
}

// ==================== SMOOTH SCROLL (KODE LAMA) ====================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href.length > 1) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const navbarEl = document.getElementById('mainNavbar');
                const navbarHeight = navbarEl ? navbarEl.offsetHeight : 80;
                const offsetTop = target.offsetTop - navbarHeight - 20;
                window.scrollTo({ top: offsetTop, behavior: 'smooth' });
            }
        }
    });
});

// ==================== ACTIVE NAV LINK (KODE LAMA) ====================
const sections = document.querySelectorAll('section[id]');
if (sections.length > 0) {
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (window.scrollY >= sectionTop - 200) current = section.getAttribute('id');
        });
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) link.classList.add('active');
        });
    });
}

// ==================== SERVICE LINK INTERACTION (KODE LAMA) ====================
document.querySelectorAll('.service-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.getElementById('features');
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

// ==================== DASHBOARD PERSONALIZATION ====================
function personalizeDashboard() {
    const user = getCurrentUser();
    if (!user) {
        if (window.location.href.toLowerCase().includes('dashboard')) window.location.href = window.location.origin + APP_BASE;
        return;
    }
    const nameEl = document.getElementById('adminName') || document.getElementById('dashboardName');
    if (nameEl) nameEl.textContent = user.nama;
    const titleEl = document.querySelector('.header-title');
    if (titleEl) {
        const lockedTitle = (window.__MOMSPIRE_HEADER_TITLE_LOCK || '').toString().trim();
        if (lockedTitle) {
            titleEl.textContent = lockedTitle;
            return;
        }

        if (user.role === 'admin') titleEl.textContent = 'Dashboard Admin';
        else if (user.role === 'bidan') titleEl.textContent = 'Dashboard Bidan';
        else if (user.role === 'dokter') titleEl.textContent = 'Dashboard Dokter';
        else titleEl.textContent = 'Dashboard Pengguna';
    }
}

// ==================== AUTH MODAL: Forgot password handler ====================
document.addEventListener('click', function(e){
    const forgot = e.target.closest && e.target.closest('.forgot-link');
    if (forgot) {
        e.preventDefault();
        // show forgot pane inside auth modal
        const loginPane = document.getElementById('login');
        const forgotPane = document.getElementById('forgot');
        const authTabs = document.getElementById('authTabs');
        const modalContent = document.querySelector('#authModal .modal-content');
        if (loginPane && forgotPane) {
            loginPane.classList.remove('show','active');
            loginPane.classList.add('fade');
            forgotPane.classList.add('show','active');
            forgotPane.classList.remove('fade');
            // Hide tabs when showing forgot password
            if (authTabs) {
                authTabs.style.display = 'none';
            }
            if (modalContent) modalContent.classList.add('forgot-open');
        }
        // copy email
        const loginEmail = document.getElementById('loginEmail');
        const forgotEmail = document.getElementById('forgotEmailModal');
        if (loginEmail && forgotEmail && loginEmail.value) forgotEmail.value = loginEmail.value;
    }

    const forgotCancel = e.target.closest && e.target.closest('#forgotCancel');
    if (forgotCancel) {
        const loginPane = document.getElementById('login');
        const forgotPane = document.getElementById('forgot');
        const authTabs = document.getElementById('authTabs');
        const modalContent = document.querySelector('#authModal .modal-content');
        if (loginPane && forgotPane) {
            forgotPane.classList.remove('show','active');
            forgotPane.classList.add('fade');
            loginPane.classList.add('show','active');
            loginPane.classList.remove('fade');
            // Show tabs again when returning to login
            if (authTabs) {
                authTabs.style.display = 'flex';
            }
            if (modalContent) modalContent.classList.remove('forgot-open');
        }
    }
});

// Ensure tabs are visible when switching to register or login via tab buttons
document.addEventListener('click', function(e){
    const tabBtn = e.target.closest && e.target.closest('[data-bs-toggle="pill"]');
    if (tabBtn) {
        const authTabs = document.getElementById('authTabs');
        if (authTabs) {
            authTabs.style.display = 'flex';
        }
    }
});

// ==================== ADMIN SETTINGS HANDLER ====================
function initAdminSettingsPage() {
    const profileForm = document.getElementById('adminProfileForm');
    const passwordForm = document.getElementById('adminPasswordForm');
    if (!profileForm && !passwordForm) return;

    const user = getCurrentUser();
    if (!user || user.role !== 'admin') {
        alert('Akses pengaturan admin ditolak. Silakan login sebagai admin.');
        window.location.href = window.location.origin + APP_BASE;
        return;
    }

    const namaEl = document.getElementById('adminProfileNama');
    const emailEl = document.getElementById('adminProfileEmail');
    const telpEl = document.getElementById('adminProfileTelp');

    if (namaEl) namaEl.value = user.nama || '';
    if (emailEl) emailEl.value = user.email || '';
    if (telpEl) telpEl.value = user.no_telp || '';
}

// ==================== ROLE DASHBOARD HELPERS ====================
const CONSULTATION_STORAGE_KEY = 'momspire_consultations';

function getConsultationRecords() {
    try {
        const raw = localStorage.getItem(CONSULTATION_STORAGE_KEY);
        const parsed = raw ? JSON.parse(raw) : [];
        return Array.isArray(parsed) ? parsed : [];
    } catch (err) {
        console.error('Gagal membaca data konsultasi:', err);
        return [];
    }
}

function saveConsultationRecords(records) {
    localStorage.setItem(CONSULTATION_STORAGE_KEY, JSON.stringify(records));
}

function badgeByStatus(status) {
    const map = {
        pending: '<span class="badge text-bg-warning">Pending</span>',
        in_progress: '<span class="badge text-bg-info">Diproses</span>',
        dirujuk: '<span class="badge text-bg-danger">Dirujuk</span>',
        selesai: '<span class="badge text-bg-success">Selesai</span>'
    };
    return map[status] || '<span class="badge text-bg-secondary">-</span>';
}

function formatDateId(dateInput) {
    const d = new Date(dateInput);
    if (Number.isNaN(d.getTime())) return '-';
    return d.toLocaleString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
}

async function loadNotificationsByRole(role, limit = 10) {
    const formData = new FormData();
    formData.append('role', role);
    formData.append('limit', String(limit));
    const result = await apiRequest('get_notifications', formData);
    return result.status === 'success' ? result.data : [];
}

function initPenggunaDashboard() {
    if (!window.location.pathname.includes('DashboardPengguna')) return;
    const user = getCurrentUser();
    if (!user || user.role !== 'pengguna') return;

    const form = document.getElementById('penggunaConsultationForm');
    const tbody = document.getElementById('penggunaConsultationTableBody');
    const totalEl = document.getElementById('penggunaTotalKonsultasi');
    const pendingEl = document.getElementById('penggunaPendingKonsultasi');
    const notifCountEl = document.getElementById('penggunaNotifCount');
    const notifListEl = document.getElementById('penggunaNotifList');

    function renderConsultations() {
        if (!tbody) return;
        const mine = getConsultationRecords().filter(r => String(r.userId) === String(user.id));
        tbody.innerHTML = '';

        if (mine.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Belum ada konsultasi.</td></tr>';
        } else {
            mine.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
            mine.forEach(r => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${formatDateId(r.createdAt)}</td>
                    <td>${r.topic || '-'}</td>
                    <td style="text-transform:capitalize;">${r.targetRole || '-'}</td>
                    <td>${badgeByStatus(r.status)}</td>
                    <td>${r.notes ? r.notes : '-'}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        if (totalEl) totalEl.textContent = String(mine.length);
        if (pendingEl) pendingEl.textContent = String(mine.filter(r => r.status !== 'selesai').length);
    }

    async function renderNotifications() {
        if (!notifListEl) return;
        const notif = await loadNotificationsByRole('pengguna', 6);
        if (notifCountEl) notifCountEl.textContent = String(notif.length);

        if (notif.length === 0) {
            notifListEl.innerHTML = '<p class="text-muted mb-0">Belum ada notifikasi.</p>';
            return;
        }

        notifListEl.innerHTML = '';
        notif.forEach(n => {
            const item = document.createElement('div');
            item.className = 'alert-item mb-2';
            item.innerHTML = `
                <div class="alert-avatar blue"><i class="bi bi-bell-fill"></i></div>
                <div class="alert-info">
                    <h6 class="mb-1">${n.judul}</h6>
                    <span>${n.pesan}</span>
                    <div class="small text-muted mt-1">${formatDateId(n.created_at)}</div>
                </div>
            `;
            notifListEl.appendChild(item);
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const topic = (document.getElementById('consultTopic')?.value || '').trim();
            const targetRole = (document.getElementById('consultTargetRole')?.value || '').trim();
            const message = (document.getElementById('consultMessage')?.value || '').trim();
            if (!topic || !targetRole || !message) {
                alert('Silakan lengkapi data konsultasi.');
                return;
            }

            const records = getConsultationRecords();
            records.push({
                id: 'kons-' + Date.now(),
                userId: user.id,
                patientName: user.nama || 'Pengguna',
                patientEmail: user.email || '',
                topic,
                targetRole,
                message,
                status: 'pending',
                notes: '',
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            });
            saveConsultationRecords(records);
            form.reset();
            renderConsultations();
            alert('Konsultasi berhasil diajukan.');
        });
    }

    const profileForm = document.getElementById('penggunaProfileForm');
    const namaEl = document.getElementById('penggunaNama');
    const emailEl = document.getElementById('penggunaEmail');
    const telpEl = document.getElementById('penggunaTelp');
    if (namaEl) namaEl.value = user.nama || '';
    if (emailEl) emailEl.value = user.email || '';
    if (telpEl) telpEl.value = user.no_telp || '';

    if (profileForm) {
        profileForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const nama = (namaEl?.value || '').trim();
            const email = (emailEl?.value || '').trim();
            const no_telp = (telpEl?.value || '').trim();
            if (!nama || !email) {
                alert('Nama dan email wajib diisi.');
                return;
            }

            const formData = new FormData();
            formData.append('id', String(user.id));
            formData.append('nama', nama);
            formData.append('email', email);
            formData.append('no_telp', no_telp);
            formData.append('role', 'pengguna');
            formData.append('password', '');

            const result = await apiRequest('update_user', formData);
            if (result.status === 'success') {
                const updatedUser = { ...user, nama, email, no_telp };
                saveCurrentUser(updatedUser);
                personalizeDashboard();
                alert('Profil berhasil diperbarui.');
            } else {
                alert('Error: ' + (result.message || 'Gagal update profil'));
            }
        });
    }

    renderConsultations();
    renderNotifications();
}

async function initBidanDashboard() {
    if (!window.location.pathname.includes('DashboardBidan')) return;
    const user = getCurrentUser();
    if (!user || user.role !== 'bidan') return;

    const stats = await apiRequest('get_stats');
    const data = stats.status === 'success' ? stats.data : { pengguna: 0, ibu_hamil_aktif: 0 };
    const all = getConsultationRecords();
    const queue = all.filter(r => r.targetRole === 'bidan' || (r.targetRole === 'dokter' && r.status === 'dirujuk'));

    const ibuEl = document.getElementById('bidanIbuTerdaftar');
    const hamilEl = document.getElementById('bidanKehamilanAktif');
    const imunEl = document.getElementById('bidanImunisasiLengkap');
    const risikoEl = document.getElementById('bidanRisikoTinggi');
    if (ibuEl) ibuEl.textContent = String(data.pengguna || 0);
    if (hamilEl) hamilEl.textContent = String(data.ibu_hamil_aktif || 0);
    const immunTotal = queue.filter(r => (r.topic || '').toLowerCase().includes('imunisasi')).length;
    const immunDone = queue.filter(r => (r.topic || '').toLowerCase().includes('imunisasi') && r.status === 'selesai').length;
    if (imunEl) imunEl.textContent = immunTotal > 0 ? `${Math.round((immunDone / immunTotal) * 100)}%` : '0%';
    const risikoCount = queue.filter(r => r.status === 'dirujuk' || (r.topic || '').toLowerCase().includes('risiko')).length;
    if (risikoEl) risikoEl.textContent = String(risikoCount);

    const riskList = document.getElementById('bidanRiskList');
    if (riskList) {
        const highRisk = queue.filter(r => r.status === 'dirujuk').slice(0, 3);
        if (highRisk.length === 0) {
            riskList.innerHTML = '<p class="text-muted mb-0">Belum ada data risiko tinggi.</p>';
        } else {
            riskList.innerHTML = '';
            highRisk.forEach(r => {
                const item = document.createElement('div');
                item.className = 'alert-item';
                item.innerHTML = `
                    <div class="alert-avatar orange"><i class="bi bi-exclamation-fill"></i></div>
                    <div class="alert-info">
                        <h6>${r.patientName}</h6>
                        <span>${r.message.slice(0, 60)}${r.message.length > 60 ? '...' : ''}</span>
                    </div>
                `;
                riskList.appendChild(item);
            });
        }
    }

    const tbody = document.getElementById('bidanConsultationTableBody');
    if (tbody) {
        tbody.innerHTML = '';
        if (queue.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Belum ada konsultasi untuk bidan.</td></tr>';
        } else {
            queue.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
            queue.forEach(r => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${r.patientName}</td>
                    <td>${r.topic}</td>
                    <td>${r.targetRole === 'dokter' ? 'Rujuk Dokter' : 'Bidan'}</td>
                    <td>${badgeByStatus(r.status)}</td>
                    <td>${formatDateId(r.createdAt)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info me-1" data-action="process" data-id="${r.id}">Proses</button>
                        <button class="btn btn-sm btn-outline-danger me-1" data-action="refer" data-id="${r.id}">Rujuk</button>
                        <button class="btn btn-sm btn-outline-success" data-action="done" data-id="${r.id}">Selesai</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        tbody.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-id]');
            if (!btn) return;
            const action = btn.getAttribute('data-action');
            const id = btn.getAttribute('data-id');
            const records = getConsultationRecords();
            const idx = records.findIndex(r => r.id === id);
            if (idx < 0) return;

            if (action === 'process') {
                records[idx].status = 'in_progress';
            } else if (action === 'refer') {
                records[idx].status = 'dirujuk';
                records[idx].targetRole = 'dokter';
                records[idx].notes = 'Dirujuk oleh bidan untuk evaluasi dokter.';
            } else if (action === 'done') {
                const note = prompt('Catatan penyelesaian:', records[idx].notes || '');
                records[idx].status = 'selesai';
                records[idx].notes = note || records[idx].notes || 'Selesai ditangani bidan.';
            }

            records[idx].updatedAt = new Date().toISOString();
            saveConsultationRecords(records);
            initBidanDashboard();
        }, { once: true });
    }

    const addArticleBtn = document.getElementById('bidanAddArticleBtn');
    if (addArticleBtn) addArticleBtn.onclick = () => { window.location.href = window.location.origin + APP_BASE + 'admin/articles'; };
    const ancBtn = document.getElementById('bidanAncScheduleBtn');
    if (ancBtn) ancBtn.onclick = () => { alert('Fitur jadwal ANC siap dikembangkan pada modul berikutnya.'); };
    const reportBtn = document.getElementById('bidanReportBtn');
    if (reportBtn) reportBtn.onclick = () => { alert('Ringkasan: ' + queue.length + ' konsultasi aktif untuk bidan.'); };
}

async function initDokterDashboard() {
    if (!window.location.pathname.includes('DashboardDokter')) return;
    const user = getCurrentUser();
    if (!user || user.role !== 'dokter') return;

    const stats = await apiRequest('get_stats');
    const data = stats.status === 'success' ? stats.data : { pengguna: 0, ibu_hamil_aktif: 0 };
    const all = getConsultationRecords();
    const queue = all.filter(r => r.targetRole === 'dokter' || r.status === 'dirujuk');

    const pasienEl = document.getElementById('dokterTotalPasien');
    const hamilEl = document.getElementById('dokterKehamilanAktif');
    const selesaiEl = document.getElementById('dokterSelesaiPersen');
    const risikoEl = document.getElementById('dokterRisikoTinggi');
    if (pasienEl) pasienEl.textContent = String(data.pengguna || 0);
    if (hamilEl) hamilEl.textContent = String(data.ibu_hamil_aktif || 0);
    const done = queue.filter(r => r.status === 'selesai').length;
    if (selesaiEl) selesaiEl.textContent = queue.length > 0 ? `${Math.round((done / queue.length) * 100)}%` : '0%';
    if (risikoEl) risikoEl.textContent = String(queue.filter(r => r.status === 'dirujuk').length);

    const riskList = document.getElementById('dokterRiskList');
    if (riskList) {
        const referred = queue.filter(r => r.status === 'dirujuk').slice(0, 3);
        if (referred.length === 0) {
            riskList.innerHTML = '<p class="text-muted mb-0">Belum ada rujukan risiko tinggi.</p>';
        } else {
            riskList.innerHTML = '';
            referred.forEach(r => {
                const item = document.createElement('div');
                item.className = 'alert-item';
                item.innerHTML = `
                    <div class="alert-avatar pink"><i class="bi bi-exclamation-fill"></i></div>
                    <div class="alert-info">
                        <h6>${r.patientName}</h6>
                        <span>${r.notes || 'Rujukan dari bidan'}</span>
                    </div>
                `;
                riskList.appendChild(item);
            });
        }
    }

    const tbody = document.getElementById('dokterConsultationTableBody');
    if (tbody) {
        tbody.innerHTML = '';
        if (queue.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Belum ada konsultasi untuk dokter.</td></tr>';
        } else {
            queue.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
            queue.forEach(r => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${r.patientName}</td>
                    <td>${r.topic}</td>
                    <td>Dokter</td>
                    <td>${badgeByStatus(r.status)}</td>
                    <td>${formatDateId(r.createdAt)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info me-1" data-action="process" data-id="${r.id}">Proses</button>
                        <button class="btn btn-sm btn-outline-success" data-action="diagnose" data-id="${r.id}">Diagnosa</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        tbody.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-id]');
            if (!btn) return;
            const action = btn.getAttribute('data-action');
            const id = btn.getAttribute('data-id');
            const records = getConsultationRecords();
            const idx = records.findIndex(r => r.id === id);
            if (idx < 0) return;

            if (action === 'process') {
                records[idx].status = 'in_progress';
            } else if (action === 'diagnose') {
                const note = prompt('Tuliskan diagnosis/rekomendasi:', records[idx].notes || '');
                records[idx].status = 'selesai';
                records[idx].notes = note || 'Selesai ditangani dokter.';
            }

            records[idx].updatedAt = new Date().toISOString();
            saveConsultationRecords(records);
            initDokterDashboard();
        }, { once: true });
    }

    const addArticleBtn = document.getElementById('dokterAddArticleBtn');
    if (addArticleBtn) addArticleBtn.onclick = () => { window.location.href = window.location.origin + APP_BASE + 'admin/articles'; };
    const ancBtn = document.getElementById('dokterAncScheduleBtn');
    if (ancBtn) ancBtn.onclick = () => { alert('Fitur jadwal tindak lanjut akan disiapkan.'); };
    const reportBtn = document.getElementById('dokterReportBtn');
    if (reportBtn) reportBtn.onclick = () => { alert('Ringkasan: ' + queue.length + ' kasus untuk ditinjau dokter.'); };
}

// ==================== INITIALIZE (ONLOAD) ====================
document.addEventListener('DOMContentLoaded', () => {
    personalizeDashboard();
    initPregnancyFieldToggles();
    initAdminSettingsPage();
    initPenggunaDashboard();
    initBidanDashboard();
    initDokterDashboard();
    const useLegacyAdminApi = window.MOMSPIRE_USE_LEGACY_ADMIN_API !== false;

    // Init AOS jika ada
    if (window.AOS) AOS.init({ duration: 800, once: true });



    // READ by stat cards: klik total pengguna/bidan/dokter untuk filter tabel user.
    if (useLegacyAdminApi) {
        document.querySelectorAll('.stat-card[data-role-filter]').forEach(card => {
            card.addEventListener('click', () => {
                const roleFilter = card.getAttribute('data-role-filter') || 'all';
                const modalEl = document.getElementById('userManagementModal');
                if (modalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }
                loadUserTable(roleFilter);
            });
        });

        // Tombol filter pada halaman Manajemen User.
        document.querySelectorAll('.user-filter-btn[data-role]').forEach(btn => {
            btn.addEventListener('click', () => {
                const roleFilter = btn.getAttribute('data-role') || 'all';
                loadUserTable(roleFilter);
            });
        });

        // LOAD DATA DASHBOARD JIKA HALAMAN ADMIN
        if (window.location.pathname.includes('DashboardAdmin') || window.location.pathname.includes('ManajemenUser')) {
            loadDashboardStats();
            loadUserTable('all');
        }
    }
});