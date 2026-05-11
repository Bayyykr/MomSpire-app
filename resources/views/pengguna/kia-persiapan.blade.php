@extends('pengguna.master')

@section('title', 'Persiapan Melahirkan - MomSpire')
@section('header_title', 'Persiapan Melahirkan')
@section('header_subtitle', 'Beri tanda centang pada hal-hal persiapan melahirkan yang sudah Ibu lakukan.')

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

        <form action="{{ route('pengguna.persiapan.save') }}" method="POST">
            @csrf
            
            <!-- Card Wrapper -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-gradient-primary rounded-circle p-3 text-white">
                                <i class="bi bi-journal-check fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-gradient">Persiapan Melahirkan (Diisi oleh Ibu)</h5>
                                <p class="text-muted small mb-0">Lengkapi check-list persiapan melahirkan mulai dari Trimester kedua.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-4 py-2 shadow-sm fw-semibold">
                            <i class="bi bi-save2-fill me-1"></i> Simpan Semua Perubahan
                        </button>
                    </div>
                </div>

                <div class="card-body p-4 bg-white border-top">
                    <!-- Grid System for Columns -->
                    <div class="row g-4">
                        
                        <!-- Column 1 (Left Column) -->
                        <div class="col-12 col-lg-6 border-end-lg">
                            <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-arrow-left-right me-2"></i>Persiapan Medis & Administratif</h6>
                            
                            <!-- Item 1 (HPL) -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="tanya_tanggal_perkiraan" id="tanya_tanggal" value="1" {{ $persiapan && $persiapan->tanya_tanggal_perkiraan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <div class="w-100">
                                        <label class="form-check-label fw-bold text-dark small" for="tanya_tanggal">
                                            Saya sudah menanyakan kepada bidan dan dokter tanggal perkiraan proses melahirkan (HPL):
                                        </label>
                                        <div class="row g-2 mt-2">
                                            <div class="col-4">
                                                <input type="text" name="hpl_tanggal" value="{{ $persiapan->hpl_tanggal ?? '' }}" placeholder="Tanggal" class="form-control form-control-sm text-center rounded-pill">
                                            </div>
                                            <div class="col-4">
                                                <input type="text" name="hpl_bulan" value="{{ $persiapan->hpl_bulan ?? '' }}" placeholder="Bulan" class="form-control form-control-sm text-center rounded-pill">
                                            </div>
                                            <div class="col-4">
                                                <input type="text" name="hpl_tahun" value="{{ $persiapan->hpl_tahun ?? '' }}" placeholder="Tahun" class="form-control form-control-sm text-center rounded-pill">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="minta_dampingi" id="minta_dampingi" value="1" {{ $persiapan && $persiapan->minta_dampingi ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="minta_dampingi">
                                        Saya sudah meminta suami atau keluarga untuk mendampingi saat periksa kehamilan dan proses melahirkan.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 3 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="siap_tabungan" id="siap_tabungan" value="1" {{ $persiapan && $persiapan->siap_tabungan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="siap_tabungan">
                                        Saya sudah mempersiapkan tabungan atau dana cadangan untuk biaya proses melahirkan dan biaya lainnya.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 4 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="kartu_jkn" id="kartu_jkn" value="1" {{ $persiapan && $persiapan->kartu_jkn ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="kartu_jkn">
                                        Saya sudah memperoleh Kartu JKN dan mendaftarkan diri ke kantor BPJS Kesehatan setempat, atau bertanya ke petugas Puskesmas.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 5 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="tempat_melahirkan" id="tempat_melahirkan" value="1" {{ $persiapan && $persiapan->tempat_melahirkan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="tempat_melahirkan">
                                        Saya sudah meminta proses melahirkan dilakukan oleh bidan/dokter di Puskesmas, Rumah Sakit atau Klinik Bersalin.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2 (Right Column) -->
                        <div class="col-12 col-lg-6">
                            <h6 class="fw-bold text-danger mb-3 pb-2 border-bottom"><i class="bi bi-shield-check me-2"></i>Keluarga, Lingkungan & KB</h6>
                            
                            <!-- Item 6 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="siap_ktp_kk" id="siap_ktp_kk" value="1" {{ $persiapan && $persiapan->siap_ktp_kk ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="siap_ktp_kk">
                                        Saya sudah menyiapkan KTP, Kartu Keluarga, dan keperluan lainnya untuk ibu dan bayi yang akan dilahirkan.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 7 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="siap_pendonor" id="siap_pendonor" value="1" {{ $persiapan && $persiapan->siap_pendonor ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="siap_pendonor">
                                        Saya sudah menyiapkan lebih dari 1 orang yang memiliki golongan darah sama dan bersedia menjadi pendonor jika diperlukan.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 8 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="siap_kendaraan" id="siap_kendaraan" value="1" {{ $persiapan && $persiapan->siap_kendaraan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="siap_kendaraan">
                                        Suami, keluarga dan masyarakat sudah menyiapkan kendaraan jika sewaktu-waktu diperlukan.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 9 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="sepakat_stiker_p4k" id="sepakat_stiker_p4k" value="1" {{ $persiapan && $persiapan->sepakat_stiker_p4k ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="sepakat_stiker_p4k">
                                        Saya dan keluarga sudah menyepakati amanat proses melahirkan dalam stiker P4K dan sudah ditempelkan di depan rumah.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 10 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="rencana_kb" id="rencana_kb" value="1" {{ $persiapan && $persiapan->rencana_kb ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <div class="w-100">
                                        <label class="form-check-label fw-bold text-dark small" for="rencana_kb">
                                            Saya sudah merencanakan ikut Keluarga Berencana (KB) setelah bersalin.
                                        </label>
                                        <div class="mt-2">
                                            <input type="text" name="metode_kb" value="{{ $persiapan->metode_kb ?? '' }}" placeholder="Metode KB yang saya pilih (misal: Implant, Suntik, IUD)" class="form-control form-control-sm rounded-pill">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
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
