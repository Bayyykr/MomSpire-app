@extends('bidan.master')

@section('title', 'Dashboard Bidan - MomSpire')

@section('content')
	@php
		$penggunaCount = $penggunaCount ?? 0;
		$bidanCount = $bidanCount ?? 0;
		$dokterCount = $dokterCount ?? 0;
	@endphp

	<div class="role-hero p-3 p-sm-4 p-lg-5 mb-4">
		<div class="row align-items-center g-3 g-lg-4">
			<div class="col-12 col-lg-8">
				<span class="badge text-bg-light text-success mb-3">Dashboard Bidan</span>
				<h1 class="display-6 fw-bold mb-2 mb-lg-3" style="font-size: clamp(1.5rem, 5vw, 2.5rem);">Selamat datang, Bidan.</h1>
				<p class="lead mb-0" style="font-size: clamp(0.95rem, 3vw, 1.1rem);">Pantau pengguna terdaftar, koordinasi dengan tim bidan, dan siapkan eskalasi ke dokter bila diperlukan.</p>
			</div>
			<div class="col-12 col-lg-4 text-lg-end">
				<div class="bg-white text-success rounded-4 p-3 p-lg-4 d-inline-block shadow-sm">
					<div class="fs-1 fw-bold" style="font-size: clamp(2rem, 6vw, 2.5rem);">{{ $penggunaCount }}</div>
					<div class="small text-muted">pengguna terdaftar yang bisa dipantau</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 g-lg-4 mb-4">
		<div class="col-12 col-sm-6 col-md-4">
			<div class="card role-card h-100">
				<div class="card-body p-3 p-lg-4">
					<div class="text-success mb-2"><i class="bi bi-heart-pulse-fill fs-3"></i></div>
					<h5 class="fw-bold">Total Pengguna</h5>
					<p class="display-6 fw-bold mb-1">{{ $penggunaCount }}</p>
					<p class="text-muted mb-0" style="font-size: 0.9rem;">Akun pengguna yang terdaftar di sistem.</p>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-6 col-md-4">
			<div class="card role-card h-100">
				<div class="card-body p-3 p-lg-4">
					<div class="text-primary mb-2"><i class="bi bi-chat-dots-fill fs-3"></i></div>
					<h5 class="fw-bold">Total Bidan</h5>
					<p class="display-6 fw-bold mb-1">{{ $bidanCount }}</p>
					<p class="text-muted mb-0" style="font-size: 0.9rem;">Jumlah bidan aktif yang bisa bekerja sama.</p>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-6 col-md-4">
			<div class="card role-card h-100">
				<div class="card-body p-3 p-lg-4">
					<div class="text-warning mb-2"><i class="bi bi-calendar2-check-fill fs-3"></i></div>
					<h5 class="fw-bold">Total Dokter</h5>
					<p class="display-6 fw-bold mb-1">{{ $dokterCount }}</p>
					<p class="text-muted mb-0" style="font-size: 0.9rem;">Dokter yang tersedia untuk rujukan kasus lebih lanjut.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 g-lg-4">
		<div class="col-12 col-lg-7">
			<div class="card role-card h-100">
				<div class="card-body p-3 p-sm-4 p-lg-5">
					<h5 class="fw-bold mb-3">Pengguna terbaru</h5>
					<div class="list-group list-group-flush">
						@forelse ($recentPengguna as $user)
							<div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
								<div class="flex-grow-1">
									<div class="fw-semibold" style="font-size: 0.95rem;">{{ $user->name }}</div>
									<div class="text-muted small">{{ $user->email }}</div>
								</div>
								<span class="badge text-bg-light text-nowrap">{{ $user->created_at?->diffForHumans() }}</span>
							</div>
						@empty
							<div class="text-muted">Belum ada pengguna baru.</div>
						@endforelse
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-5">
			<div class="card role-card h-100">
				<div class="card-body p-3 p-sm-4 p-lg-5">
					<h5 class="fw-bold mb-3">Fokus hari ini</h5>
					<div class="list-group list-group-flush">
						<div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-start flex-wrap gap-2">
							<div class="flex-grow-1">
								<div class="fw-semibold" style="font-size: 0.95rem;">Review data pengguna</div>
								<div class="text-muted small">Cek akun pengguna terbaru dan respons mereka.</div>
							</div>
							<span class="badge text-bg-success rounded-pill text-nowrap">Prioritas</span>
						</div>
						<div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-start flex-wrap gap-2">
							<div class="flex-grow-1">
								<div class="fw-semibold" style="font-size: 0.95rem;">Koordinasi dengan dokter</div>
								<div class="text-muted small">Siapkan rujukan untuk kasus yang perlu evaluasi lanjutan.</div>
							</div>
							<span class="badge text-bg-primary rounded-pill text-nowrap">Proses</span>
						</div>
						<div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-start flex-wrap gap-2">
							<div class="flex-grow-1">
								<div class="fw-semibold" style="font-size: 0.95rem;">Perbarui edukasi</div>
								<div class="text-muted small">Tambahkan catatan layanan dan panduan dasar.</div>
							</div>
							<span class="badge text-bg-warning rounded-pill text-nowrap">Rutin</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card role-card mt-3 mt-lg-4">
		<div class="card-body p-3 p-sm-4 p-lg-5">
			<h5 class="fw-bold mb-3">Catatan operasional</h5>
			<p class="text-muted mb-0">Dashboard ini sudah tersambung ke role login bidan. Untuk tahap berikutnya, data konsultasi bisa dipindahkan dari localStorage ke database Laravel tanpa mengubah alur login yang sudah ada.</p>
		</div>
	</div>
@endsection
