@extends('pengguna.master')

@section('title', 'Kesehatan Lingkungan - MomSpire')
@section('header_title', 'Kesehatan Lingkungan')
@section('header_subtitle', 'Pemantauan kebersihan dan sanitasi lingkungan rumah tangga untuk tumbuh kembang optimal si kecil.')

@section('content')
<div class="row g-4 animate__animated animate__fadeIn">
    <!-- Card Wrapper -->
    <div class="col-12">
        <form action="{{ route('pengguna.kesehatan_lingkungan.save') }}" method="POST">
            @csrf
            
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-gradient-primary rounded-circle p-3 text-white">
                                <i class="bi bi-house-heart-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-gradient">Ceklist Kesehatan Lingkungan</h5>
                                <p class="text-muted small mb-0">Diisi oleh keluarga. Beri tanda ✓ (centang) pada kotak yang sesuai dengan kondisi di rumah.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-4 py-2 shadow-sm fw-semibold">
                            <i class="bi bi-save2-fill me-1"></i> Simpan Ceklist Lingkungan
                        </button>
                    </div>
                </div>

                <div class="card-body p-4 bg-white border-top">
                    <div class="alert alert-warning border-0 rounded-4 p-4 mb-4 d-flex gap-3 align-items-start shadow-sm" style="background-color: #fffbeb; border-left: 5px solid #f59e0b !important;">
                        <i class="bi bi-info-circle-fill fs-4 text-warning flex-shrink-0"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Petunjuk Pengisian</h6>
                            <p class="mb-0 text-muted small">Baca dan pahami hal-hal di bawah ini. Jika ada yang tidak dimengerti, tanyakan pada kader atau tenaga kesehatan faskes terdekat.</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Bagian 1: Sarana Sanitasi -->
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-4 p-4 shadow-sm">
                                <h6 class="fw-bold text-gradient mb-3"><i class="bi bi-droplet-fill me-2"></i>1. Sarana Sanitasi (pilih salah satu)</h6>
                                
                                <div class="ps-3 mb-4">
                                    <p class="fw-medium text-dark small mb-2">1. Di mana ibu dan keluarga buang air besar?</p>
                                    <div class="row g-3 ps-3">
                                        <div class="col-12 col-md-6">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="bab_sembarangan" id="bab_sembarangan" value="1" {{ $lingkungan && $lingkungan->bab_sembarangan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="bab_sembarangan" style="cursor: pointer;">Sembarangan (di kebun, sungai dll)</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="bab_jamban_sendiri" id="bab_jamban_sendiri" value="1" {{ $lingkungan && $lingkungan->bab_jamban_sendiri ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="bab_jamban_sendiri" style="cursor: pointer;">Jamban milik sendiri</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ps-3 mb-4">
                                    <p class="fw-medium text-dark small mb-2">2. Bila jamban milik sendiri, bagian bawahnya/bak penampung tinja berupa apa?</p>
                                    <div class="row g-3 ps-3">
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="penampung_tangki_septik" id="penampung_tangki_septik" value="1" {{ $lingkungan && $lingkungan->penampung_tangki_septik ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="penampung_tangki_septik" style="cursor: pointer;">Tangki septik disedot setiap 3-5 tahun terakhir atau disalurkan ke sistem pengolahan</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="penampung_cubluk" id="penampung_cubluk" value="1" {{ $lingkungan && $lingkungan->penampung_cubluk ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="penampung_cubluk" style="cursor: pointer;">Cubluk/lubang tanah</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="penampung_drainase" id="penampung_drainase" value="1" {{ $lingkungan && $lingkungan->penampung_drainase ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="penampung_drainase" style="cursor: pointer;">Dibuang langsung ke drainase/kolam/sawah/sungai/danau/laut dan pantai/tanah lapang/kebun</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ps-3 mb-2">
                                    <p class="fw-medium text-dark small mb-2">3. Bagaimana bentuk kloset jambannya?</p>
                                    <div class="ps-3">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="kloset_leher_angsa" id="kloset_leher_angsa" value="1" {{ $lingkungan && $lingkungan->kloset_leher_angsa ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="kloset_leher_angsa" style="cursor: pointer;">Kloset leher angsa/lainnya yang mencegah binatang pembawa penyakit masuk</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Cuci Tangan Pakai Sabun -->
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-4 p-4 shadow-sm">
                                <h6 class="fw-bold text-gradient mb-3"><i class="bi bi-hand-thumbs-up-fill me-2"></i>2. Cuci Tangan Pakai Sabun</h6>

                                <div class="ps-3 mb-4">
                                    <p class="fw-medium text-dark small mb-2">1. Seperti apa jenis sarana cuci tangan dirumah ibu?</p>
                                    <div class="row g-3 ps-3">
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="ctps_sarana" id="ctps_sarana" value="1" {{ $lingkungan && $lingkungan->ctps_sarana ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="ctps_sarana" style="cursor: pointer;">Memiliki sarana/tempat</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="ctps_air_mengalir" id="ctps_air_mengalir" value="1" {{ $lingkungan && $lingkungan->ctps_air_mengalir ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="ctps_air_mengalir" style="cursor: pointer;">Memiliki air mengalir</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="ctps_sabun" id="ctps_sabun" value="1" {{ $lingkungan && $lingkungan->ctps_sabun ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="ctps_sabun" style="cursor: pointer;">Memiliki sabun</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ps-3 mb-4">
                                    <p class="fw-medium text-dark small mb-1">2. Apakah ibu melakukan cuci tangan pakai sabun (lihat halaman 86)?</p>
                                    <span class="text-muted small ps-3">Selalu budayakan cuci tangan pakai sabun di air mengalir untuk mencegah infeksi.</span>
                                </div>

                                <div class="ps-3 mb-2">
                                    <p class="fw-medium text-dark small mb-2">3. Apakah ibu mengetahui waktu-waktu kritis cuci tangan pakai sabun?</p>
                                    <div class="row g-3 ps-3">
                                        <div class="col-12 col-md-6">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="ctps_waktu_sebelum_makan" id="ctps_waktu_sebelum_makan" value="1" {{ $lingkungan && $lingkungan->ctps_waktu_sebelum_makan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="ctps_waktu_sebelum_makan" style="cursor: pointer;">Sebelum makan</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="ctps_waktu_sebelum_mengolah" id="ctps_waktu_sebelum_mengolah" value="1" {{ $lingkungan && $lingkungan->ctps_waktu_sebelum_mengolah ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="ctps_waktu_sebelum_mengolah" style="cursor: pointer;">Sebelum mengolah dan menghidangkan makanan</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="ctps_waktu_sebelum_menyusui" id="ctps_waktu_sebelum_menyusui" value="1" {{ $lingkungan && $lingkungan->ctps_waktu_sebelum_menyusui ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="ctps_waktu_sebelum_menyusui" style="cursor: pointer;">Sebelum menyusui anak, sebelum memberi makan bayi/balita</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="ctps_waktu_setelah_bab" id="ctps_waktu_setelah_bab" value="1" {{ $lingkungan && $lingkungan->ctps_waktu_setelah_bab ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="ctps_waktu_setelah_bab" style="cursor: pointer;">Setelah buang air besar/kecil</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 3: Pengelolaan Makanan dan Air Minum -->
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-4 p-4 shadow-sm">
                                <h6 class="fw-bold text-gradient mb-3"><i class="bi bi-cup-straw me-2"></i>3. Pengelolaan Makanan dan Air Minum</h6>

                                <div class="ps-3 mb-4">
                                    <p class="fw-medium text-dark small mb-2">1. Apa sumber air minum di rumah ibu?</p>
                                    <div class="row g-3 ps-3">
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_pipa" id="sumber_air_pipa" value="1" {{ $lingkungan && $lingkungan->sumber_air_pipa ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_pipa" style="cursor: pointer;">Pipa</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_kran" id="sumber_air_kran" value="1" {{ $lingkungan && $lingkungan->sumber_air_kran ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_kran" style="cursor: pointer;">Kran umum</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_sumur_terlindungi" id="sumber_air_sumur_terlindungi" value="1" {{ $lingkungan && $lingkungan->sumber_air_sumur_terlindungi ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_sumur_terlindungi" style="cursor: pointer;">Sumur bor/pompa/sumur gali yang terlindungi</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_mata_air_terlindungi" id="sumber_air_mata_air_terlindungi" value="1" {{ $lingkungan && $lingkungan->sumber_air_mata_air_terlindungi ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_mata_air_terlindungi" style="cursor: pointer;">Mata air terlindungi</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_sungai" id="sumber_air_sungai" value="1" {{ $lingkungan && $lingkungan->sumber_air_sungai ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_sungai" style="cursor: pointer;">Sungai/mata air tidak terlindungi</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_danau" id="sumber_air_danau" value="1" {{ $lingkungan && $lingkungan->sumber_air_danau ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_danau" style="cursor: pointer;">Danau/kolam/sumur gali tidak terlindungi</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_hujan" id="sumber_air_hujan" value="1" {{ $lingkungan && $lingkungan->sumber_air_hujan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_hujan" style="cursor: pointer;">Air hujan</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_waduk" id="sumber_air_waduk" value="1" {{ $lingkungan && $lingkungan->sumber_air_waduk ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_waduk" style="cursor: pointer;">Waduk</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_kolam" id="sumber_air_kolam" value="1" {{ $lingkungan && $lingkungan->sumber_air_kolam ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_kolam" style="cursor: pointer;">Kolam</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="sumber_air_irigasi" id="sumber_air_irigasi" value="1" {{ $lingkungan && $lingkungan->sumber_air_irigasi ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="sumber_air_irigasi" style="cursor: pointer;">Irigasi</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ps-3 mb-4">
                                    <p class="fw-medium text-dark small mb-2">2. Bagaimana ibu mengelola air minum di rumah tangga?</p>
                                    <div class="row g-3 ps-3">
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="kelola_air_rebus" id="kelola_air_rebus" value="1" {{ $lingkungan && $lingkungan->kelola_air_rebus ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="kelola_air_rebus" style="cursor: pointer;">Melalui proses pengolahan (misal: merebus)</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="kelola_air_endap_saring" id="kelola_air_endap_saring" value="1" {{ $lingkungan && $lingkungan->kelola_air_endap_saring ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="kelola_air_endap_saring" style="cursor: pointer;">Jika air keruh dilakukan pengolahan, seperti: pengendapan atau penyaringan</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="kelola_air_wadah_tertutup" id="kelola_air_wadah_tertutup" value="1" {{ $lingkungan && $lingkungan->kelola_air_wadah_tertutup ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="kelola_air_wadah_tertutup" style="cursor: pointer;">Menyimpan air minum di dalam wadah yang tertutup rapat, kuat dan diambil dengan cara yang aman (tidak tersentuh tangan atau mulut)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ps-3 mb-2">
                                    <p class="fw-medium text-dark small mb-2">3. Bagaimana ibu mengelola makanan di dalam keluarga?</p>
                                    <div class="row g-3 ps-3">
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="kelola_makanan_tertutup" id="kelola_makanan_tertutup" value="1" {{ $lingkungan && $lingkungan->kelola_makanan_tertutup ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="kelola_makanan_tertutup" style="cursor: pointer;">Makanan tertutup baik dengan penutup yang bersih</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="kelola_makanan_jauh_bahan_berbahaya" id="kelola_makanan_jauh_bahan_berbahaya" value="1" {{ $lingkungan && $lingkungan->kelola_makanan_jauh_bahan_berbahaya ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="kelola_makanan_jauh_bahan_berbahaya" style="cursor: pointer;">Makanan tidak berdekatan dengan bahan berbahaya dan beracun (deterjen, pestisida, cairan obat nyamuk, dan sejenisnya)</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="kelola_makanan_baik_benar" id="kelola_makanan_baik_benar" value="1" {{ $lingkungan && $lingkungan->kelola_makanan_baik_benar ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                                <label class="form-check-label text-dark small" for="kelola_makanan_baik_benar" style="cursor: pointer;">Mengelola makanan dengan baik dan benar yaitu menjaga kebersihan, memisah makanan mentah dan matang, memasak sampai matang, tidak membiarkan makanan matang di luar lebih dari 4 jam serta menggunakan air yang aman.</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 4: Pengelolaan Sampah -->
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-4 p-4 shadow-sm">
                                <h6 class="fw-bold text-gradient mb-3"><i class="bi bi-trash-fill me-2"></i>4. Pengelolaan Sampah</h6>
                                <p class="fw-medium text-dark small mb-3 ps-3">Bagaimana ibu mengelola sampah?</p>
                                
                                <div class="row g-3 ps-4">
                                    <div class="col-12 col-md-6">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="sampah_tidak_berserakan" id="sampah_tidak_berserakan" value="1" {{ $lingkungan && $lingkungan->sampah_tidak_berserakan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="sampah_tidak_berserakan" style="cursor: pointer;">Tidak ada sampah berserakan di lingkungan sekitar rumah</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="sampah_tempat_tertutup" id="sampah_tempat_tertutup" value="1" {{ $lingkungan && $lingkungan->sampah_tempat_tertutup ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="sampah_tempat_tertutup" style="cursor: pointer;">Ada tempat sampah yang tertutup, kuat dan mudah dibersihkan</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="sampah_dipilah" id="sampah_dipilah" value="1" {{ $lingkungan && $lingkungan->sampah_dipilah ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="sampah_dipilah" style="cursor: pointer;">Telah melakukan pemilahan sampah</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="sampah_tidak_dibakar" id="sampah_tidak_dibakar" value="1" {{ $lingkungan && $lingkungan->sampah_tidak_dibakar ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="sampah_tidak_dibakar" style="cursor: pointer;">Tidak dibakar</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="sampah_tidak_dibuang_sembarangan" id="sampah_tidak_dibuang_sembarangan" value="1" {{ $lingkungan && $lingkungan->sampah_tidak_dibuang_sembarangan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="sampah_tidak_dibuang_sembarangan" style="cursor: pointer;">Tidak dibuang ke sungai/kebun/saluran drainase/tempat terbuka</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 5: Pengelolaan Limbah Cair -->
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-4 p-4 shadow-sm">
                                <h6 class="fw-bold text-gradient mb-3"><i class="bi bi-water me-2"></i>5. Pengelolaan Limbah Cair (air bekas cuci baju, piring, mandi)</h6>
                                <p class="fw-medium text-dark small mb-3 ps-3">Bagaimana ibu mengelola limbah cair di rumah?</p>

                                <div class="row g-3 ps-4">
                                    <div class="col-12">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="limbah_tidak_menggenang" id="limbah_tidak_menggenang" value="1" {{ $lingkungan && $lingkungan->limbah_tidak_menggenang ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="limbah_tidak_menggenang" style="cursor: pointer;">Tidak terlihat genangan air di sekitar rumah</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="limbah_saluran_tertutup" id="limbah_saluran_tertutup" value="1" {{ $lingkungan && $lingkungan->limbah_saluran_tertutup ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="limbah_saluran_tertutup" style="cursor: pointer;">Ada saluran pembuangan limbah cair rumah tangga (non kakus) yang kedap dan tertutup</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" name="limbah_terhubung_resapan" id="limbah_terhubung_resapan" value="1" {{ $lingkungan && $lingkungan->limbah_terhubung_resapan ? 'checked' : '' }} class="form-check-input custom-checkbox-lg m-0 flex-shrink-0">
                                            <label class="form-check-label text-dark small" for="limbah_terhubung_resapan" style="cursor: pointer;">Terhubung dengan sumur resapan dan atau sistem pengolahan limbah</label>
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
</style>
@endsection
