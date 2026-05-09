@extends('Admin.master')

@section('title', 'Dashboard Admin - MomSpire')
@section('header_title', 'Dashboard')
@section('header_subtitle')
Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>
@endsection

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endpush

@section('content')
    <div class="row g-2 g-sm-3 g-lg-4 mb-4">
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="pengguna" title="Klik untuk lihat user role Pengguna" style="cursor: pointer;">
                <div class="stat-icon pink"><i class="bi bi-people-fill"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalPengguna" style="font-size: clamp(1rem, 2.5vw, 1.5rem);">{{ $penggunaCount }}</h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Pengguna</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="bidan" title="Klik untuk lihat user role Bidan" style="cursor: pointer;">
                <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalBidan" style="font-size: clamp(1rem, 2.5vw, 1.5rem);">{{ $bidanCount }}</h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Bidan</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="dokter" title="Klik untuk lihat user role Dokter" style="cursor: pointer;">
                <div class="stat-icon green"><i class="bi bi-person-workspace"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalDokter" style="font-size: clamp(1rem, 2.5vw, 1.5rem);">{{ $dokterCount }}</h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Dokter</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="articles" title="Klik untuk lihat daftar artikel" style="cursor: pointer;">
                <div class="stat-icon orange"><i class="bi bi-journal-text"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalArtikel" style="font-size: clamp(1rem, 2.5vw, 1.5rem);">0</h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Artikel</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="bi bi-chat-dots-fill"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" style="font-size: clamp(1rem, 2.5vw, 1.5rem);">567</h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Konsultasi</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="pengguna_hamil" title="Klik untuk lihat pengguna yang sedang hamil" style="cursor: pointer;">
                <div class="stat-icon teal"><i class="bi bi-heart-pulse"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statIbuHamilAktif" style="font-size: clamp(1rem, 2.5vw, 1.5rem);">{{ $ibuHamilAktifCount }}</h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Ibu Hamil Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 style="font-size: clamp(0.95rem, 3vw, 1.1rem); margin-bottom: 0.25rem;">Pertumbuhan Pengguna (Per Bulan)</h5>
                    <span class="text-muted small">Statistik 6 bulan terakhir</span>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 style="font-size: clamp(0.95rem, 3vw, 1.1rem); margin-bottom: 0.25rem;">Pembaca Artikel Per Minggu</h5>
                    <span class="text-muted small">Jumlah pembaca artikel minggu ini</span>
                </div>
                <div class="card-body">
                    <canvas id="articleReadersChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5 style="margin-bottom: 0;">Activity Log - Aktivitas Terbaru</h5>
                    <button class="btn btn-sm btn-outline-primary">Lihat Semua</button>
                </div>
                <div class="card-body p-0">
                    <div class="activity-log">
                        <div class="activity-item">
                            <div class="activity-icon" style="background: rgba(0, 184, 148, 0.15); color: #00b894;">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <div class="activity-content">
                                <h6 style="font-size: clamp(0.9rem, 2vw, 1rem); margin-bottom: 0.25rem;">User Baru Terdaftar</h6>
                                <p class="text-muted small">Siti Aminah mendaftar sebagai pengguna baru</p>
                                <span class="activity-time">2 jam yang lalu</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background: rgba(230, 57, 128, 0.15); color: #e63980;">
                                <i class="bi bi-chat-dots-fill"></i>
                            </div>
                            <div class="activity-content">
                                <h6 style="font-size: clamp(0.9rem, 2vw, 1rem); margin-bottom: 0.25rem;">Konsultasi Baru</h6>
                                <p class="text-muted small">Dewi Kartika berkonsultasi dengan Dr. Ratna</p>
                                <span class="activity-time">1 jam yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dashboardStatsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="dashboardStatsModalTitle">Detail Statistik</h5>
                        <small class="text-muted" id="dashboardStatsModalSubtitle">Daftar user berdasarkan kategori yang dipilih</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="dashboardStatsModalBody"></tbody>
                        </table>
                    </div>
                    <div id="dashboardStatsModalEmpty" class="text-center py-4 text-muted d-none">
                        Tidak ada data untuk kategori ini.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    window.MOMSPIRE_ADMIN_DASHBOARD_DATA = @json($dashboardUsers);

    document.addEventListener('DOMContentLoaded', function() {
        const ctx1 = document.getElementById('userGrowthChart');
        if(ctx1) {
            new Chart(ctx1, {
                type: 'line',
                data: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], datasets: [{ label: 'Users', data: [200, 400, 600, 800, 1000, 1200], borderColor: '#e63980', backgroundColor: 'rgba(230, 57, 128, 0.1)', fill: true, tension: 0.4 }] },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }
        const ctx2 = document.getElementById('articleReadersChart');
         if(ctx2) {
            new Chart(ctx2, {
                type: 'bar',
                data: { labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'], datasets: [{ label: 'Pembaca Minggu Ini', data: [14, 22, 18, 26, 24, 31, 20], backgroundColor: '#e63980' }] },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }

        const dashboardData = Array.isArray(window.MOMSPIRE_ADMIN_DASHBOARD_DATA) ? window.MOMSPIRE_ADMIN_DASHBOARD_DATA : [];
        const modalEl = document.getElementById('dashboardStatsModal');
        const modalTitle = document.getElementById('dashboardStatsModalTitle');
        const modalSubtitle = document.getElementById('dashboardStatsModalSubtitle');
        const modalBody = document.getElementById('dashboardStatsModalBody');
        const modalEmpty = document.getElementById('dashboardStatsModalEmpty');

        const labels = {
            pengguna: { title: 'Daftar Pengguna', subtitle: 'Semua akun dengan role pengguna' },
            bidan: { title: 'Daftar Bidan', subtitle: 'Semua akun dengan role bidan' },
            dokter: { title: 'Daftar Dokter', subtitle: 'Semua akun dengan role dokter' },
            pengguna_hamil: { title: 'Ibu Hamil Aktif', subtitle: 'Pengguna yang sedang hamil' },
            articles: { title: 'Daftar Artikel', subtitle: 'Artikel yang tersimpan pada browser (LocalStorage)' },
        };

        function getArticlesFromLocalStorage() {
            try {
                const raw = localStorage.getItem('momspire_articles');
                const parsed = raw ? JSON.parse(raw) : [];
                return Array.isArray(parsed) ? parsed : [];
            } catch (e) {
                console.error('Gagal membaca artikel dari localStorage', e);
                return [];
            }
        }

        function getRows(filter) {
            if (filter === 'pengguna_hamil') {
                return dashboardData.filter((user) => user.role === 'pengguna' && Number(user.is_hamil) === 1);
            }

            if (filter === 'articles') {
                // return articles list in a unified object shape for rendering
                const arts = getArticlesFromLocalStorage();
                return arts.map(a => ({
                    title: a.title || '-',
                    category: a.category || '-',
                    url: a.url || '#',
                    createdAt: a.createdAt || a.created_at || null,
                }));
            }

            return dashboardData.filter((user) => user.role === filter);
        }

        function badgeForUser(user) {
            if (user.role !== 'pengguna') {
                return '<span class="badge text-bg-light">-</span>';
            }

            return Number(user.is_hamil) === 1
                ? '<span class="badge text-bg-danger">Ibu Hamil Aktif</span>'
                : '<span class="badge text-bg-secondary">Tidak Hamil</span>';
        }

        // Article stat sync from ManajemenArtikel localStorage
        const statTotalArtikelEl = document.getElementById('statTotalArtikel');
        function updateArticleStat() {
            try {
                const arts = getArticlesFromLocalStorage();
                if (statTotalArtikelEl) statTotalArtikelEl.textContent = String(arts.length || 0);
            } catch (e) {
                console.error('Gagal update stat artikel', e);
            }
        }

        updateArticleStat();
        // update when storage changes (other tab or when ManajemenArtikel updates)
        window.addEventListener('storage', function(e) {
            if (e.key === 'momspire_articles') updateArticleStat();
        });

        document.querySelectorAll('.stat-card[data-role-filter]').forEach((card) => {
            card.addEventListener('click', function() {
                const filter = this.getAttribute('data-role-filter') || 'pengguna';
                const rows = getRows(filter);
                const meta = labels[filter] || { title: 'Detail Statistik', subtitle: 'Daftar data' };

                if (modalTitle) modalTitle.textContent = meta.title;
                if (modalSubtitle) modalSubtitle.textContent = meta.subtitle;
                const modalTable = modalEl ? modalEl.querySelector('table') : null;
                const modalThead = modalTable ? modalTable.querySelector('thead') : null;

                if (modalBody) {
                    if (filter === 'articles') {
                        if (modalThead) {
                            modalThead.innerHTML = `
                                <tr>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Link</th>
                                    <th>Dibuat</th>
                                </tr>
                            `;
                        }

                        modalBody.innerHTML = rows.map((a) => `
                            <tr>
                                <td>
                                    <div class="fw-semibold">${a.title || '-'}</div>
                                </td>
                                <td><span class="badge text-bg-light">${a.category || '-'}</span></td>
                                <td><a href="${a.url || '#'}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">${(a.url || '#').length > 60 ? (a.url || '#').slice(0,60) + '...' : (a.url || '#')}</a></td>
                                <td>${a.createdAt ? new Date(a.createdAt).toLocaleDateString('id-ID') : '-'}</td>
                            </tr>
                        `).join('');
                    } else {
                        if (modalThead) {
                            modalThead.innerHTML = `
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            `;
                        }

                        modalBody.innerHTML = rows.map((user) => `
                            <tr>
                                <td>${user.name || '-'}</td>
                                <td>${user.email || '-'}</td>
                                <td><span class="badge" style="text-transform:capitalize; background:${user.role === 'bidan' ? '#00b894' : user.role === 'dokter' ? '#00d4aa' : '#e63980'}; color:#fff;">${user.role || '-'}</span></td>
                                <td>${badgeForUser(user)}</td>
                            </tr>
                        `).join('');
                    }
                }

                if (modalEmpty) modalEmpty.classList.toggle('d-none', rows.length > 0);

                if (modalEl) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            });
        });
    });
</script>
@endpush
