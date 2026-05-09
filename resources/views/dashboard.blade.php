@extends('pengguna.master')

@section('title', 'Dashboard Pengguna - MomSpire')
@section('header_title', 'Dashboard Pengguna')
@section('header_subtitle', '')

@push('head')
<style>
	.role-hero { 
		background: linear-gradient(135deg, #e63980 0%, #ff8c42 100%); 
		color: #fff; 
		border-radius: 24px;
		position: relative;
		overflow: hidden;
	}
	.role-hero::before {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		width: 300px;
		height: 300px;
		background: rgba(255, 255, 255, 0.1);
		border-radius: 50%;
		transform: translate(50%, -50%);
	}
	.role-hero::after {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 200px;
		height: 200px;
		background: rgba(255, 255, 255, 0.08);
		border-radius: 50%;
		transform: translate(-30%, 50%);
	}
	.role-hero .hero-content {
		position: relative;
		z-index: 1;
	}
	.role-card { border: 0; border-radius: 20px; box-shadow: 0 18px 40px rgba(15, 23, 42, .08); }
	.feature-card {
		border: 0;
		border-radius: 20px;
		box-shadow: 0 12px 32px rgba(15, 23, 42, .06);
		transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		cursor: pointer;
		text-decoration: none;
		color: inherit;
		display: flex;
		flex-direction: column;
		height: 100%;
		padding: 32px 24px;
		background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
		border: 1px solid rgba(15, 23, 42, .05);
		position: relative;
		overflow: hidden;
	}
	.feature-card::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 4px;
		background: linear-gradient(90deg, #e63980 0%, #ff8c42 100%);
		transform: scaleX(0);
		transform-origin: left;
		transition: transform 0.3s ease;
	}
	.feature-card:hover {
		transform: translateY(-12px);
		box-shadow: 0 24px 48px rgba(15, 23, 42, .12);
		text-decoration: none;
		color: inherit;
		background: linear-gradient(135deg, #ffffff 0%, #fff5f7 100%);
	}
	.feature-card:hover::before {
		transform: scaleX(1);
	}
	.feature-icon {
		font-size: 56px;
		margin-bottom: 20px;
		transition: transform 0.3s ease;
	}
	.feature-card:hover .feature-icon {
		transform: scale(1.1);
	}
	.feature-title {
		font-size: 20px;
		font-weight: 700;
		margin-bottom: 12px;
		color: #1a1a1a;
	}
	.feature-desc {
		font-size: 14px;
		color: #6c757d;
		flex-grow: 1;
		line-height: 1.6;
	}
</style>
@endpush

@section('content')
	<div class="role-hero p-4 mb-4">
		<div class="hero-content d-flex align-items-center justify-content-between">
			<div>
				<h2 class="h3 fw-bold mb-1">Halo, {{ auth()->user()->name }}! 👋</h2>
				<p class="mb-0" style="font-size: .98rem; line-height: 1.45; max-width: 720px;">Pantau informasi kesehatan kehamilan Anda dan akses fitur-fitur penting untuk kesehatan ibu dan bayi.</p>
			</div>
			<div class="text-end d-none d-md-block">
				<a href="{{ route('pengguna.pengaturan') }}" class="btn btn-sm btn-light" style="border-radius:12px;">Pengaturan Akun</a>
			</div>
		</div>
	</div>

	<div class="row g-4 mb-4">
		<div class="col-12">
			<div class="card" style="border-radius:16px; box-shadow: 0 12px 40px rgba(15,23,42,.06);">
				<div class="card-body d-flex flex-column flex-md-row align-items-start gap-3">
					<div class="me-3">
						<div class="small text-muted">Perkiraan Hari Lahir (HPL)</div>
						<div class="fw-bold h5">{{ auth()->user()->hpl ?? 'Belum diisi' }}</div>
					</div>
					<div class="me-3">
						<div class="small text-muted">Status Risiko</div>
						<div class="fw-bold text-warning">{{ auth()->user()->resiko ?? 'Normal' }}</div>
					</div>
					<div class="me-3">
						<div class="small text-muted">GPA</div>
						<div class="fw-bold">{{ auth()->user()->gpa ?? '0 / 0 / 0' }}</div>
					</div>
					<div class="ms-auto text-end">
						<a href="{{ route('pengguna.status_kehamilan') }}" class="btn btn-outline-primary">Lihat Detail Status</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-lg-3 col-md-6">
			<a href="{{ route('pengguna.kalkulator') }}" class="card feature-card">
				<div class="feature-icon" style="color: #e63980;">
					<i class="bi bi-calculator-fill"></i>
				</div>
				<div class="feature-title">Kalkulator Kehamilan</div>
				<div class="feature-desc">Hitung usia kehamilan dan estimasi tanggal persalinan Anda dengan akurat.</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="{{ route('pengguna.status_kehamilan') }}" class="card feature-card">
				<div class="feature-icon" style="color: #ff6b9d;">
					<i class="bi bi-heart-pulse-fill"></i>
				</div>
				<div class="feature-title">Status Kehamilan</div>
				<div class="feature-desc">Pantau ringkasan lengkap status kesehatan dan catatan kehamilan Anda.</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="{{ route('pengguna.jadwal') }}" class="card feature-card">
				<div class="feature-icon" style="color: #ff8c42;">
					<i class="bi bi-calendar2-check-fill"></i>
				</div>
				<div class="feature-title">Jadwal Kontrol</div>
				<div class="feature-desc">Lihat jadwal pemeriksaan rutin dan kelola janji temu dengan tenaga kesehatan.</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="{{ route('pengguna.buku_kia') }}" class="card feature-card">
				<div class="feature-icon" style="color: #ffc107;">
					<i class="bi bi-book-fill"></i>
				</div>
				<div class="feature-title">Buku KIA</div>
				<div class="feature-desc">Akses dan download dokumen Buku KIA untuk panduan kesehatan ibu dan anak.</div>
			</a>
		</div>
	</div>
@endsection