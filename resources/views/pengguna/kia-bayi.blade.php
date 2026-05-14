@extends('pengguna.master')

@section('title', 'Bayi Baru Lahir (0-28 Hari) - MomSpire')
@section('header_title', 'Bayi Baru Lahir (0 - 28 Hari)')
@section('header_subtitle', 'Satu bulan pertama kehidupan merupakan masa penting untuk mendukung kelangsungan hidup bayi.')

@section('content')
<div class="row g-4 animate__animated animate__fadeIn">
    <!-- Success Alert -->
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-4 mb-4 animate__animated animate__bounceIn" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Berhasil Disimpan!</h6>
                        <span class="small text-muted">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('pengguna.bayi.save') }}" method="POST">
            @csrf
            
            <!-- Card Wrapper -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-gradient-primary rounded-circle p-3 text-white">
                                <i class="bi bi-person-hearts fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-gradient">Pemeriksaan Bayi Baru Lahir</h5>
                                <p class="text-muted small mb-0">Beri tanda centang pada jadwal pemeriksaan yang telah dilalui si kecil.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-4 py-2 shadow-sm fw-semibold">
                            <i class="bi bi-save2-fill me-1"></i> Simpan Ceklist Pemeriksaan
                        </button>
                    </div>
                </div>

                <div class="card-body p-4 bg-white border-top">
                    <!-- Educational Content System in 2 Columns -->
                    <div class="row g-4 mb-5">
                        <!-- Column 1 (Left Column) -->
                        <div class="col-12 col-lg-6 border-end-lg">
                            <div class="mb-4">
                                <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom" style="color: #6d28d9 !important;"><i class="bi bi-info-circle-fill me-2"></i>YANG AKAN DIALAMI</h6>
                                <ul class="text-muted small ps-3 mb-0 space-y-2" style="line-height: 1.6;">
                                    <li class="mb-2">Bayi akan mengalami perubahan yang cepat, dari lingkungan yang terlindungi di dalam rahim ke dunia luar.</li>
                                    <li class="mb-2">Orang tua atau pengasuh berperan penting dalam mendukung pertumbuhan dan perkembangan bayi pada masa awal kehidupan.</li>
                                    <li>Pola tidur bayi sampai dengan 16 jam dalam sehari.</li>
                                </ul>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-success mb-3 pb-2 border-bottom"><i class="bi bi-check-circle-fill me-2"></i>YANG HARUS DILAKUKAN</h6>
                                <ul class="text-muted small ps-3 mb-0 space-y-2" style="line-height: 1.6;">
                                    <li class="mb-2">
                                        Pastikan bayi mendapat pemeriksaan dan pelayanan kesehatan oleh tenaga kesehatan:
                                        <ul class="mt-1 mb-1 ps-3 text-dark fw-medium">
                                            <li>0-6 jam setelah lahir</li>
                                            <li>6-48 jam setelah lahir</li>
                                            <li>3-7 hari setelah lahir</li>
                                            <li>8-28 hari setelah lahir</li>
                                        </ul>
                                    </li>
                                    <li class="mb-2">Susui bayi dengan penuh kasih sayang, dekap dengan hangat, dan jalin hubungan kasih sayang dengan menatap dan mengajaknya bicara.</li>
                                    <li class="mb-2">Jaga bayi tetap hangat dan jaga kebersihan selama merawat bayi.</li>
                                    <li>Cek kesehatan bayi dan kenali tanda bahaya pada bayi baru lahir. Jika ada tanda bahaya, segera ke fasilitas pelayanan kesehatan.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Column 2 (Right Column) -->
                        <div class="col-12 col-lg-6">
                            <div class="mb-4">
                                <h6 class="fw-bold text-info mb-3 pb-2 border-bottom" style="color: #0284c7 !important;"><i class="bi bi-shield-check me-2"></i>SKRINING & POLA TIDUR</h6>
                                <ul class="text-muted small ps-3 mb-0 space-y-2" style="line-height: 1.6;">
                                    <li class="mb-2">Pastikan bayi mendapat imunisasi hepatitis B (HB0) sebelum 24 jam, skrining Hipotiroid Kongenital (SHK) 48 - 72 jam setelah lahir, dan skrining Penyakit Jantung Bawaan (PJB) kritis 24-48 jam setelah lahir.</li>
                                    <li class="mb-2">Tetap berikan ASI saja selama 6 bulan, dan dilanjutkan hingga bayi berusia 2 tahun.</li>
                                    <li>
                                        Tips pola tidur bayi yang sehat:
                                        <ul class="mt-1 mb-1 ps-3 text-dark fw-medium">
                                            <li>Sebaiknya bayi tidur terlentang.</li>
                                            <li>Gunakan alas yang rata.</li>
                                            <li>Jauhkan benda yang dapat menutupi kepala.</li>
                                            <li>Gunakan kelambu.</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h6 class="fw-bold text-danger mb-3 pb-2 border-bottom"><i class="bi bi-exclamation-triangle-fill me-2"></i>MENGAPA HARUS DILAKUKAN</h6>
                                <ul class="text-muted small ps-3 mb-0 space-y-2" style="line-height: 1.6;">
                                    <li class="mb-2">Dua pertiga kematian balita di Indonesia terjadi di usia 1-28 hari pertama.</li>
                                    <li class="mb-2">Pemeriksaan dan pelayanan kesehatan dilakukan untuk memantau kesehatan bayi secara menyeluruh, agar dapat segera dilakukan penanganan apabila ditemukan adanya infeksi atau kondisi yang membahayakan bayi.</li>
                                    <li>Menjalin kedekatan dengan bayi penting untuk pertumbuhan dan perkembangannya yang terbaik.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Yellow Interactive Checklist Section -->
                    <div class="card rounded-4 border-0 mb-4 overflow-hidden shadow-sm" style="background-color: #fffbeb; border: 2px solid #f59e0b !important;">
                        <div class="p-4 bg-warning text-dark fw-bold">
                            <i class="bi bi-ui-checks me-2 fs-5"></i> Beri tanda ✓ (centang) jika si kecil sudah mendapat pemeriksaan dan pelayanan kesehatan oleh tenaga kesehatan di Puskesmas pada:
                        </div>
                        <div class="p-4 bg-white">
                            <div class="row g-4">
                                <!-- Option 1 -->
                                <div class="col-12 col-md-3">
                                    <div class="form-check-card p-3 rounded-4 bg-light border-0 h-100">
                                        <div class="form-check d-flex align-items-center gap-3 mb-0">
                                            <input type="checkbox" name="jam_0_6" id="jam_0_6" value="1" {{ $bayi && $bayi->jam_0_6 ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label fw-bold text-dark small" for="jam_0_6" style="cursor: pointer;">
                                                0 - 6 jam setelah lahir.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Option 2 -->
                                <div class="col-12 col-md-3">
                                    <div class="form-check-card p-3 rounded-4 bg-light border-0 h-100">
                                        <div class="form-check d-flex align-items-center gap-3 mb-0">
                                            <input type="checkbox" name="jam_6_48" id="jam_6_48" value="1" {{ $bayi && $bayi->jam_6_48 ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label fw-bold text-dark small" for="jam_6_48" style="cursor: pointer;">
                                                6 - 48 jam setelah lahir.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Option 3 -->
                                <div class="col-12 col-md-3">
                                    <div class="form-check-card p-3 rounded-4 bg-light border-0 h-100">
                                        <div class="form-check d-flex align-items-center gap-3 mb-0">
                                            <input type="checkbox" name="hari_3_7" id="hari_3_7" value="1" {{ $bayi && $bayi->hari_3_7 ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label fw-bold text-dark small" for="hari_3_7" style="cursor: pointer;">
                                                Hari 3 - 7 setelah lahir.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Option 4 -->
                                <div class="col-12 col-md-3">
                                    <div class="form-check-card p-3 rounded-4 bg-light border-0 h-100">
                                        <div class="form-check d-flex align-items-center gap-3 mb-0">
                                            <input type="checkbox" name="hari_8_28" id="hari_8_28" value="1" {{ $bayi && $bayi->hari_8_28 ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label fw-bold text-dark small" for="hari_8_28" style="cursor: pointer;">
                                                Hari 8 - 28 setelah lahir.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="alert alert-light border rounded-3 p-3 mb-0 text-muted small">
                        <i class="bi bi-chat-quote-fill me-2 text-secondary"></i> Tanyakan kepada bidan/dokter/Perawat untuk penjelasan lebih lanjut terkait perawatan bayi baru lahir.
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Gradient styles */
    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--accent1) 0%, var(--accent2) 100%);
        border: 0;
        color: white;
    }
    .btn-gradient-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--accent1) 0%, var(--accent2) 100%);
    }

    /* Form check customizations */
    .form-check-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .form-check-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .custom-checkbox-lg {
        width: 1.5em;
        height: 1.5em;
        border-radius: 0.35em !important;
        border-color: #cbd5e1;
    }
    .custom-checkbox-lg:checked {
        background-color: var(--accent1);
        border-color: var(--accent1);
    }

    @media (min-width: 992px) {
        .border-end-lg {
            border-right: 1px solid #e2e8f0;
        }
    }
</style>
@endsection
