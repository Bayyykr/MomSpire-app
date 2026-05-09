@extends('pengguna.master')

@section('title', 'Artikel Edukasi - MomSpire')
@section('header_title', 'Artikel Edukasi')
@section('header_subtitle', 'Baca panduan dan informasi kesehatan ibu hamil.')

@push('head')
<style>
    .hero-modern { background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%); color: #fff; border-radius: 20px; padding: 40px; margin-bottom: 32px; }
    .hero-modern h1 { font-size: 32px; font-weight: 800; margin-bottom: 8px; }
    .hero-modern p { font-size: 16px; opacity: 0.95; }
    .content-card { background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 10px 40px rgba(15,23,42,.08); }
    .article-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 24px; }
    .article-item { background: #fff; border-radius: 14px; padding: 20px; box-shadow: 0 4px 16px rgba(15,23,42,.06); border: 2px solid #f0f0f0; transition: all 0.3s; }
    .article-item:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(109, 40, 217, 0.15); border-color: #6d28d9; }
</style>
@endpush

@section('content')
    <div class="hero-modern">
        <h1>📚 Artikel Edukasi</h1>
        <p>Baca panduan lengkap dan informasi kesehatan ibu hamil dari sumber terpercaya</p>
    </div>

    <div class="content-card">
        <h5 class="fw-bold mb-2">Panduan & Informasi Kesehatan</h5>
        <p class="text-muted mb-4">Katalog artikel edukasi kesehatan ibu hamil dan panduan terkini. Semua artikel telah dikurasi oleh tenaga kesehatan profesional dan disesuaikan dengan kebutuhan Anda.</p>
        
        <div class="article-grid" id="articlesContainer">
            <!-- Article cards akan ditampilkan di sini -->
            <div class="text-center col-12 py-5">
                <p class="text-muted">Artikel-artikel edukatif akan hadir segera. Pantau halaman ini untuk update terbaru.</p>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('pengguna.dashboard') }}" class="btn btn-outline-secondary">← Kembali ke Dashboard</a>
        </div>
    </div>
@endsection
