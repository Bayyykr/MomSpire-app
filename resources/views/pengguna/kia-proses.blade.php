@extends('pengguna.master')

@section('title', 'Proses Melahirkan - MomSpire')
@section('header_title', 'Proses Melahirkan')
@section('header_subtitle', 'Pahami tanda-tanda dan hak Ibu selama proses melahirkan.')

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

        <form action="{{ route('pengguna.proses.save') }}" method="POST">
            @csrf
            
            <!-- Card Wrapper -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-gradient-primary rounded-circle p-3 text-white">
                                <i class="bi bi-heart-pulse fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-gradient">Proses Melahirkan (Diisi oleh Ibu)</h5>
                                <p class="text-muted small mb-0">Beri tanda centang pada hal-hal yang sudah Ibu ketahui/lakukan saat proses persalinan.</p>
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
                            <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-info-circle me-2"></i>Tanda-tanda & Hak Ibu</h6>
                            
                            <!-- Item 1 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="mulas_teratur" id="mulas_teratur" value="1" {{ $proses && $proses->mulas_teratur ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="mulas_teratur">
                                        Tanda-tanda akan bersalin didahului dengan mulas teratur, semakin lama semakin kuat.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="durasi_persalinan" id="durasi_persalinan" value="1" {{ $proses && $proses->durasi_persalinan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="durasi_persalinan">
                                        Pada kehamilan pertama, biasanya bayi baru lahir setelah 12 jam sejak mulas-mulas. Pada kehamilan kedua dan berikutnya, biasanya bayi lahir lebih cepat.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 3 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="hak_pendamping" id="hak_pendamping" value="1" {{ $proses && $proses->hak_pendamping ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="hak_pendamping">
                                        Ibu berhak memilih didampingi atau tidak, dan siapa pendampingnya.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 4 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="hak_posisi" id="hak_posisi" value="1" {{ $proses && $proses->hak_posisi ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="hak_posisi">
                                        Ibu berhak memilih posisi proses melahirkan yang diinginkan, diskusikan dengan petugas posisi yang aman.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2 (Right Column) -->
                        <div class="col-12 col-lg-6">
                            <h6 class="fw-bold text-danger mb-3 pb-2 border-bottom"><i class="bi bi-activity me-2"></i>Tindakan & Inisiasi Menyusu Dini</h6>
                            
                            <!-- Item 5 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="ingin_bab" id="ingin_bab" value="1" {{ $proses && $proses->ingin_bab ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="ingin_bab">
                                        Jika terasa ingin buang air besar, segera beritahu petugas.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 6 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="kurangi_sakit" id="kurangi_sakit" value="1" {{ $proses && $proses->kurangi_sakit ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="kurangi_sakit">
                                        Untuk mengurangi rasa sakit ketika bersalin, tarik nafas melalui hidung dan keluarkan lewat mulut.
                                    </label>
                                </div>
                            </div>

                            <!-- Item 7 -->
                            <div class="form-check-card p-3 mb-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="inisiasi_menyusu_dini" id="inisiasi_menyusu_dini" value="1" {{ $proses && $proses->inisiasi_menyusu_dini ? 'checked' : '' }} class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-bold text-dark small" for="inisiasi_menyusu_dini">
                                        Setelah bayi lahir, lakukan Inisiasi Menyusu Dini (IMD) dengan cara kontak kulit ke kulit antara bayi dan ibunya segera selama minimal 1 jam.
                                    </label>
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
