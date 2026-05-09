@extends('pengguna.master')

@section('title', 'Dashboard Pengguna - MomSpire')
@section('header_title', 'Dashboard Pengguna')
@section('header_subtitle', '')

@push('head')
<style>
	:root {
		--pengguna-primary: #e63980;
		--pengguna-primary-dark: #c41e5c;
		--pengguna-secondary: #00b894;
		--pengguna-accent: #00d4aa;
		--pengguna-purple: #6f42c1;
		--pengguna-blue-soft: #60a5fa;
		--pengguna-ink: #1e293b;
		--pengguna-muted: #64748b;
		--pengguna-text-light: #94a3b8;
		--gradient-primary: linear-gradient(135deg, #e63980 0%, #ff6b9d 100%);
		--gradient-secondary: linear-gradient(135deg, #00b894 0%, #00d4aa 100%);
		--gradient-accent: linear-gradient(135deg, #6f42c1 0%, #a855f7 100%);
		--shadow-md: 0 4px 15px rgba(0,0,0,0.08);
		--shadow-lg: 0 20px 40px rgba(0,0,0,0.1);
		--shadow-xl: 0 25px 50px rgba(0,0,0,0.12);
	}

	/* Bootstrap custom utilities */
	.btn-outline-pink {
		border-color: var(--pengguna-primary);
		color: var(--pengguna-primary);
	}
	.btn-outline-pink:hover {
		background-color: var(--pengguna-primary);
		border-color: var(--pengguna-primary);
		color: white;
	}

	.text-pink-primary { color: var(--pengguna-primary); }
	.bg-pink-light { background-color: rgba(230, 57, 128, 0.1); }

	.border-bottom-light {
		border-bottom: 1px solid #e2e8f0 !important;
	}

	.w-sm-auto {
		width: auto !important;
	}

	.tiny { font-size: 0.7rem; }

	/* Responsive schedule timeline */
	.schedule-timeline-responsive {
		display: flex;
		gap: 0.75rem;
		padding: 0.5rem 0;
		-webkit-overflow-scrolling: touch;
	}

	.schedule-day-btn {
		border: 2px solid #e2e8f0;
		border-radius: 14px;
		padding: 12px 8px;
		background: white;
		transition: all 0.2s ease;
		color: var(--pengguna-ink);
	}

	.schedule-day-btn:hover {
		border-color: #94a3b8;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
		transform: translateY(-2px);
	}

	.schedule-day-btn.today {
		border-color: var(--pengguna-primary);
		border-width: 3px;
		box-shadow: 0 0 0 2px rgba(230, 57, 128, 0.15);
	}

	.schedule-day-btn.active {
		border-color: var(--pengguna-primary);
		background-color: var(--pengguna-primary);
		color: white;
	}

	.schedule-day-btn.active .text-muted {
		color: rgba(255, 255, 255, 0.7) !important;
	}

	/* Hero section responsive improvements */
	.pengguna-dashboard-shell {
		position: relative;
		isolation: isolate;
		background: linear-gradient(180deg, #ffffff 0%, #f0f4f8 100%);
		min-height: 100vh;
	}

	.pengguna-dashboard-shell::before {
		content: '';
		position: fixed;
		inset: 0;
		background:
			radial-gradient(circle at 12% 8%, rgba(230, 57, 128, 0.15), transparent 28%),
			radial-gradient(circle at 92% 14%, rgba(107, 66, 193, 0.12), transparent 26%),
			radial-gradient(circle at 20% 80%, rgba(0, 184, 148, 0.08), transparent 32%);
		z-index: -2;
		pointer-events: none;
	}

	.pengguna-dashboard-shell::after {
		content: '';
		position: fixed;
		inset: 0;
		background-image: linear-gradient(rgba(15, 23, 42, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, 0.015) 1px, transparent 1px);
		background-size: 42px 42px;
		opacity: .3;
		pointer-events: none;
		z-index: -1;
	}

	.dashboard-card-modern {
		border: 1px solid #e2e8f0;
		border-radius: 20px;
		box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
		transition: box-shadow .25s ease, border-color .25s ease;
		overflow: hidden;
		background: #fff;
	}

	.dashboard-card-modern:hover {
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
		border-color: #cbd5e1;
	}

	.hero-shell {
		position: relative;
		background: linear-gradient(135deg, #f8fafc 0%, #fdf2f8 100%);
		color: var(--pengguna-ink);
		overflow: hidden;
	}

	.hero-shell::after {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(180deg, rgba(230,57,128,.04), transparent 62%);
		pointer-events: none;
		z-index: 0;
	}

	.hero-shell .card-body {
		position: relative;
		z-index: 1;
	}

	.hero-chip {
		display: inline-flex;
		align-items: center;
		gap: .45rem;
		padding: .36rem .75rem;
		border-radius: 999px;
		background: rgba(230, 57, 128, 0.12);
		color: var(--pengguna-primary-dark);
		font-size: .75rem;
		font-weight: 700;
		letter-spacing: .05em;
		text-transform: uppercase;
		border: none;
	}

	.hero-stat {
		padding: .78rem .82rem;
		border-radius: 14px;
		background: rgba(255, 255, 255, 0.92);
		border: 1px solid rgba(148, 163, 184, 0.25);
		transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
	}

	.hero-stat:hover {
		transform: translateY(-3px);
		border-color: rgba(230, 57, 128, 0.35);
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
	}

	.hero-figure {
		position: relative;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 1rem;
		min-height: 180px;
		border-radius: 16px;
		background: linear-gradient(180deg, rgba(255,255,255,.8), rgba(255,255,255,.55));
		border: 1px solid rgba(148, 163, 184, 0.22);
	}

	.hero-stats-row {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
		gap: .75rem;
	}

	@media (max-width: 576px) {
		.hero-stats-row {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	.hero-hearts-container {
		position: relative;
		width: 100%;
		height: 180px;
	}

	.floating-heart {
		position: absolute;
		cursor: pointer;
		line-height: 1;
	}

	.floating-heart.h1 { top: 10px; left: 14px; font-size: 42px; color: #ffc0cb; opacity: 0.9; }
	.floating-heart.h2 { top: 18px; left: 116px; font-size: 16px; color: #ffd6e7; opacity: 0.82; }
	.floating-heart.h3 { top: 44px; right: 30px; font-size: 28px; color: #ffb3d9; opacity: 0.84; }
	.floating-heart.h4 { top: 76px; left: 58px; font-size: 22px; color: #ffd6e7; opacity: 0.8; }
	.floating-heart.h5 { top: 92px; right: 82px; font-size: 36px; color: #ffc0cb; opacity: 0.88; }
	.floating-heart.h6 { top: 118px; left: 10px; font-size: 14px; color: #ffd6e7; opacity: 0.76; }
	.floating-heart.h7 { top: 134px; left: 102px; font-size: 30px; color: #ffb3d9; opacity: 0.86; }
	.floating-heart.h8 { top: 152px; right: 14px; font-size: 18px; color: #ffd6e7; opacity: 0.78; }
	.floating-heart.h9 { top: 172px; left: 46px; font-size: 24px; color: #ffc0cb; opacity: 0.82; }
	.floating-heart.h10 { top: 178px; right: 62px; font-size: 40px; color: #ffb3d9; opacity: 0.88; }
	.floating-heart.h11 { top: 38px; left: 182px; font-size: 20px; color: #ffd6e7; opacity: 0.8; }
	.floating-heart.h12 { top: 116px; right: 148px; font-size: 26px; color: #ffc0cb; opacity: 0.84; }

	/* Centered mom-child SVG wrapper */
	.mom-hug-wrap {
		position: absolute;
		inset: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 2;
		pointer-events: none;
	}

	.mom-hug-wrap::before {
		content: "";
		position: absolute;
		inset: 0;
		z-index: 1;
		pointer-events: none;
		background: radial-gradient(circle at center, rgba(255,192,203,0.36) 0%, rgba(255,192,203,0.18) 28%, rgba(255,192,203,0.08) 42%, transparent 60%);
		filter: blur(18px);
		opacity: 0.95;
	}

	.mom-hug-svg {
		width: 110px;
		height: 110px;
		transform-origin: center;
		filter: drop-shadow(0 12px 28px rgba(15,23,42,0.12));
		pointer-events: none;
		animation: mom-pulse 3.6s ease-in-out infinite, mom-sway 8s ease-in-out infinite, halo-fade 6s ease-in-out infinite;
		z-index: 3;
		border-radius: 12px;
		box-shadow: 0 8px 30px rgba(255,182,193,0.08), inset 0 1px 0 rgba(255,255,255,0.12);
	}

	@keyframes halo-fade {
		0% { box-shadow: 0 8px 30px rgba(255,182,193,0.06); }
		50% { box-shadow: 0 14px 44px rgba(255,182,193,0.12); }
		100% { box-shadow: 0 8px 30px rgba(255,182,193,0.06); }
	}

	@keyframes mom-pulse {
		0% { transform: translateY(0) scale(0.985); }
		50% { transform: translateY(-6px) scale(1.02); }
		100% { transform: translateY(0) scale(0.985); }
	}

	@keyframes mom-sway {
		0% { transform: translateX(0) rotate(0deg); }
		25% { transform: translateX(-2px) rotate(-0.8deg); }
		50% { transform: translateX(0) rotate(0deg); }
		75% { transform: translateX(2px) rotate(0.8deg); }
		100% { transform: translateX(0) rotate(0deg); }
	}

	.mom-arm {
		transform-origin: 50% 20%;
		animation: mom-arm-hug 4.2s ease-in-out infinite;
	}

	@keyframes mom-arm-hug {
		0% { transform: rotate(6deg); }
		40% { transform: rotate(-6deg); }
		60% { transform: rotate(-2deg); }
		100% { transform: rotate(6deg); }
	}

	.child-rock {
		transform-origin: center 50%;
		animation: child-rock 3.8s ease-in-out infinite;
	}

	@keyframes child-rock {
		0% { transform: translateY(0) rotate(0deg); }
		50% { transform: translateY(-3px) rotate(-2.2deg); }
		100% { transform: translateY(0) rotate(0deg); }
	}

	@media (max-width: 767px) {
		.hero-hearts-container {
			height: 140px;
			max-width: 280px;
			margin: 0 auto;
		}
		.mom-hug-svg { width: 80px; height: 80px; }
		.floating-heart.h1 { font-size: 28px; }
		.floating-heart.h5 { font-size: 24px; }
		.floating-heart.h10 { font-size: 28px; }
	}

	.action-shell {
		background: #fff;
		color: var(--pengguna-ink);
	}

	.action-shell:hover {
		border-color: rgba(148, 163, 184, 0.45);
	}

	.action-shell .card-body {
		transition: transform .2s ease;
	}

	.action-shell:hover .card-body {
		transform: translateY(-2px);
	}

	.action-shell .action-badge {
		display: inline-flex;
		align-items: center;
		gap: .4rem;
		padding: .35rem .75rem;
		border-radius: 999px;
		font-size: .77rem;
		font-weight: 600;
		letter-spacing: .02em;
		background: rgba(100, 116, 139, .12);
		color: var(--pengguna-muted);
		border: none;
	}

	.action-shell.buku .action-badge {
		background: rgba(230, 57, 128, 0.1);
		color: #be185d;
		border: none;
	}

	.action-illustration {
		width: 90px;
		height: 90px;
		border-radius: 18px;
		display: grid;
		place-items: center;
		flex: 0 0 auto;
	}

	.action-illustration.buku {
		background: #f8fafc;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
	}

	@media (max-width: 768px) {
		.action-shell .card-body {
			flex-direction: column !important;
		}
		.action-illustration {
			width: 80px;
			height: 80px;
		}
	}

	/* Scrollbar styling for timeline */
	.schedule-timeline-responsive::-webkit-scrollbar {
		height: 6px;
	}
	.schedule-timeline-responsive::-webkit-scrollbar-track {
		background: #f1f5f9;
		border-radius: 10px;
	}
	.schedule-timeline-responsive::-webkit-scrollbar-thumb {
		background: linear-gradient(90deg, rgba(230, 57, 128, 0.6), rgba(107, 66, 193, 0.6));
		border-radius: 10px;
	}
	.schedule-timeline-responsive::-webkit-scrollbar-thumb:hover {
		background: linear-gradient(90deg, #e63980, #6f42c1);
	}

	/* Floating heart animations */
	@keyframes float {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-15px);
		}
	}

	@keyframes float-delay-1 {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-12px);
		}
	}

	@keyframes float-delay-2 {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-18px);
		}
	}

	@keyframes float-delay-3 {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-10px);
		}
	}

	@keyframes float-delay-4 {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-14px);
		}
	}

	.heart-float-1 {
		animation: float 3s ease-in-out infinite;
	}

	.heart-float-2 {
		animation: float-delay-1 3.5s ease-in-out infinite 0.3s;
	}

	.heart-float-3 {
		animation: float-delay-2 4s ease-in-out infinite 0.6s;
	}

	.heart-float-4 {
		animation: float-delay-3 3.2s ease-in-out infinite 0.9s;
	}

	.heart-float-5 {
		animation: float-delay-4 3.8s ease-in-out infinite 1.2s;
	}

	/* heart hover interaction */
	.bi-heart-fill {
		transition: transform .25s ease, opacity .25s ease;
	}

	.bi-heart-fill:hover {
		transform: scale(1.12) translateY(-6px);
		opacity: 0.9;
	}
</style>
@endpush

@section('content')
	<div class="pengguna-dashboard-shell">
		@php
			use Carbon\Carbon;
			$hour = Carbon::now()->hour;
			if ($hour < 10) { $greet = 'Selamat pagi'; }
			elseif ($hour < 15) { $greet = 'Selamat siang'; }
			elseif ($hour < 18) { $greet = 'Selamat sore'; }
			else { $greet = 'Selamat malam'; }
			$displayName = 'Ny. ' . (auth()->user()->name ?? 'Pengguna');
		@endphp

		<!-- Hero Greeting Section -->
		<div class="card dashboard-card-modern hero-shell mb-4">
			<div class="card-body" style="padding: 24px;">
				<div class="row align-items-center g-4">
					<div class="col-12 col-lg-7 d-flex flex-column justify-content-between">
						<div>
							<h4 class="fw-bold mb-3" style="font-size: clamp(1.25rem, 5vw, 1.6rem); line-height: 1.2;">{{ $greet }}, {{ $displayName }}</h4>
							<div class="hero-stats-row">
								<div class="hero-stat">
									<small class="text-muted d-block" style="font-size: .74rem; margin-bottom: 0.3rem;">Status Kehamilan</small>
									<div class="fw-bold" style="font-size: .98rem; color: #1e293b;">{{ auth()->user()->is_hamil ? 'Sedang Hamil' : 'Tidak Hamil' }}</div>
								</div>
								<div class="hero-stat">
									<small class="text-muted d-block" style="font-size: .74rem; margin-bottom: 0.3rem;">HPL</small>
									<div class="fw-bold" style="font-size: .98rem; color: #1e293b;">{{ auth()->user()->hpl ?? '—' }}</div>
								</div>
								<div class="hero-stat">
									<small class="text-muted d-block" style="font-size: .74rem; margin-bottom: 0.3rem;">Risiko Kehamilan</small>
									<div class="fw-bold" style="font-size: .98rem; color: #1e293b;">{{ auth()->user()->resiko ?? 'Normal' }}</div>
								</div>
								<div class="hero-stat">
									<small class="text-muted d-block" style="font-size: .74rem; margin-bottom: 0.3rem;">GPA</small>
									<div class="fw-bold" style="font-size: .98rem; color: #1e293b;">{{ auth()->user()->gpa ?? '0/0/0' }}</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-5">
						<div class="hero-figure">
							<div class="hero-hearts-container">
								<div class="mom-hug-wrap" aria-hidden="true">
									<img src="{{ asset('foto/mom.jpg') }}" alt="Ibu memeluk anak" class="mom-hug-svg mom-sway" style="border-radius: 12px; object-fit: cover;" />
								</div>
								<i class="bi bi-heart-fill floating-heart h1 heart-float-1" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h2 heart-float-2" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h3 heart-float-3" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h4 heart-float-4" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h5 heart-float-5" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h6 heart-float-2" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h7 heart-float-1" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h8 heart-float-4" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h9 heart-float-3" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h10 heart-float-5" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h11 heart-float-2" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h12 heart-float-4" aria-hidden="true"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	<!-- Quick Action Cards -->
	<div class="row g-4 mb-4">
		<div class="col-12 col-lg-6">
			<a href="{{ route('pengguna.kalkulator') }}" class="card dashboard-card-modern action-shell d-block text-decoration-none" style="min-height: 160px; overflow: hidden;">
				<div class="card-body d-flex flex-column flex-sm-row gap-3 align-items-start align-sm-items-center" style="padding: 20px;">
					<div class="action-illustration buku">
						<img src="{{ asset('foto/kalkulator.jpg') }}" alt="Kalkulator Gizi" style="width:100%; height:100%; object-fit:cover; border-radius:12px;">
					</div>
					<div class="flex-grow-1">
						<div class="action-badge mb-2">KALKULATOR GIZI</div>
						<h5 class="fw-bold mb-2" style="letter-spacing: .02em;">KALKULATOR GIZI</h5>
						<p class="text-muted mb-0" style="font-size: 0.95rem;">Hitung kebutuhan dan status gizi ibu hamil atau anak dalam tampilan yang simpel, cepat, dan enak dipakai.</p>
					</div>
				</div>
			</a>
		</div>
		<div class="col-12 col-lg-6">
			<a href="{{ route('pengguna.buku_kia') }}" class="card dashboard-card-modern action-shell d-block text-decoration-none" style="min-height: 160px; overflow: hidden;">
				<div class="card-body d-flex flex-column flex-sm-row gap-3 align-items-start align-sm-items-center" style="padding: 20px;">
					<div class="action-illustration buku">
						<img src="{{ asset('foto/BUKU KIA.jpg') }}" alt="Buku KIA" style="width:100%; height:100%; object-fit:cover; border-radius:24px;">
					</div>
					<div class="flex-grow-1">
						<div class="action-badge mb-2">BUKU KIA</div>
						<h5 class="fw-bold mb-2">BUKU KIA</h5>
						<p class="text-muted mb-0" style="font-size: 0.95rem;">Buka panduan Kesehatan Ibu dan Anak untuk akses informasi yang lebih jelas, rapi, dan nyaman dibaca.</p>
					</div>
				</div>
			</a>
		</div>
	</div>

	<!-- Schedule Section -->
	<div class="row g-4">
		<div class="col-12">
			<div class="card border-0 shadow-sm" style="border-radius: 20px;">
				<div class="card-header border-bottom-light bg-white" style="border-bottom: 1px solid #e2e8f0; border-radius: 20px 20px 0 0;">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
						<h5 class="mb-0 fw-bold">JADWAL</h5>
					</div>
				</div>
				<div class="card-body p-4">
					<!-- Month Year Navigation -->
					<div class="row align-items-center justify-content-center mb-4">
						<div class="col-12 col-sm-auto d-grid gap-2 d-sm-flex">
							<button class="btn btn-sm btn-outline-pink" id="monthPrev" type="button">
								<i class="bi bi-chevron-left"></i> <span class="d-none d-sm-inline">Bulan Sebelumnya</span>
							</button>
							<div class="text-center">
								<div class="text-muted small mb-1">Bulan</div>
								<div class="h6 fw-bold mb-0" id="displayMonth">{{ \Carbon\Carbon::today()->translatedFormat('F') }}</div>
							</div>
							<div class="text-center">
								<div class="text-muted small mb-1">Tahun</div>
								<div class="h6 fw-bold mb-0" id="displayYear">{{ \Carbon\Carbon::today()->format('Y') }}</div>
							</div>
							<button class="btn btn-sm btn-outline-pink" id="monthNext" type="button">
								<span class="d-none d-sm-inline">Bulan Berikutnya</span> <i class="bi bi-chevron-right"></i>
							</button>
						</div>
					</div>

					<!-- Date Navigation -->
					<div class="row align-items-center justify-content-center mb-4">
						<div class="col-12 col-sm-auto">
							<button class="btn btn-sm btn-outline-pink w-100 w-sm-auto" id="datePrev" type="button">
								<i class="bi bi-chevron-left"></i> <span class="d-none d-sm-inline">7 Hari Sebelumnya</span>
							</button>
						</div>
						<div class="col-12">
							<div class="schedule-timeline-responsive overflow-x-auto mb-3" id="scheduleTimeline">
								@php
									$days = collect();
									for ($i = 0; $i < 7; $i++) {
										$days->push(\Carbon\Carbon::today()->addDays($i));
									}
								@endphp
								@foreach($days as $d)
									<button class="btn btn-sm schedule-day-btn text-center flex-shrink-0" data-date="{{ $d->toDateString() }}" data-label="{{ $d->translatedFormat('l, d F Y') }}" style="min-width: 100px;">
										<div class="small text-muted mb-1">{{ $d->translatedFormat('D') }}</div>
										<div class="h5 fw-bold mb-1" style="color: #1e293b;">{{ $d->format('d') }}</div>
										<div class="tiny text-muted">{{ $d->translatedFormat('M') }}</div>
									</button>
								@endforeach
							</div>
						</div>
						<div class="col-12 col-sm-auto">
							<button class="btn btn-sm btn-outline-pink w-100 w-sm-auto" id="dateNext" type="button">
								<span class="d-none d-sm-inline">7 Hari Berikutnya</span> <i class="bi bi-chevron-right"></i>
							</button>
						</div>
					</div>

					<!-- Schedule Detail -->
					<div class="alert alert-light border-0 rounded-3" role="alert">
						<h6 class="fw-semibold mb-2 text-pink-primary">Detail Kegiatan</h6>
						<p id="scheduleDetail" class="text-muted mb-0">Pilih tanggal untuk melihat jadwal dan rincian kegiatan Anda.</p>
					</div>
				</div>
			</div>
		</div>
	</div>

@push('scripts')
<script>
// Initialize date navigation
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let startDate = new Date();
const todayStr = new Date().toISOString().split('T')[0];

// Month names in Indonesian
const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
const monthNamesShort = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

function updateScheduleTimeline() {
	const timeline = document.getElementById('scheduleTimeline');
	timeline.innerHTML = '';
	
	for (let i = 0; i < 7; i++) {
		const date = new Date(startDate);
		date.setDate(date.getDate() + i);
		
		const dateStr = date.toISOString().split('T')[0];
		const dayName = dayNames[date.getDay()];
		const dayNum = String(date.getDate()).padStart(2, '0');
		const monthName = monthNamesShort[date.getMonth()];
		
		const btn = document.createElement('button');
		btn.className = 'btn btn-sm schedule-day-btn text-center flex-shrink-0';
		btn.type = 'button';
		btn.setAttribute('data-date', dateStr);
		btn.setAttribute('data-label', date.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }));
		btn.style.minWidth = '100px';
		
		// Add 'today' class if it's today
		if (dateStr === todayStr) {
			btn.classList.add('today');
		}
		
		btn.innerHTML = `
			<div class="small text-muted mb-1">${dayName}</div>
			<div class="h5 fw-bold mb-1" style="color: var(--pengguna-ink);">${dayNum}</div>
			<div class="tiny text-muted">${monthName}</div>
		`;
		
		btn.addEventListener('click', function() {
			document.querySelectorAll('.schedule-day-btn').forEach(b => b.classList.remove('active'));
			btn.classList.add('active');
			const label = btn.getAttribute('data-label');
			document.getElementById('scheduleDetail').textContent = 'Menampilkan detail untuk: ' + label;
		});
		
		timeline.appendChild(btn);
	}
}

function updateMonthYearDisplay() {
	document.getElementById('displayMonth').textContent = monthNames[currentMonth];
	document.getElementById('displayYear').textContent = currentYear;
}

// Month navigation
document.getElementById('monthPrev').addEventListener('click', function() {
	currentMonth--;
	if (currentMonth < 0) {
		currentMonth = 11;
		currentYear--;
	}
	startDate = new Date(currentYear, currentMonth, 1);
	updateMonthYearDisplay();
	updateScheduleTimeline();
});

document.getElementById('monthNext').addEventListener('click', function() {
	currentMonth++;
	if (currentMonth > 11) {
		currentMonth = 0;
		currentYear++;
	}
	startDate = new Date(currentYear, currentMonth, 1);
	updateMonthYearDisplay();
	updateScheduleTimeline();
});

// Date navigation (7 days at a time)
document.getElementById('datePrev').addEventListener('click', function() {
	startDate.setDate(startDate.getDate() - 7);
	currentMonth = startDate.getMonth();
	currentYear = startDate.getFullYear();
	updateMonthYearDisplay();
	updateScheduleTimeline();
});

document.getElementById('dateNext').addEventListener('click', function() {
	startDate.setDate(startDate.getDate() + 7);
	currentMonth = startDate.getMonth();
	currentYear = startDate.getFullYear();
	updateMonthYearDisplay();
	updateScheduleTimeline();
});

// Initial setup
document.querySelectorAll('.schedule-day-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        document.querySelectorAll('.schedule-day-btn').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
		const label = btn.getAttribute('data-label') || btn.getAttribute('data-date');
		document.getElementById('scheduleDetail').textContent = 'Menampilkan detail untuk: ' + label;
    });
});

// Dragging for timeline on desktop
const scrollers = document.querySelectorAll('#scheduleTimeline');
scrollers.forEach(function(el){
    let isDown=false, startX, scrollLeft;
    el.addEventListener('mousedown', (e)=>{ isDown=true; el.classList.add('dragging'); startX=e.pageX - el.offsetLeft; scrollLeft=el.scrollLeft; });
    el.addEventListener('mouseleave', ()=>{ isDown=false; el.classList.remove('dragging'); });
    el.addEventListener('mouseup', ()=>{ isDown=false; el.classList.remove('dragging'); });
    el.addEventListener('mousemove', (e)=>{ if(!isDown) return; e.preventDefault(); const x=e.pageX - el.offsetLeft; const walk=(x-startX)*2; el.scrollLeft=scrollLeft-walk; });
});
</script>
@endpush

	</div>
@endsection
