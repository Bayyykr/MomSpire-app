@extends('pengguna.master')

@section('title', 'Kalkulator Gizi - MomSpire')
@section('header_title')
	Kalkulator Gizi
@endsection
@section('header_subtitle', '')
@section('header_action')
	<a href="{{ route('pengguna.dashboard') }}" class="btn btn-back-page">
		<i class="bi bi-arrow-left"></i>
		<span>Kembali</span>
	</a>
@endsection

@push('head')
<script>
	window.__MOMSPIRE_SIDEBAR_OPEN = false;
	window.__MOMSPIRE_HEADER_TITLE_LOCK = 'Kalkulator Gizi';
</script>
<style>
	.btn-back-page {
		background: #fff;
		border: 1px solid rgba(15, 23, 42, .12);
		border-radius: 999px;
		color: #0f172a;
		font-weight: 700;
		padding: 10px 16px;
		box-shadow: 0 10px 20px rgba(15, 23, 42, .05);
		transition: all .2s ease;
		transform: translateX(8px);
		margin-right: 6px;
	}

	.btn-back-page:hover {
		transform: translateX(8px) translateY(-1px);
		border-color: rgba(15, 23, 42, .25);
		box-shadow: 0 14px 24px rgba(15, 23, 42, .12);
		color: #0f172a;
	}

	.kalkulator-shell {
		background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
		border: 1px solid rgba(15, 23, 42, .05);
		border-radius: 28px;
		box-shadow: 0 18px 40px rgba(15, 23, 42, .06);
		padding: 28px;
	}

	.kalkulator-hero {
		background: linear-gradient(135deg, #fff1f7 0%, #fff7ed 100%);
		border: 1px solid rgba(244, 114, 182, .2);
		border-radius: 24px;
		color: #0f172a;
		padding: 28px;
		margin-bottom: 24px;
		position: relative;
		overflow: hidden;
	}

	.kalkulator-hero::after {
		content: '';
		position: absolute;
		inset: auto -120px -120px auto;
		width: 240px;
		height: 240px;
		border-radius: 50%;
		background: rgba(244, 114, 182, .12);
		pointer-events: none;
	}

	.kalkulator-hero h2 {
		font-size: 30px;
		font-weight: 800;
		margin-bottom: 10px;
		color: #be185d;
	}

	.kalkulator-hero p {
		margin-bottom: 0;
		opacity: .92;
		color: #334155;
		max-width: 58rem;
	}

	.metric-chip {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 14px;
		border-radius: 999px;
		background: #ffffff;
		border: 1px solid rgba(244, 114, 182, .24);
		backdrop-filter: blur(8px);
		font-size: 13px;
		font-weight: 700;
		color: #be185d;
		white-space: nowrap;
	}

	.kalkulator-panel {
		background: linear-gradient(180deg, #fff8fc 0%, #ffffff 100%);
		border: 1px solid rgba(244, 114, 182, .18);
		border-radius: 24px;
		padding: 24px;
		box-shadow: 0 14px 28px rgba(244, 114, 182, .12);
		height: 100%;
	}

	.kalkulator-result {
		background: linear-gradient(180deg, #fff 0%, #fff7fb 100%);
		border: 1px solid rgba(230, 57, 128, .12);
		border-radius: 24px;
		padding: 24px;
		box-shadow: 0 16px 34px rgba(15, 23, 42, .06);
		height: 100%;
	}

	.section-label {
		font-size: 12px;
		font-weight: 800;
		letter-spacing: .16em;
		text-transform: uppercase;
		color: #e63980;
		margin-bottom: 12px;
	}

	.calc-mode-group {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
	}

	.calc-mode-btn {
		border: 1px solid rgba(244, 114, 182, .24);
		background: #fff;
		color: #be185d;
		border-radius: 999px;
		padding: 12px 18px;
		font-weight: 700;
		transition: all .2s ease;
		box-shadow: 0 8px 16px rgba(244, 114, 182, .1);
	}

	.calc-mode-btn:hover {
		transform: translateY(-1px);
		border-color: rgba(244, 114, 182, .48);
		background: #fff5fb;
	}

	.calc-mode-btn.active {
		background: linear-gradient(135deg, #e11d48 0%, #f472b6 100%);
		border-color: transparent;
		color: #fff;
		box-shadow: 0 14px 26px rgba(225, 29, 72, .24);
	}

	.form-label {
		font-weight: 700;
		color: #475569;
	}

	.form-control,
	.form-select {
		border-radius: 14px;
		padding: 12px 14px;
		border-color: rgba(15, 23, 42, .12);
		box-shadow: none;
	}

	.form-control:focus,
	.form-select:focus {
		border-color: rgba(225, 29, 72, .45);
		box-shadow: 0 0 0 4px rgba(225, 29, 72, .1);
	}

	.result-placeholder {
		background: #fff;
		border: 1px dashed rgba(230, 57, 128, .25);
		border-radius: 20px;
		padding: 20px;
		color: #475569;
	}

	.result-stack {
		display: grid;
		gap: 14px;
	}

	.result-box {
		background: #fff;
		border-radius: 18px;
		padding: 16px 18px;
		border: 1px solid rgba(15, 23, 42, .07);
	}

	.result-box strong {
		color: #0f172a;
	}

	.result-value {
		font-size: 28px;
		font-weight: 800;
		line-height: 1.1;
	}

	.badge-soft {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 12px;
		border-radius: 999px;
		font-size: 13px;
		font-weight: 700;
		background: rgba(230, 57, 128, .08);
		color: #c81e68;
	}

	@media (max-width: 991px) {
		.kalkulator-shell {
			padding: 20px;
		}

		.kalkulator-hero {
			padding: 22px;
		}

		.kalkulator-hero h2 {
			font-size: 24px;
		}
	}
</style>
@endpush

@section('content')
	<div class="kalkulator-shell">
		<div class="kalkulator-hero">
			<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 position-relative" style="z-index: 1;">
				<div>
					<h2>Hitung berat badan ideal ibu atau anak</h2>
					<p>
						Pilih mode yang sesuai, masukkan tinggi badan dan berat badan, lalu lihat estimasi berat badan ideal di sisi kanan.
						Untuk ibu hamil, usia kehamilan membantu menampilkan rekomendasi yang lebih relevan.
					</p>
				</div>
				<div class="d-flex flex-wrap gap-2">
					<span class="metric-chip"><i class="bi bi-heart-pulse-fill"></i> Ibu hamil</span>
					<span class="metric-chip"><i class="bi bi-people-fill"></i> Anak</span>
					<span class="metric-chip"><i class="bi bi-rulers"></i> Berat ideal</span>
				</div>
			</div>
		</div>

		<div class="row g-4 align-items-stretch">
			<div class="col-lg-6">
				<div class="kalkulator-panel">
					<div class="calc-mode-group mb-4" role="tablist" aria-label="Mode kalkulator gizi">
						<button type="button" class="calc-mode-btn active" data-mode="ibu" id="modeIbuBtn">Hitung gizi untuk ibu</button>
						<button type="button" class="calc-mode-btn" data-mode="anak" id="modeAnakBtn">Hitung gizi untuk anak</button>
					</div>

					<form id="giziForm" novalidate>
						<div id="ibuFields">
							<div class="mb-3">
								<label class="form-label">Usia kehamilan (minggu)</label>
								<input type="number" min="1" max="42" class="form-control" id="minggu" value="20" placeholder="Contoh: 20">
							</div>
							<div class="mb-3">
								<label class="form-label">Tinggi badan (cm)</label>
								<input type="number" min="100" class="form-control" id="tb_ibu" placeholder="Contoh: 160">
							</div>
							<div class="mb-4">
								<label class="form-label">Berat badan saat ini (kg)</label>
								<input type="number" min="20" step="0.1" class="form-control" id="bb_ibu" placeholder="Contoh: 60">
							</div>
						</div>

						<div id="anakFields" style="display:none;">
							<div class="mb-3">
								<label class="form-label">Tinggi badan anak (cm)</label>
								<input type="number" min="30" class="form-control" id="tb_anak" placeholder="Contoh: 85">
							</div>
							<div class="mb-4">
								<label class="form-label">Berat badan anak (kg)</label>
								<input type="number" min="1" step="0.1" class="form-control" id="bb_anak" placeholder="Contoh: 12">
							</div>
						</div>

						<div class="d-grid">
							<button type="button" id="calculateBtn" class="btn btn-primary" style="border-radius: 14px; padding: 12px 18px; font-weight: 700; background: linear-gradient(135deg, #e63980 0%, #ff8c42 100%); border: 0; box-shadow: 0 16px 28px rgba(230, 57, 128, .22);">
								Hitung
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="kalkulator-result">
					<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
						<div>
							<div class="section-label mb-2">Hasil</div>
							<h4 class="fw-bold mb-1">Ringkasan kalkulasi</h4>
							<p class="text-muted mb-0">Hasil akan tampil di sini setelah data dimasukkan.</p>
						</div>
						<span class="badge-soft"><i class="bi bi-lightning-charge-fill"></i> Instan</span>
					</div>

					<div id="resultArea" class="result-stack">
						<div class="result-placeholder">
							<p class="mb-2 fw-semibold">Belum ada hasil.</p>
							<p class="mb-0">Masukkan data lalu klik tombol <strong>Hitung</strong> untuk melihat estimasi berat badan ideal dan ringkasan statusnya.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
<script>
	(function () {
		const headerTitle = document.querySelector('.header-title');
		if (headerTitle) {
			headerTitle.textContent = 'Kalkulator Gizi';
		}

		const modeButtons = document.querySelectorAll('.calc-mode-btn');
		const ibuFields = document.getElementById('ibuFields');
		const anakFields = document.getElementById('anakFields');
		const calculateBtn = document.getElementById('calculateBtn');
		const resultArea = document.getElementById('resultArea');
		let currentMode = 'ibu';

		function resetResult() {
			resultArea.innerHTML = `
				<div class="result-placeholder">
					<p class="mb-2 fw-semibold">Belum ada hasil.</p>
					<p class="mb-0">Masukkan data lalu klik tombol <strong>Hitung</strong> untuk melihat estimasi berat badan ideal dan ringkasan statusnya.</p>
				</div>
			`;
		}

		function setMode(mode) {
			currentMode = mode;
			modeButtons.forEach((button) => {
				button.classList.toggle('active', button.dataset.mode === mode);
			});
			ibuFields.style.display = mode === 'ibu' ? 'block' : 'none';
			anakFields.style.display = mode === 'anak' ? 'block' : 'none';
			resetResult();
		}

		function toMeters(cm) {
			return (parseFloat(cm) || 0) / 100;
		}

		function formatNumber(value) {
			return Number.isFinite(value) ? value.toFixed(1) : '0.0';
		}

		function pregnancyTargetWeight(heightCm, currentWeek) {
			const baseIdeal = Math.max(40, ((heightCm - 100) * 0.85));
			let gain = 0;

			if (currentWeek <= 12) {
				gain = 0.8 + (currentWeek * 0.1);
			} else if (currentWeek <= 28) {
				gain = 2.0 + ((currentWeek - 12) * 0.35);
			} else {
				gain = 7.6 + ((currentWeek - 28) * 0.3);
			}

			return baseIdeal + gain;
		}

		function childIdealRange(heightCm) {
			const heightMeter = toMeters(heightCm);
			const idealMin = 18.5 * (heightMeter * heightMeter);
			const idealMax = 22.0 * (heightMeter * heightMeter);
			return { idealMin, idealMax, idealMid: (idealMin + idealMax) / 2 };
		}

		function renderIbuResult(minggu, tb, bb) {
			const ideal = pregnancyTargetWeight(tb, minggu);
			const difference = bb - ideal;
			const bmi = bb / (toMeters(tb) * toMeters(tb));
			let status = 'Ideal';
			let statusClass = 'text-success';

			if (difference < -2) {
				status = 'Perlu menaikkan berat badan';
				statusClass = 'text-warning';
			} else if (difference > 2) {
				status = 'Di atas estimasi ideal';
				statusClass = 'text-danger';
			}

			resultArea.innerHTML = `
				<div class="result-box">
					<div class="text-muted small mb-1">BB ideal ibu saat ini</div>
					<div class="result-value">${formatNumber(ideal)} kg</div>
					<div class="${statusClass} fw-semibold mt-1">${status}</div>
				</div>
				<div class="result-box">
					<div class="row g-3">
						<div class="col-6">
							<div class="text-muted small">Usia kehamilan</div>
							<div class="fw-bold">${minggu} minggu</div>
						</div>
						<div class="col-6">
							<div class="text-muted small">BMI saat ini</div>
							<div class="fw-bold">${formatNumber(bmi)}</div>
						</div>
						<div class="col-6">
							<div class="text-muted small">Berat saat ini</div>
							<div class="fw-bold">${formatNumber(bb)} kg</div>
						</div>
						<div class="col-6">
							<div class="text-muted small">Selisih estimasi</div>
							<div class="fw-bold">${difference >= 0 ? '+' : ''}${formatNumber(difference)} kg</div>
						</div>
					</div>
				</div>
				<div class="result-box">
					<div class="text-muted small mb-1">Catatan</div>
					<div class="fw-semibold">Estimasi ini dipakai sebagai panduan cepat. Untuk penilaian klinis, tetap sesuaikan dengan pemeriksaan tenaga kesehatan.</div>
				</div>
			`;
		}

		function renderChildResult(tb, bb) {
			const range = childIdealRange(tb);
			const bmi = bb / (toMeters(tb) * toMeters(tb));
			let status = 'Dalam estimasi ideal';
			let statusClass = 'text-success';

			if (bb < range.idealMin) {
				status = 'Perlu peningkatan berat badan';
				statusClass = 'text-warning';
			} else if (bb > range.idealMax) {
				status = 'Di atas estimasi ideal';
				statusClass = 'text-danger';
			}

			resultArea.innerHTML = `
				<div class="result-box">
					<div class="text-muted small mb-1">BB ideal anak</div>
					<div class="result-value">${formatNumber(range.idealMid)} kg</div>
					<div class="text-muted mt-1">Rentang ideal: ${formatNumber(range.idealMin)} kg - ${formatNumber(range.idealMax)} kg</div>
				</div>
				<div class="result-box">
					<div class="row g-3">
						<div class="col-6">
							<div class="text-muted small">Tinggi badan</div>
							<div class="fw-bold">${formatNumber(tb)} cm</div>
						</div>
						<div class="col-6">
							<div class="text-muted small">Berat badan</div>
							<div class="fw-bold">${formatNumber(bb)} kg</div>
						</div>
						<div class="col-6">
							<div class="text-muted small">BMI</div>
							<div class="fw-bold">${formatNumber(bmi)}</div>
						</div>
						<div class="col-6">
							<div class="text-muted small">Status</div>
							<div class="fw-bold ${statusClass}">${status}</div>
						</div>
					</div>
				</div>
				<div class="result-box">
					<div class="text-muted small mb-1">Catatan</div>
					<div class="fw-semibold">Karena kalkulator ini tidak memakai usia anak, hasilnya memakai estimasi berbasis tinggi dan berat badan untuk panduan awal.</div>
				</div>
			`;
		}

		modeButtons.forEach((button) => {
			button.addEventListener('click', function () {
				setMode(this.dataset.mode);
			});
		});

		calculateBtn.addEventListener('click', function () {
			if (currentMode === 'ibu') {
				const minggu = parseInt(document.getElementById('minggu').value, 10) || 0;
				const tb = parseFloat(document.getElementById('tb_ibu').value) || 0;
				const bb = parseFloat(document.getElementById('bb_ibu').value) || 0;

				if (!minggu || !tb || !bb) {
					resultArea.innerHTML = `
						<div class="result-placeholder border border-danger-subtle">
							<p class="mb-1 fw-semibold text-danger">Data ibu belum lengkap.</p>
							<p class="mb-0">Isi usia kehamilan, tinggi badan, dan berat badan dulu ya.</p>
						</div>
					`;
					return;
				}

				renderIbuResult(minggu, tb, bb);
				return;
			}

			const tb = parseFloat(document.getElementById('tb_anak').value) || 0;
			const bb = parseFloat(document.getElementById('bb_anak').value) || 0;

			if (!tb || !bb) {
				resultArea.innerHTML = `
					<div class="result-placeholder border border-danger-subtle">
						<p class="mb-1 fw-semibold text-danger">Data anak belum lengkap.</p>
						<p class="mb-0">Isi tinggi badan dan berat badan dulu ya.</p>
					</div>
				`;
				return;
			}

			renderChildResult(tb, bb);
		});

		setMode('ibu');
		window.__momspireSidebarOpen = false;
		if (typeof window.__momspireSyncSidebar === 'function') {
			window.__momspireSyncSidebar();
		}
	})();
</script>
@endpush