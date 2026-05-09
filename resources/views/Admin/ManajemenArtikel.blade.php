@extends('Admin.master')

@section('title', 'Manajemen Artikel - MomSpire')
@section('header_title', 'Manajemen Artikel')
@section('header_subtitle', 'Tambah dan kelola link artikel untuk pengguna')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="bi bi-journal-richtext"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="articleCountAnak">0</h3>
                    <p class="stat-label">Total Artikel Anak</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-link-45deg"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="articleCountTrimester1">0</h3>
                    <p class="stat-label">Kehamilan Trimester 1</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-link-45deg"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="articleCountTrimester2">0</h3>
                    <p class="stat-label">Kehamilan Trimester 2</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon pink"><i class="bi bi-link-45deg"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="articleCountTrimester3">0</h3>
                    <p class="stat-label">Kehamilan Trimester 3</p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <button class="quick-action w-100 h-100" data-bs-toggle="modal" data-bs-target="#addArticleModal">
                <div class="qa-icon"><i class="bi bi-file-earmark-plus-fill"></i></div>
                <span>Tambah Artikel Web</span>
            </button>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Daftar Artikel</h5>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary filter-btn" data-filter="semua">
                    <i class="bi bi-funnel me-1"></i> Semua Artikel
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="Anak">
                    <i class="bi bi-bookmark-fill me-1"></i> Artikel Anak
                </button>
                <button type="button" class="btn btn-sm btn-outline-info filter-btn" data-filter="Kehamilan Trimester 1">
                    <i class="bi bi-bookmark-fill me-1"></i> Trimester 1
                </button>
                <button type="button" class="btn btn-sm btn-outline-success filter-btn" data-filter="Kehamilan Trimester 2">
                    <i class="bi bi-bookmark-fill me-1"></i> Trimester 2
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning filter-btn" data-filter="Kehamilan Trimester 3">
                    <i class="bi bi-bookmark-fill me-1"></i> Trimester 3
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="articleTableWrapper">
                <table class="table table-hover align-middle mb-0" id="articleTable">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Link</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div id="articleEmptyState" class="text-center py-5 d-none">
                <i class="bi bi-journal-x fs-2 d-block mb-2 text-muted"></i>
                <p class="text-muted mb-0">Belum ada artikel. Tambahkan artikel pertama Anda.</p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addArticleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-file-earmark-plus-fill me-2"></i>Tambah Artikel Web</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addArticleForm">
                        <div class="mb-3">
                            <label class="form-label">Judul Artikel</label>
                            <input type="text" class="form-control" id="articleTitle" placeholder="Contoh: Tips Nutrisi Ibu Hamil" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" id="articleCategory" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Anak">Artikel Anak</option>
                                <option value="Kehamilan Trimester 1">Kehamilan Trimester 1</option>
                                <option value="Kehamilan Trimester 2">Kehamilan Trimester 2</option>
                                <option value="Kehamilan Trimester 3">Kehamilan Trimester 3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link Artikel</label>
                            <input type="url" class="form-control" id="articleUrl" placeholder="https://example.com/artikel" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat (Opsional)</label>
                            <textarea class="form-control" id="articleDescription" rows="3" placeholder="Ringkasan artikel..."></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-custom flex-fill">Simpan Artikel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function() {
        const STORAGE_KEY = 'momspire_articles';
        const tableBody = document.querySelector('#articleTable tbody');
        const articleCountEl = document.getElementById('articleCount');
        const activeLinksCountEl = document.getElementById('activeLinksCount');
        const emptyStateEl = document.getElementById('articleEmptyState');
        const tableWrapperEl = document.getElementById('articleTableWrapper');
        const addForm = document.getElementById('addArticleForm');

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            if (Number.isNaN(date.getTime())) return '-';
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function normalizeUrl(url) {
            const clean = url.trim();
            if (/^https?:\/\//i.test(clean)) return clean;
            return 'https://' + clean;
        }

        function getArticles() {
            try {
                const raw = localStorage.getItem(STORAGE_KEY);
                const parsed = raw ? JSON.parse(raw) : [];
                return Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                console.error('Gagal membaca data artikel:', error);
                return [];
            }
        }

        function saveArticles(articles) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(articles));
        }

        function updateStats(articles) {
            const countAnak = articles.filter(a => a.category === 'Anak').length;
            const countT1 = articles.filter(a => a.category === 'Kehamilan Trimester 1').length;
            const countT2 = articles.filter(a => a.category === 'Kehamilan Trimester 2').length;
            const countT3 = articles.filter(a => a.category === 'Kehamilan Trimester 3').length;
            
            document.getElementById('articleCountAnak').textContent = String(countAnak);
            document.getElementById('articleCountTrimester1').textContent = String(countT1);
            document.getElementById('articleCountTrimester2').textContent = String(countT2);
            document.getElementById('articleCountTrimester3').textContent = String(countT3);
        }

        let currentFilter = 'semua';

        function renderArticles(filterCategory = null) {
            const articles = getArticles();
            updateStats(articles);

            // Filter articles by category if specified
            let filtered = articles;
            if (filterCategory && filterCategory !== 'semua') {
                filtered = articles.filter(a => a.category === filterCategory);
            }

            tableBody.innerHTML = '';
            if (filtered.length === 0) {
                emptyStateEl.classList.remove('d-none');
                tableWrapperEl.classList.add('d-none');
                return;
            }

            emptyStateEl.classList.add('d-none');
            tableWrapperEl.classList.remove('d-none');

            filtered.forEach(article => {
                const safeTitle = escapeHtml(article.title);
                const safeCategory = escapeHtml(article.category);
                const safeUrl = escapeHtml(article.url);
                const safeDesc = escapeHtml(article.description || '');
                const shortUrl = safeUrl.length > 45 ? safeUrl.slice(0, 45) + '...' : safeUrl;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <div class="fw-semibold">${safeTitle}</div>
                        <div class="small text-muted">${safeDesc || '-'}</div>
                    </td>
                    <td><span class="badge text-bg-light">${safeCategory}</span></td>
                    <td><a href="${safeUrl}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">${shortUrl}</a></td>
                    <td>${formatDate(article.createdAt)}</td>
                    <td>
                        <a href="${safeUrl}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-box-arrow-up-right"></i> Buka
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" data-delete-id="${article.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });
        }

        function seedArticlesIfEmpty() {
            const existing = getArticles();
            if (existing.length > 0) return;

            const defaults = [
                {
                    id: 'art-' + Date.now(),
                    title: 'Panduan Gizi Seimbang Untuk Ibu Hamil',
                    category: 'Kehamilan Trimester 1',
                    url: 'https://www.alodokter.com/makanan-sehat-untuk-ibu-hamil',
                    description: 'Artikel rujukan nutrisi harian selama kehamilan.',
                    createdAt: new Date().toISOString()
                }
            ];
            saveArticles(defaults);
        }

        addForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const title = document.getElementById('articleTitle').value.trim();
            const category = document.getElementById('articleCategory').value.trim();
            const urlInput = document.getElementById('articleUrl').value.trim();
            const description = document.getElementById('articleDescription').value.trim();

            if (!title || !category || !urlInput) {
                alert('Harap lengkapi data artikel (khususnya pilih kategori).');
                return;
            }

            const normalizedUrl = normalizeUrl(urlInput);
            try {
                new URL(normalizedUrl);
            } catch (error) {
                alert('Format link artikel tidak valid.');
                return;
            }

            const articles = getArticles();
            articles.unshift({
                id: 'art-' + Date.now(),
                title,
                category,
                url: normalizedUrl,
                description,
                createdAt: new Date().toISOString()
            });
            saveArticles(articles);
            renderArticles(currentFilter);

            const modalEl = document.getElementById('addArticleModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            addForm.reset();
        });

        tableBody.addEventListener('click', function(e) {
            const button = e.target.closest('[data-delete-id]');
            if (!button) return;

            const id = button.getAttribute('data-delete-id');
            if (!id) return;
            if (!confirm('Yakin ingin menghapus artikel ini?')) return;

            const articles = getArticles();
            const filtered = articles.filter(article => article.id !== id);
            saveArticles(filtered);
            renderArticles(currentFilter);
        });

        document.addEventListener('DOMContentLoaded', function() {
            seedArticlesIfEmpty();
            renderArticles(currentFilter);

            // Initialize filter buttons
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    currentFilter = filter;
                    
                    // Update button styling
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    renderArticles(filter === 'semua' ? null : filter);
                });
            });
            
            // Mark first button as active
            if (filterButtons.length > 0) {
                filterButtons[0].classList.add('active');
            }
        });
    })();
</script>
@endpush
