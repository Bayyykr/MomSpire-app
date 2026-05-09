<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MomSpire - Kesehatan Ibu dan Anak</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- AOS animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-Ho3qxc05Qz5B1do1BM6V+gZc5T9jp/+uvvFOvHML1Ng6sHdf6+n+W3YpUWLw5pX5hG1uhxTgnsCNjY72KkiY0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/style.css', 'resources/js/script.js'])
</head>
<body>

    <!-- Canvas Background -->
    <canvas id="bgCanvas"></canvas>

    <!-- ==================== NAVBAR ==================== -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('foto/logo.jpg') }}" alt="MomSpire Logo" class="brand-logo">
                <span>MomSpire</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#problem">Masalah & Solusi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur Utama</a></li>
                    <li class="nav-item"><a class="nav-link" href="#impact">Dampak</a></li>
                    <li class="nav-item"><a class="nav-link" href="#ecosystem">Ekosistem Platform</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tim">Tim Kami</a></li>
                </ul>
                <button class="btn btn-login-nav" data-bs-toggle="modal" data-bs-target="#authModal">
                    <i class="bi bi-person-circle me-2"></i>Masuk
                </button>
            </div>
        </div>
    </nav>

    <!-- ==================== HERO SECTION ==================== -->
    <section class="hero-section" id="beranda">
        <div class="hero-background">
            <div class="hero-blob blob-1"></div>
            <div class="hero-blob blob-2"></div>
            <div class="hero-blob blob-3"></div>
        </div>
        <div class="container hero-container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hero-content">
                        <div class="hero-badge" data-aos="fade-up" data-aos-delay="100">
                            <i class="bi bi-award-fill"></i>
                            <span>Platform Kesehatan Terintegrasi</span>
                        </div>
                        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                             Kesehatan<br>
                            <span class="text-gradient">Ibu & Anak</span>
                        </h1>
                        <p class="hero-desc" data-aos="fade-up" data-aos-delay="300">
                            MomSpire mengubah pemantauan kesehatan ibu dan anak melalui Buku KIA Digital terintegrasi, memungkinkan konsultasi tanpa hambatan, deteksi risiko real‑time, dan layanan kesehatan berbasis data untuk generasi yang lebih sehat.
                        </p>
                        <div class="hero-actions" data-aos="fade-up" data-aos-delay="400">
                            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#authModal">
                                <i class="bi bi-play-circle-fill"></i><span>Mulai Sekarang</span>
                            </button>
                            <button class="btn btn-outline-custom" onclick="document.getElementById('features').scrollIntoView({behavior: 'smooth'})">
                                <i class="bi bi-arrow-down-circle"></i><span>Pelajari Lebih Lanjut</span>
                            </button>
                        </div>
                        <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
                            <div class="stat-item">
                                <h3>1K+</h3>
                                <p>Pengguna Aktif</p>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <h3>200+</h3>
                                <p>Konsultasi/Bulan</p>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <h3>95%</h3>
                                <p>Tingkat Kepuasan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-offset="200">
                    <div class="hero-visual">
                        <div class="hero-illustration-wrapper">
                            <svg class="hero-illustration" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                <!-- Ibu Hamil -->
                                <circle cx="100" cy="80" r="20" fill="#e63980" opacity="0.9"/>
                                <ellipse cx="100" cy="150" rx="35" ry="50" fill="#e63980" opacity="0.85"/>
                                <path d="M65 140 Q60 180 70 220" stroke="#e63980" stroke-width="8" fill="none" stroke-linecap="round" opacity="0.8"/>
                                <path d="M135 140 Q140 180 130 220" stroke="#e63980" stroke-width="8" fill="none" stroke-linecap="round" opacity="0.8"/>
                                <!-- Bayi/Anak -->
                                <circle cx="280" cy="100" r="18" fill="#00b894" opacity="0.9"/>
                                <ellipse cx="280" cy="160" rx="25" ry="35" fill="#00b894" opacity="0.85"/>
                                <!-- Hati -->
                                <path d="M200 200 C200 200 180 180 170 190 C160 200 160 210 180 230 L200 245 L220 230 C240 210 240 200 230 190 C220 180 200 200 200 200" fill="#ff6b9d" opacity="0.7"/>
                                <!-- Dekoratif -->
                                <circle cx="150" cy="280" r="3" fill="#6f42c1" opacity="0.4"/>
                                <circle cx="320" cy="320" r="4" fill="#00d4aa" opacity="0.4"/>
                                <circle cx="80" cy="35₀" r="3.5" fill="#e63980" opacity="0.3"/>
                            </svg>
                        </div>
                        <div class="floating-card" data-aos="zoom-in" data-aos-delay="600">
                            <div class="card-badge">✓ Verifikasi Keamanan</div>
                            <div class="card-stat">
                                <i class="bi bi-shield-check"></i>
                                <span>Data Aman & Terenkripsi</span>
                            </div>
                            <div class="card-stat">
                                <i class="bi bi-lock-fill"></i>
                                <span>Privasi Terjamin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wave divider -->
        <div class="wave-divider">
            <svg viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 Q300,0 600,30 T1200,30 L1200,60 L0,60 Z" fill="currentColor"/>
            </svg>
        </div>
    </section>

    <!-- ==================== PROBLEM & SOLUTION SECTION ==================== -->
    <section class="problem-solution-section" id="problem" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- Problem Side -->
                <div class="col-lg-6">
                    <span class="section-tag" data-aos="fade-up">Tantangan</span>
                    <h2 class="section-title" data-aos="fade-up" data-aos-delay="100">
                        Data Kesehatan Ibu Masih Terfragmentasi
                    </h2>
                    <div class="problem-list" data-aos="fade-up" data-aos-delay="200">
                        <div class="problem-item">
                            <div class="problem-icon"><i class="bi bi-file-text"></i></div>
                            <div>
                                <h5>Manual Buku KIA</h5>
                                <p>Catatan kesehatan berbasis kertas rentan hilang dan rusak</p>
                            </div>
                        </div>
                        <div class="problem-item">
                            <div class="problem-icon"><i class="bi bi-diagram-3"></i></div>
                            <div>
                                <h5>Komunikasi Terputus</h5>
                                <p>Keterbatasan akses komunikasi antara ibu dan tenaga kesehatan</p>
                            </div>
                        </div>
                        <div class="problem-item">
                            <div class="problem-icon"><i class="bi bi-graph-down"></i></div>
                            <div>
                                <h5>Tingginya AKI dan AKB</h5>
                                <p>Akibat keterlambatan deteksi risiko dan penanganan yang tepat waktu</p>
                            </div>
                        </div>
                        <div class="problem-item">
                            <div class="problem-icon"><i class="bi bi-book"></i></div>
                            <div>
                                <h5>Kurangnya Media Edukasi Digital</h5>
                                <p>Keterbatasan akses terhadap media edukasi yang dapat diakses kapan saja</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Solution Side -->
                <div class="col-lg-6">
                    <span class="section-tag section-tag-alt" data-aos="fade-up">Solusi Kami</span>
                    <h2 class="section-title" data-aos="fade-up" data-aos-delay="100">
                        Platform Digital Kesehatan Ibu dan Anak Terintegrasi
                    </h2>
                    <div class="solution-list" data-aos="fade-up" data-aos-delay="200">
                        <div class="solution-item">
                            <div class="solution-icon"><i class="bi bi-shield-check"></i></div>
                            <div>
                                <h5>Digital Buku KIA</h5>
                                <p>Buku KIA digital yang aman dan dapat diakses kapan saja</p>
                            </div>
                        </div>
                        <div class="solution-item">
                            <div class="solution-icon"><i class="bi bi-telephone"></i></div>
                            <div>
                                <h5>Konsultasi Terpadu</h5>
                                <p>Koneksi tanpa hambatan antara ibu dengan tenaga kesehatan</p>
                            </div>
                        </div>
                        <div class="solution-item">
                            <div class="solution-icon"><i class="bi bi-heart-pulse"></i></div>
                            <div>
                                <h5>Pemantauan Rutin & Kontrol Gizi Digital</h5>
                                <p>Pemantauan rutin masa kehamilan dan alat kontrol gizi digital</p>
                            </div>
                        </div>
                        <div class="solution-item">
                            <div class="solution-icon"><i class="bi bi-newspaper"></i></div>
                            <div>
                                <h5>Edukasi Digital Aksesibel</h5>
                                <p>Artikel edukasi yang dapat diakses kapan saja sesuai dengan masa kehamilan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FEATURES SECTION ==================== -->
    <section class="features-section" id="features" data-aos="fade-up">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag">Fitur Utama</span>
                <h2 class="section-title">Solusi Kesehatan Ibu & Anak Lengkap</h2>
                <p class="section-subtitle">Platform terpadu untuk manajemen kesehatan ibu dan anak di Indonesia</p>
            </div>
            <!-- Features Grid 3x2 -->
            <div class="row g-4 mt-5">
                <!-- TOP ROW: 3 Cards -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <div class="service-card feature-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-journal-medical"></i>
                        </div>
                        <h4>Buku KIA Digital</h4>
                        <p>Buku KIA Digital terintegrasi dalam satu platform terpercaya.</p>
                        <a href="#" class="service-link">Pelajari <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card feature-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-camera-video-fill"></i>
                        </div>
                        <h4>Konsultasi Online</h4>
                        <p>Konsultasi chat real-time dengan tenaga kesehatan.</p>
                        <a href="#" class="service-link">Pelajari <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card feature-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-clipboard-heart"></i>
                        </div>
                        <h4>Status Kehamilan</h4>
                        <p>Status kesehatan secara detail dan deteksi resiko.</p>
                        <a href="#" class="service-link">Pelajari <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- BOTTOM ROW: 3 Cards -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card feature-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-lightning-fill"></i>
                        </div>
                        <h4>Kalkulator Gizi</h4>
                        <p>Kalkulator untuk menghitung status gizi ibu dan anak sesuai berat badan ideal.</p>
                        <a href="#" class="service-link">Pelajari <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card feature-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <h4>Artikel Edukasi</h4>
                        <p>Artikel edukatif tentang kehamilan, menyusui, dan perawatan anak.</p>
                        <a href="#" class="service-link">Pelajari <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-card feature-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-calendar-heart-fill"></i>
                        </div>
                        <h4>Jadwal Imunisasi & Kontrol</h4>
                        <p>Jadwal vaksinasi dan kontrol dengan notifikasi pengingat lengkap.</p>
                        <a href="#" class="service-link">Pelajari <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== DATA & IMPACT SECTION (Investor Section) ==================== -->
    <section class="impact-section" id="impact" data-aos="fade-up">
        <div class="container">
            <div class="section-header text-center mb-5">
                <span class="section-tag">Dampak</span>
                <h2 class="section-title">Transformasi Kesehatan Ibu & Anak</h2>
                <p class="section-subtitle">Wawasan berbasis data untuk peningkatan kesehatan ibu dan anak</p>
            </div>

            <!-- Context -->
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h3 class="impact-title" data-aos="fade-up">Transformasi Kesehatan Melalui Data</h3>
                    <p class="impact-text" data-aos="fade-up" data-aos-delay="100">
                        Platform kami memungkinkan deteksi dini komplikasi kehamilan, meningkatkan kesehatan ibu dan anak, serta mengurangi angka kematian yang dapat dicegah. Melalui sistem data terintegrasi dan monitoring real-time, kami ciptakan masa depan yang lebih sehat untuk semua ibu dan anak Indonesia.
                    </p>
                    <ul class="impact-benefits" data-aos="fade-up" data-aos-delay="200">
                        <li><i class="bi bi-check-circle-fill"></i> Deteksi risiko 40% lebih cepat</li>
                        <li><i class="bi bi-check-circle-fill"></i> 95% tingkat kepuasan pengguna</li>
                        <li><i class="bi bi-check-circle-fill"></i> Monitoring kesehatan 24/7 nonstop</li>
                        <li><i class="bi bi-check-circle-fill"></i> Siap skalabilitas nasional</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="impact-chart" data-aos="zoom-in">
                        <svg viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#e63980;stop-opacity:0.5" />
                                    <stop offset="100%" style="stop-color:#e63980;stop-opacity:0.1" />
                                </linearGradient>
                            </defs>
                            <!-- Month labels -->
                            <text x="20" y="190" font-size="12" fill="#999">Jan</text>
                            <text x="60" y="190" font-size="12" fill="#999">Feb</text>
                            <text x="100" y="190" font-size="12" fill="#999">Mar</text>
                            <text x="140" y="190" font-size="12" fill="#999">Apr</text>
                            <text x="180" y="190" font-size="12" fill="#999">May</text>
                            <text x="220" y="190" font-size="12" fill="#999">Jun</text>
                            <!-- Area Chart -->
                            <polygon points="20,170 60,140 100,100 140,80 180,40 220,20 220,180 180,180 140,180 100,180 60,180 20,180" fill="url(#chartGradient)" stroke="none"/>
                            <!-- Line -->
                            <polyline points="20,170 60,140 100,100 140,80 180,40 220,20" stroke="#e63980" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            <!-- Points -->
                            <circle cx="20" cy="170" r="4" fill="#e63980"/>
                            <circle cx="60" cy="140" r="4" fill="#e63980"/>
                            <circle cx="100" cy="100" r="4" fill="#e63980"/>
                            <circle cx="140" cy="80" r="4" fill="#e63980"/>
                            <circle cx="180" cy="40" r="4" fill="#e63980"/>
                            <circle cx="220" cy="20" r="4" fill="#e63980"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== ECOSYSTEM SECTION ==================== -->
    <section class="ecosystem-section" id="ecosystem" data-aos="fade-up">
        <div class="container">
            <div class="section-header text-center mb-5">
                <span class="section-tag">Ekosistem Platform</span>
                <h2 class="section-title">Solusi Terintegrasi untuk Semua Stakeholder</h2>
                <p class="section-subtitle">Dirancang untuk ibu, bidan, dokter spesialis obgyn, dan administrator kesehatan</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="ecosystem-card" data-aos="fade-up">
                        <div class="ecosystem-icon mother">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h4>Ibu</h4>
                        <p>Pantau kehamilan, tumbuh kembang anak, dan akses layanan kesehatan profesional kapan saja.</p>
                        <ul class="ecosystem-list">
                            <li>Buku KIA Digital</li>
                            <li>Konsultasi Langsung dan Online</li>
                            <li>Jadwal Imunisasi dan Kontrol</li>
                            <li>Artikel Edukasi</li>
                            <li>Kalkulator Gizi</li>
                            <li>Status Kehamilan</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="ecosystem-card" data-aos="fade-up">
                        <div class="ecosystem-icon midwife">
                            <i class="bi bi-person-hearts"></i>
                        </div>
                        <h4>Bidan</h4>
                        <p>Kelola data pasien terpusat, catat perkembangan, dan kolaborasi dengan dokter spesialis dengan mudah.</p>
                        <ul class="ecosystem-list">
                            <li>Dashboard Pasien Terpusat</li>
                            <li>Akses Buku KIA Digital</li>
                            <li>Koordinasi Perawatan</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="ecosystem-card" data-aos="fade-up">
                        <div class="ecosystem-icon doctor">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <h4>Dokter Spesialis Obgyn</h4>
                        <p>Lihat riwayat pasien lengkap, berikan konsultasi profesional, dan monitor perkembangan bersama.</p>
                        <ul class="ecosystem-list">
                            <li>Manajemen Pasien Komprehensif</li>
                            <li>Tools Konsultasi Online</li>
                            <li>Analitik Data Klinis</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="ecosystem-card" data-aos="fade-up">
                        <div class="ecosystem-icon admin">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <h4>Administrator Kesehatan</h4>
                        <p>Pantau hasil kesehatan wilayah, analisis dampak program, dan laporan statistik kesehatan.</p>
                        <ul class="ecosystem-list">
                            <li>Visualisasi Statistik Real-time</li>
                            <li>Sistem Manajemen Pengguna</li>
                            <li>Manajemen Artikel Edukasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FINAL CTA SECTION ==================== -->
    <section class="cta-final-section" id="cta">
        <div class="container">
            <div class="cta-content" data-aos="zoom-in">
                <h2 class="cta-title">Siap Transformasi Kesehatan Ibu & Anak?</h2>
                <p class="cta-subtitle">Bergabung dengan ribuan keluarga Indonesia yang meningkatkan kesehatan mereka bersama MomSpire</p>
                
                <div class="cta-buttons">
                    <button class="btn btn-primary-custom btn-lg" data-bs-toggle="modal" data-bs-target="#authModal">
                        <i class="bi bi-rocket-takeoff"></i> Daftar Sekarang
                    </button>
                </div>

                <div class="cta-meta">
                    <div class="meta-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Aman & Terpercaya</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-headset"></i>
                        <span>Support 24/7</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-diagram-3"></i>
                        <span>Integrasi Mudah</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ==================== TEAM SECTION ==================== -->
    <section class="team-section" id="tim" data-aos="fade-up">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag">Tim Kami</span>
                <h2 class="section-title">Tim Pengembang MomSpire</h2>
                <p class="section-subtitle">SCRUM Team yang berkomitmen untuk kesehatan ibu dan anak</p>
            </div>
            <div class="team-carousel-wrapper mt-5">
                <div id="teamCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="team-card">
                                <div class="team-photo">
                                    <img src="{{ asset('foto/dhamar.jpg') }}" alt="Team Member 1">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="team-card">
                                <div class="team-photo">
                                    <img src="{{ asset('foto/Ajeng.jpg') }}" alt="Team Member 2">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="team-card">
                                <div class="team-photo">
                                    <img src="{{ asset('foto/ndra.jpg') }}" alt="Team Member 3">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="team-card">
                                <div class="team-photo">
                                    <img src="{{ asset('foto/denilla.jpg') }}" alt="Team Member 4">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="team-card">
                                <div class="team-photo">
                                    <img src="{{ asset('foto/mifta.jpg') }}" alt="Team Member 5">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#teamCarousel" data-bs-slide="prev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#teamCarousel" data-bs-slide="next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="3"></button>
                        <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="4"></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                            <img src="{{ asset('foto/logo.jpg') }}" alt="MomSpire Logo" class="brand-logo">
                            <span>MomSpire</span>
                        </div>
                    <p class="footer-desc">Platform kesehatan ibu dan anak terpercaya untuk konsultasi, edukasi, dan pemantauan tumbuh kembang.</p>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-title">Layanan</h6>
                    <ul class="footer-links">
                        <li><a href="#">Konsultasi</a></li>
                        <li><a href="#">Imunisasi</a></li>
                        <li><a href="#">Posyandu</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-title">Informasi</h6>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Dokter Kami</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-title">Bantuan</h6>
                    <ul class="footer-links">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Privasi</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-title">Sosial Media</h6>
                    <ul class="footer-links">
                        <li><a href="#"><i class="bi bi-instagram"></i> Instagram</a></li>
                        <li><a href="#"><i class="bi bi-youtube"></i> YouTube</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 MomSpire. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- ==================== AUTH MODAL ==================== -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="auth-header">
                    <div class="auth-logo">
                        <img src="{{ asset('foto/logo.jpg') }}" alt="MomSpire Logo" class="brand-logo">
                    </div>
                    <h4>Selamat Datang</h4>
                    <p>Masuk ke akun Anda</p>
                </div>
                <div class="auth-body">
                    <ul class="nav nav-pills auth-tabs" id="authTabs" role="tablist" style="display:flex;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button">Masuk</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button">Daftar</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-4" id="authTabsContent">
                        <!-- Login -->
                        <div class="tab-pane fade show active" id="login" role="tabpanel">
                            <form id="loginForm" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <div class="input-icon">
                                        <i class="bi bi-envelope"></i>
                                        <input type="email" class="form-control" name="email" id="loginEmail" placeholder="nama@email.com" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <div class="input-icon">
                                        <i class="bi bi-lock"></i>
                                        <input type="password" class="form-control" name="password" id="loginPassword" placeholder="Masukkan password" required>
                                    </div>
                                </div>
                                <div class="form-options">
                                    <label class="checkbox-wrapper">
                                        <input type="checkbox" name="remember" id="rememberMe">
                                        <span class="checkmark"></span>
                                        <span>Ingat saya</span>
                                    </label>
                                    <a href="#" class="forgot-link">Lupa password?</a>
                                </div>
                                <button type="submit" class="btn btn-auth w-100">Masuk</button>
                            </form>
                            <div class="auth-divider">
                                <span>atau</span>
                            </div>
                            <div class="social-buttons">
                                <button class="btn btn-social" type="button"><i class="bi bi-google"></i></button>
                                <button class="btn btn-social" type="button"><i class="bi bi-facebook"></i></button>
                                <button class="btn btn-social" type="button"><i class="bi bi-apple"></i></button>
                            </div>
                        </div>
                        <!-- Forgot Password -->
                        <div class="tab-pane fade" id="forgot" role="tabpanel">
                            <div class="forgot-header text-center mb-3">
                                <h5 class="mb-2">Reset Password</h5>
                                <p class="text-muted" style="font-size:0.9rem">Masukkan email Anda untuk menerima tautan reset password</p>
                            </div>
                            <form id="forgotForm" action="{{ route('password.email') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <div class="input-icon">
                                        <i class="bi bi-envelope"></i>
                                        <input type="email" class="form-control" name="email" id="forgotEmailModal" placeholder="nama@email.com" required>
                                    </div>
                                </div>
                                <div style="display:flex; gap:8px; margin-top:20px;">
                                    <button type="submit" class="btn btn-auth" style="flex:1">Kirim Tautan</button>
                                    <button type="button" id="forgotCancel" class="btn btn-secondary" style="flex:1">Batal</button>
                                </div>
                            </form>
                        </div>
                        <!-- Register -->
                        <div class="tab-pane fade" id="register" role="tabpanel">
                            <form id="registerForm" action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="input-icon">
                                        <i class="bi bi-person"></i>
                                        <input type="text" class="form-control" name="name" id="registerName" placeholder="Masukkan nama" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <div class="input-icon">
                                        <i class="bi bi-envelope"></i>
                                        <input type="email" class="form-control" name="email" id="registerEmail" placeholder="nama@email.com" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. Telepon</label>
                                    <div class="input-icon">
                                        <i class="bi bi-phone"></i>
                                        <input type="tel" class="form-control" name="phone_number" id="registerPhone" placeholder="08xxxxxxxxxx" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <div class="input-icon">
                                        <i class="bi bi-lock"></i>
                                        <input type="password" class="form-control" name="password" id="registerPassword" placeholder="Minimal 8 karakter" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <div class="input-icon">
                                        <i class="bi bi-lock"></i>
                                        <input type="password" class="form-control" name="password_confirmation" id="registerPasswordConfirm" placeholder="Ulangi password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="checkbox-wrapper">
                                        <input type="checkbox" name="terms" id="agreeTerms" required>
                                        <span class="checkmark"></span>
                                        <span>Setuju dengan <a href="#">Syarat & Ketentuan</a></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-auth w-100">Daftar Sekarang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-B5h6G+2SbZxi+9Hn1fJhQBr4GrY4qVhnjZcArxQFpcvKB0qPinB8S2EAbk89xFa21Y+q5l71yzpF+V8cV+4AOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>