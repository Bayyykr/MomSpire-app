@extends('pengguna.master')

@section('title', 'Konsultasi - MomSpire')
@section('header_title', 'Konsultasi')
@section('header_subtitle', 'Hubungi tenaga kesehatan profesional untuk pertanyaan kesehatan Anda.')

@push('head')
<style>
    .hero-modern { background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%); color: #fff; border-radius: 20px; padding: 40px; margin-bottom: 32px; }
    .hero-modern h1 { font-size: 32px; font-weight: 800; margin-bottom: 8px; }
    .hero-modern p { font-size: 16px; opacity: 0.95; }
    .content-card { background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 10px 40px rgba(15,23,42,.08); }
    .consultation-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; margin-top: 24px; }
</style>
@endpush

@section('content')
    <div class="hero-modern">
        <h1>💬 Konsultasi Kesehatan</h1>
        <p>Terhubung dengan tenaga kesehatan profesional kapan pun Anda membutuhkan</p>
    </div>

    <div class="content-card">
        <h5 class="fw-bold mb-2">Hubungi Profesional Kesehatan</h5>
        <p class="text-muted mb-4">Fitur konsultasi menghubungkan Anda dengan bidan dan dokter profesional. Ajukan pertanyaan kesehatan, dapatkan saran medis, dan berkonsultasi langsung dengan tenaga kesehatan bersertifikat.</p>
        
        <div class="alert alert-info" role="alert">
            <strong>💡 Info:</strong> Fitur konsultasi online akan segera tersedia. Anda akan dapat menghubungi tenaga kesehatan langsung melalui platform ini.
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('pengguna.dashboard') }}" class="btn btn-outline-secondary">← Kembali ke Dashboard</a>
        </div>
    </div>
@endsection
