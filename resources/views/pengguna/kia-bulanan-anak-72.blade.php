@extends('pengguna.master')

@section('title', 'Pemantauan Anak 2 - 6 Tahun - MomSpire')
@section('header_title', 'Anak Umur 2 - 6 Tahun')
@section('header_subtitle', 'Catat hasil pemantauan bulanan kondisi dan kesehatan anak secara mandiri dari bulan ke-24 hingga bulan ke-71.')

@section('content')
<div class="row g-4" x-data="{ activeTab: '{{ request('tab', session('active_tab', 'tahun2')) }}', activeMonth: {{ session('active_month', 24) }} }">
    <!-- Info Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-shield-fill-check fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">🛡️ Pemantauan Rutin Anak Usia Prasekolah (2 - 6 Tahun)</h6>
                    <p class="mb-0 small opacity-90">Pada masa emas prasekolah, pemantauan kesehatan rutin sangat penting untuk mendeteksi dini masalah pernapasan, pencernaan, demam, dan kecukupan nutrisi. Lakukan pencatatan kondisi anak setiap bulan. Segera bawa anak ke Fasilitas Kesehatan (Puskesmas/Rumah Sakit) jika mengalami salah satu tanda bahaya di bawah ini.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="col-12">
        <div class="d-flex justify-content-center gap-2 border-bottom pb-3 flex-wrap">
            <button @click="activeTab = 'tahun2'; activeMonth = 24;" 
                    :class="activeTab === 'tahun2' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar2 me-2"></i> Usia 2 Tahun
            </button>
            <button @click="activeTab = 'perkembangan36';" 
                    :class="activeTab === 'perkembangan36' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-person-check-fill me-2"></i> Perkembangan 2-3 Thn
            </button>
            <button @click="activeTab = 'tahun3'; activeMonth = 36;" 
                    :class="activeTab === 'tahun3' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar3 me-2"></i> Usia 3 Tahun
            </button>
            <button @click="activeTab = 'perkembangan48';" 
                    :class="activeTab === 'perkembangan48' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-person-check-fill me-2"></i> Perkembangan 3-4 Thn
            </button>
            <button @click="activeTab = 'tahun4'; activeMonth = 48;" 
                    :class="activeTab === 'tahun4' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar4 me-2"></i> Usia 4 Tahun
            </button>
            <button @click="activeTab = 'perkembangan60';" 
                    :class="activeTab === 'perkembangan60' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-person-check-fill me-2"></i> Perkembangan 4-5 Thn
            </button>
            <button @click="activeTab = 'tahun5'; activeMonth = 60;" 
                    :class="activeTab === 'tahun5' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar4-week me-2"></i> Usia 5 Tahun
            </button>
            <button @click="activeTab = 'perkembangan72';" 
                    :class="activeTab === 'perkembangan72' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-person-check-fill me-2"></i> Perkembangan 5-6 Thn
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-4 mb-0" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Berhasil Disimpan!</h6>
                        <span class="small text-muted">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Perkembangan 2 - 3 Tahun Tab -->
    <div class="col-12" x-show="activeTab === 'perkembangan36'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Anak Umur 2 - 3 Tahun</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>
            <div class="card-body p-4">
                @php $p36 = $perk36 ?? null; @endphp
                <form action="{{ route('pengguna.perkembangan_anak_36_bulan.save') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-4">
                            <thead class="table-warning text-dark text-center">
                                <tr>
                                    <th style="width: 60px;" class="py-3">No.</th>
                                    <th class="py-3 text-start">Penanda Perkembangan Anak</th>
                                    <th style="width: 100px;" class="py-3">Ya</th>
                                    <th style="width: 100px;" class="py-3">Tidak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold bg-light">1</td>
                                    <td class="fw-medium">Anak bisa jalan naik tangga sendiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="naik_tangga" value="1" class="form-check-input fs-4" {{ (isset($p36->naik_tangga) && $p36->naik_tangga == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="naik_tangga" value="0" class="form-check-input fs-4" {{ (isset($p36->naik_tangga) && $p36->naik_tangga == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Anak bisa bermain dan menendang bola kecil?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tendang_bola" value="1" class="form-check-input fs-4" {{ (isset($p36->tendang_bola) && $p36->tendang_bola == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tendang_bola" value="0" class="form-check-input fs-4" {{ (isset($p36->tendang_bola) && $p36->tendang_bola == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Anak bisa mencoret-coret pensil pada kertas?</td>
                                    <td class="text-center">
                                        <input type="radio" name="coret_kertas" value="1" class="form-check-input fs-4" {{ (isset($p36->coret_kertas) && $p36->coret_kertas == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="coret_kertas" value="0" class="form-check-input fs-4" {{ (isset($p36->coret_kertas) && $p36->coret_kertas == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Anak bisa bicara dengan baik, menggunakan 2 kata?</td>
                                    <td class="text-center">
                                        <input type="radio" name="bicara_2_kata" value="1" class="form-check-input fs-4" {{ (isset($p36->bicara_2_kata) && $p36->bicara_2_kata == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="bicara_2_kata" value="0" class="form-check-input fs-4" {{ (isset($p36->bicara_2_kata) && $p36->bicara_2_kata == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Anak bisa menunjuk 1 atau lebih bagian tubuhnya ketika diminta?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tunjuk_bagian_tubuh" value="1" class="form-check-input fs-4" {{ (isset($p36->tunjuk_bagian_tubuh) && $p36->tunjuk_bagian_tubuh == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tunjuk_bagian_tubuh" value="0" class="form-check-input fs-4" {{ (isset($p36->tunjuk_bagian_tubuh) && $p36->tunjuk_bagian_tubuh == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Anak bisa melihat gambar dan dapat menyebut dengan benar nama 2 benda atau lebih?</td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_nama_benda" value="1" class="form-check-input fs-4" {{ (isset($p36->sebut_nama_benda) && $p36->sebut_nama_benda == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_nama_benda" value="0" class="form-check-input fs-4" {{ (isset($p36->sebut_nama_benda) && $p36->sebut_nama_benda == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Anak bisa membantu memungut mainannya sendiri atau membantu mengangkat piring jika diminta?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pungut_mainan" value="1" class="form-check-input fs-4" {{ (isset($p36->pungut_mainan) && $p36->pungut_mainan == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pungut_mainan" value="0" class="form-check-input fs-4" {{ (isset($p36->pungut_mainan) && $p36->pungut_mainan == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Anak bisa makan nasi sendiri tanpa banyak tumpah?</td>
                                    <td class="text-center">
                                        <input type="radio" name="makan_nasi_sendiri" value="1" class="form-check-input fs-4" {{ (isset($p36->makan_nasi_sendiri) && $p36->makan_nasi_sendiri == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="makan_nasi_sendiri" value="0" class="form-check-input fs-4" {{ (isset($p36->makan_nasi_sendiri) && $p36->makan_nasi_sendiri == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">9</td>
                                    <td class="fw-medium">Anak bisa melepas pakaiannya sendiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="lepas_pakaian" value="1" class="form-check-input fs-4" {{ (isset($p36->lepas_pakaian) && $p36->lepas_pakaian == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="lepas_pakaian" value="0" class="form-check-input fs-4" {{ (isset($p36->lepas_pakaian) && $p36->lepas_pakaian == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Tumbuh Kembang 2 - 3 Tahun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Perkembangan 3 - 4 Tahun Tab -->
    <div class="col-12" x-show="activeTab === 'perkembangan48'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Anak Umur 3 - 4 Tahun</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>
            <div class="card-body p-4">
                @php $p48 = $perk48 ?? null; @endphp
                <form action="{{ route('pengguna.perkembangan_anak_48_bulan.save') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-4">
                            <thead class="table-warning text-dark text-center">
                                <tr>
                                    <th style="width: 60px;" class="py-3">No.</th>
                                    <th class="py-3 text-start">Penanda Perkembangan Anak</th>
                                    <th style="width: 100px;" class="py-3">Ya</th>
                                    <th style="width: 100px;" class="py-3">Tidak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold bg-light">1</td>
                                    <td class="fw-medium">Anak bisa berdiri 1 kaki 2 detik?</td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_1_kaki_2_detik" value="1" class="form-check-input fs-4" {{ (isset($p48->berdiri_1_kaki_2_detik) && $p48->berdiri_1_kaki_2_detik == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_1_kaki_2_detik" value="0" class="form-check-input fs-4" {{ (isset($p48->berdiri_1_kaki_2_detik) && $p48->berdiri_1_kaki_2_detik == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Anak bisa melompat kedua kaki diangkat?</td>
                                    <td class="text-center">
                                        <input type="radio" name="lompat_kedua_kaki" value="1" class="form-check-input fs-4" {{ (isset($p48->lompat_kedua_kaki) && $p48->lompat_kedua_kaki == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="lompat_kedua_kaki" value="0" class="form-check-input fs-4" {{ (isset($p48->lompat_kedua_kaki) && $p48->lompat_kedua_kaki == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Anak bisa mengayuh sepeda roda tiga?</td>
                                    <td class="text-center">
                                        <input type="radio" name="kayuh_sepeda_roda_3" value="1" class="form-check-input fs-4" {{ (isset($p48->kayuh_sepeda_roda_3) && $p48->kayuh_sepeda_roda_3 == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="kayuh_sepeda_roda_3" value="0" class="form-check-input fs-4" {{ (isset($p48->kayuh_sepeda_roda_3) && $p48->kayuh_sepeda_roda_3 == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Anak bisa menggambar garis lurus?</td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_garis_lurus" value="1" class="form-check-input fs-4" {{ (isset($p48->gambar_garis_lurus) && $p48->gambar_garis_lurus == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_garis_lurus" value="0" class="form-check-input fs-4" {{ (isset($p48->gambar_garis_lurus) && $p48->gambar_garis_lurus == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Anak bisa menumpuk 8 buah kubus?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tumpuk_8_kubus" value="1" class="form-check-input fs-4" {{ (isset($p48->tumpuk_8_kubus) && $p48->tumpuk_8_kubus == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tumpuk_8_kubus" value="0" class="form-check-input fs-4" {{ (isset($p48->tumpuk_8_kubus) && $p48->tumpuk_8_kubus == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Anak bisa mengenal 2-4 warna?</td>
                                    <td class="text-center">
                                        <input type="radio" name="kenal_2_4_warna" value="1" class="form-check-input fs-4" {{ (isset($p48->kenal_2_4_warna) && $p48->kenal_2_4_warna == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="kenal_2_4_warna" value="0" class="form-check-input fs-4" {{ (isset($p48->kenal_2_4_warna) && $p48->kenal_2_4_warna == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Anak bisa menyebut nama, umur, tempat?</td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_nama_umur_tempat" value="1" class="form-check-input fs-4" {{ (isset($p48->sebut_nama_umur_tempat) && $p48->sebut_nama_umur_tempat == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_nama_umur_tempat" value="0" class="form-check-input fs-4" {{ (isset($p48->sebut_nama_umur_tempat) && $p48->sebut_nama_umur_tempat == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Anak bisa mengerti arti kata di atas, di bawah, di depan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="mengerti_arti_kata_posisi" value="1" class="form-check-input fs-4" {{ (isset($p48->mengerti_arti_kata_posisi) && $p48->mengerti_arti_kata_posisi == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="mengerti_arti_kata_posisi" value="0" class="form-check-input fs-4" {{ (isset($p48->mengerti_arti_kata_posisi) && $p48->mengerti_arti_kata_posisi == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">9</td>
                                    <td class="fw-medium">Anak bisa mendengarkan cerita?</td>
                                    <td class="text-center">
                                        <input type="radio" name="dengar_cerita" value="1" class="form-check-input fs-4" {{ (isset($p48->dengar_cerita) && $p48->dengar_cerita == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="dengar_cerita" value="0" class="form-check-input fs-4" {{ (isset($p48->dengar_cerita) && $p48->dengar_cerita == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">10</td>
                                    <td class="fw-medium">Anak bisa mencuci dan mengeringkan tangan sendiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="cuci_tangan_sendiri" value="1" class="form-check-input fs-4" {{ (isset($p48->cuci_tangan_sendiri) && $p48->cuci_tangan_sendiri == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="cuci_tangan_sendiri" value="0" class="form-check-input fs-4" {{ (isset($p48->cuci_tangan_sendiri) && $p48->cuci_tangan_sendiri == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">11</td>
                                    <td class="fw-medium">Anak bermain bersama teman, mengikuti aturan permainan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="bermain_dengan_teman" value="1" class="form-check-input fs-4" {{ (isset($p48->bermain_dengan_teman) && $p48->bermain_dengan_teman == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="bermain_dengan_teman" value="0" class="form-check-input fs-4" {{ (isset($p48->bermain_dengan_teman) && $p48->bermain_dengan_teman == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">12</td>
                                    <td class="fw-medium">Anak bisa mengenakan sepatu sendiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pakai_sepatu_sendiri" value="1" class="form-check-input fs-4" {{ (isset($p48->pakai_sepatu_sendiri) && $p48->pakai_sepatu_sendiri == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pakai_sepatu_sendiri" value="0" class="form-check-input fs-4" {{ (isset($p48->pakai_sepatu_sendiri) && $p48->pakai_sepatu_sendiri == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">13</td>
                                    <td class="fw-medium">Anak bisa mengenakan celana panjang, kemeja, baju?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pakai_celana_baju_sendiri" value="1" class="form-check-input fs-4" {{ (isset($p48->pakai_celana_baju_sendiri) && $p48->pakai_celana_baju_sendiri == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pakai_celana_baju_sendiri" value="0" class="form-check-input fs-4" {{ (isset($p48->pakai_celana_baju_sendiri) && $p48->pakai_celana_baju_sendiri == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Tumbuh Kembang 3 - 4 Tahun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Perkembangan 4 - 5 Tahun Tab -->
    <div class="col-12" x-show="activeTab === 'perkembangan60'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Anak Umur 4 - 5 Tahun</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>
            <div class="card-body p-4">
                @php $p60 = $perk60 ?? null; @endphp
                <form action="{{ route('pengguna.perkembangan_anak_60_bulan.save') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-4">
                            <thead class="table-warning text-dark text-center">
                                <tr>
                                    <th style="width: 60px;" class="py-3">No.</th>
                                    <th class="py-3 text-start">Penanda Perkembangan Anak</th>
                                    <th style="width: 100px;" class="py-3">Ya</th>
                                    <th style="width: 100px;" class="py-3">Tidak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold bg-light">1</td>
                                    <td class="fw-medium">Anak bisa berdiri 1 kaki 6 detik?</td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_1_kaki_6_detik" value="1" class="form-check-input fs-4" {{ (isset($p60->berdiri_1_kaki_6_detik) && $p60->berdiri_1_kaki_6_detik == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_1_kaki_6_detik" value="0" class="form-check-input fs-4" {{ (isset($p60->berdiri_1_kaki_6_detik) && $p60->berdiri_1_kaki_6_detik == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Anak bisa melompat-lompat 1 kaki?</td>
                                    <td class="text-center">
                                        <input type="radio" name="lompat_1_kaki" value="1" class="form-check-input fs-4" {{ (isset($p60->lompat_1_kaki) && $p60->lompat_1_kaki == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="lompat_1_kaki" value="0" class="form-check-input fs-4" {{ (isset($p60->lompat_1_kaki) && $p60->lompat_1_kaki == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Anak bisa menari?</td>
                                    <td class="text-center">
                                        <input type="radio" name="menari" value="1" class="form-check-input fs-4" {{ (isset($p60->menari) && $p60->menari == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="menari" value="0" class="form-check-input fs-4" {{ (isset($p60->menari) && $p60->menari == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Anak bisa menggambar tanda silang?</td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_tanda_silang" value="1" class="form-check-input fs-4" {{ (isset($p60->gambar_tanda_silang) && $p60->gambar_tanda_silang == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_tanda_silang" value="0" class="form-check-input fs-4" {{ (isset($p60->gambar_tanda_silang) && $p60->gambar_tanda_silang == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Anak bisa menggambar lingkaran?</td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_lingkaran" value="1" class="form-check-input fs-4" {{ (isset($p60->gambar_lingkaran) && $p60->gambar_lingkaran == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_lingkaran" value="0" class="form-check-input fs-4" {{ (isset($p60->gambar_lingkaran) && $p60->gambar_lingkaran == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Anak bisa menggambar orang dengan 3 bagian tubuh?</td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_orang_3_bagian" value="1" class="form-check-input fs-4" {{ (isset($p60->gambar_orang_3_bagian) && $p60->gambar_orang_3_bagian == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_orang_3_bagian" value="0" class="form-check-input fs-4" {{ (isset($p60->gambar_orang_3_bagian) && $p60->gambar_orang_3_bagian == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Anak bisa mengancing baju atau pakaian boneka?</td>
                                    <td class="text-center">
                                        <input type="radio" name="kancing_baju_boneka" value="1" class="form-check-input fs-4" {{ (isset($p60->kancing_baju_boneka) && $p60->kancing_baju_boneka == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="kancing_baju_boneka" value="0" class="form-check-input fs-4" {{ (isset($p60->kancing_baju_boneka) && $p60->kancing_baju_boneka == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Anak bisa menyebut nama lengkap tanpa dibantu?</td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_nama_lengkap" value="1" class="form-check-input fs-4" {{ (isset($p60->sebut_nama_lengkap) && $p60->sebut_nama_lengkap == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_nama_lengkap" value="0" class="form-check-input fs-4" {{ (isset($p60->sebut_nama_lengkap) && $p60->sebut_nama_lengkap == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">9</td>
                                    <td class="fw-medium">Anak bisa senang menyebut kata-kata baru?</td>
                                    <td class="text-center">
                                        <input type="radio" name="senang_sebut_kata_baru" value="1" class="form-check-input fs-4" {{ (isset($p60->senang_sebut_kata_baru) && $p60->senang_sebut_kata_baru == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="senang_sebut_kata_baru" value="0" class="form-check-input fs-4" {{ (isset($p60->senang_sebut_kata_baru) && $p60->senang_sebut_kata_baru == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">10</td>
                                    <td class="fw-medium">Anak bisa senang bertanya tentang sesuatu?</td>
                                    <td class="text-center">
                                        <input type="radio" name="senang_bertanya" value="1" class="form-check-input fs-4" {{ (isset($p60->senang_bertanya) && $p60->senang_bertanya == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="senang_bertanya" value="0" class="form-check-input fs-4" {{ (isset($p60->senang_bertanya) && $p60->senang_bertanya == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">11</td>
                                    <td class="fw-medium">Anak bisa menjawab pertanyaan dengan kata-kata yang benar?</td>
                                    <td class="text-center">
                                        <input type="radio" name="jawab_pertanyaan_kata_benar" value="1" class="form-check-input fs-4" {{ (isset($p60->jawab_pertanyaan_kata_benar) && $p60->jawab_pertanyaan_kata_benar == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="jawab_pertanyaan_kata_benar" value="0" class="form-check-input fs-4" {{ (isset($p60->jawab_pertanyaan_kata_benar) && $p60->jawab_pertanyaan_kata_benar == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">12</td>
                                    <td class="fw-medium">Anak bisa bicara yang mudah dimengerti?</td>
                                    <td class="text-center">
                                        <input type="radio" name="bicara_mudah_dimengerti" value="1" class="form-check-input fs-4" {{ (isset($p60->bicara_mudah_dimengerti) && $p60->bicara_mudah_dimengerti == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="bicara_mudah_dimengerti" value="0" class="form-check-input fs-4" {{ (isset($p60->bicara_mudah_dimengerti) && $p60->bicara_mudah_dimengerti == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">13</td>
                                    <td class="fw-medium">Anak bisa membandingkan/membedakan sesuatu dari ukuran dan bentuknya?</td>
                                    <td class="text-center">
                                        <input type="radio" name="banding_ukuran_bentuk" value="1" class="form-check-input fs-4" {{ (isset($p60->banding_ukuran_bentuk) && $p60->banding_ukuran_bentuk == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="banding_ukuran_bentuk" value="0" class="form-check-input fs-4" {{ (isset($p60->banding_ukuran_bentuk) && $p60->banding_ukuran_bentuk == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">14</td>
                                    <td class="fw-medium">Anak bisa menyebut angka, menghitung jari?</td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_angka_hitung_jari" value="1" class="form-check-input fs-4" {{ (isset($p60->sebut_angka_hitung_jari) && $p60->sebut_angka_hitung_jari == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_angka_hitung_jari" value="0" class="form-check-input fs-4" {{ (isset($p60->sebut_angka_hitung_jari) && $p60->sebut_angka_hitung_jari == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Tumbuh Kembang 4 - 5 Tahun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Perkembangan 5 - 6 Tahun Tab -->
    <div class="col-12" x-show="activeTab === 'perkembangan72'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Anak Umur 5 - 6 Tahun</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>
            <div class="card-body p-4">
                @php $p72 = $perk72 ?? null; @endphp
                <form action="{{ route('pengguna.perkembangan_anak_72_bulan.save') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-4">
                            <thead class="table-warning text-dark text-center">
                                <tr>
                                    <th style="width: 60px;" class="py-3">No.</th>
                                    <th class="py-3 text-start">Penanda Perkembangan Anak</th>
                                    <th style="width: 100px;" class="py-3">Ya</th>
                                    <th style="width: 100px;" class="py-3">Tidak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold bg-light">1</td>
                                    <td class="fw-medium">Anak bisa berjalan lurus?</td>
                                    <td class="text-center">
                                        <input type="radio" name="berjalan_lurus" value="1" class="form-check-input fs-4" {{ (isset($p72->berjalan_lurus) && $p72->berjalan_lurus == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="berjalan_lurus" value="0" class="form-check-input fs-4" {{ (isset($p72->berjalan_lurus) && $p72->berjalan_lurus == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Anak bisa berdiri dengan 1 kaki selama 11 detik?</td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_1_kaki_11_detik" value="1" class="form-check-input fs-4" {{ (isset($p72->berdiri_1_kaki_11_detik) && $p72->berdiri_1_kaki_11_detik == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_1_kaki_11_detik" value="0" class="form-check-input fs-4" {{ (isset($p72->berdiri_1_kaki_11_detik) && $p72->berdiri_1_kaki_11_detik == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Anak bisa menggambar dengan 6 bagian, menggambar orang lengkap?</td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_6_bagian_orang_lengkap" value="1" class="form-check-input fs-4" {{ (isset($p72->gambar_6_bagian_orang_lengkap) && $p72->gambar_6_bagian_orang_lengkap == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_6_bagian_orang_lengkap" value="0" class="form-check-input fs-4" {{ (isset($p72->gambar_6_bagian_orang_lengkap) && $p72->gambar_6_bagian_orang_lengkap == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Anak bisa menangkap bola kecil dengan kedua tangan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tangkap_bola_kecil" value="1" class="form-check-input fs-4" {{ (isset($p72->tangkap_bola_kecil) && $p72->tangkap_bola_kecil == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tangkap_bola_kecil" value="0" class="form-check-input fs-4" {{ (isset($p72->tangkap_bola_kecil) && $p72->tangkap_bola_kecil == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Anak bisa menggambar segi empat?</td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_segi_empat" value="1" class="form-check-input fs-4" {{ (isset($p72->gambar_segi_empat) && $p72->gambar_segi_empat == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="gambar_segi_empat" value="0" class="form-check-input fs-4" {{ (isset($p72->gambar_segi_empat) && $p72->gambar_segi_empat == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Anak bisa mengerti arti lawan kata?</td>
                                    <td class="text-center">
                                        <input type="radio" name="mengerti_lawan_kata" value="1" class="form-check-input fs-4" {{ (isset($p72->mengerti_lawan_kata) && $p72->mengerti_lawan_kata == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="mengerti_lawan_kata" value="0" class="form-check-input fs-4" {{ (isset($p72->mengerti_lawan_kata) && $p72->mengerti_lawan_kata == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Anak bisa mengerti pembicaraan yang menggunakan 7 kata atau lebih?</td>
                                    <td class="text-center">
                                        <input type="radio" name="mengerti_pembicaraan_7_kata" value="1" class="form-check-input fs-4" {{ (isset($p72->mengerti_pembicaraan_7_kata) && $p72->mengerti_pembicaraan_7_kata == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="mengerti_pembicaraan_7_kata" value="0" class="form-check-input fs-4" {{ (isset($p72->mengerti_pembicaraan_7_kata) && $p72->mengerti_pembicaraan_7_kata == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Anak bisa menjawab pertanyaan tentang benda terbuat dari apa dan kegunaannya?</td>
                                    <td class="text-center">
                                        <input type="radio" name="jawab_bahan_guna_benda" value="1" class="form-check-input fs-4" {{ (isset($p72->jawab_bahan_guna_benda) && $p72->jawab_bahan_guna_benda == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="jawab_bahan_guna_benda" value="0" class="form-check-input fs-4" {{ (isset($p72->jawab_bahan_guna_benda) && $p72->jawab_bahan_guna_benda == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">9</td>
                                    <td class="fw-medium">Anak bisa mengenal angka, bisa menghitung angka 5-10?</td>
                                    <td class="text-center">
                                        <input type="radio" name="kenal_angka_hitung_5_10" value="1" class="form-check-input fs-4" {{ (isset($p72->kenal_angka_hitung_5_10) && $p72->kenal_angka_hitung_5_10 == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="kenal_angka_hitung_5_10" value="0" class="form-check-input fs-4" {{ (isset($p72->kenal_angka_hitung_5_10) && $p72->kenal_angka_hitung_5_10 == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">10</td>
                                    <td class="fw-medium">Anak bisa mengenal warna-warni?</td>
                                    <td class="text-center">
                                        <input type="radio" name="kenal_warna_warni" value="1" class="form-check-input fs-4" {{ (isset($p72->kenal_warna_warni) && $p72->kenal_warna_warni == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="kenal_warna_warni" value="0" class="form-check-input fs-4" {{ (isset($p72->kenal_warna_warni) && $p72->kenal_warna_warni == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">11</td>
                                    <td class="fw-medium">Anak bisa mengungkapkan simpati?</td>
                                    <td class="text-center">
                                        <input type="radio" name="ungkapkan_simpati" value="1" class="form-check-input fs-4" {{ (isset($p72->ungkapkan_simpati) && $p72->ungkapkan_simpati == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="ungkapkan_simpati" value="0" class="form-check-input fs-4" {{ (isset($p72->ungkapkan_simpati) && $p72->ungkapkan_simpati == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">12</td>
                                    <td class="fw-medium">Anak bisa mengikuti aturan permainan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="ikut_aturan_permainan" value="1" class="form-check-input fs-4" {{ (isset($p72->ikut_aturan_permainan) && $p72->ikut_aturan_permainan == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="ikut_aturan_permainan" value="0" class="form-check-input fs-4" {{ (isset($p72->ikut_aturan_permainan) && $p72->ikut_aturan_permainan == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">13</td>
                                    <td class="fw-medium">Anak bisa berpakaian sendiri tanpa dibantu?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pakaian_sendiri_tanpa_bantu" value="1" class="form-check-input fs-4" {{ (isset($p72->pakaian_sendiri_tanpa_bantu) && $p72->pakaian_sendiri_tanpa_bantu == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pakaian_sendiri_tanpa_bantu" value="0" class="form-check-input fs-4" {{ (isset($p72->pakaian_sendiri_tanpa_bantu) && $p72->pakaian_sendiri_tanpa_bantu == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Tumbuh Kembang 5 - 6 Tahun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Helper Macro Component for Month Selection & Forms -->
    @php
        $tabConfigs = [
            'tahun2' => ['title' => 'Usia 2 Tahun (Bulan 24 - 35)', 'range' => range(24, 35)],
            'tahun3' => ['title' => 'Usia 3 Tahun (Bulan 36 - 47)', 'range' => range(36, 47)],
            'tahun4' => ['title' => 'Usia 4 Tahun (Bulan 48 - 59)', 'range' => range(48, 59)],
            'tahun5' => ['title' => 'Usia 5 Tahun (Bulan 60 - 71)', 'range' => range(60, 71)],
        ];
    @endphp

    @foreach($tabConfigs as $tabKey => $config)
        <div class="col-12" x-show="activeTab === '{{ $tabKey }}'" x-transition>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1 text-gradient">{{ $config['title'] }}</h5>
                        <p class="text-muted small mb-0">Klik pada tombol bulan untuk mencatat atau melihat hasil pemantauan.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($config['range'] as $m)
                            <button @click="activeMonth = {{ $m }}" 
                                    :class="activeMonth === {{ $m }} ? 'btn-primary shadow-sm' : 'btn-outline-primary'"
                                    class="btn rounded-circle fw-bold p-0" style="width: 42px; height: 42px;">
                                {{ $m }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="card-body p-4">
                    @foreach($config['range'] as $m)
                        @php $item = $bulanan->get($m); @endphp
                        <div x-show="activeMonth === {{ $m }}" x-transition>
                            <div class="alert alert-light border rounded-4 p-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h6 class="fw-bold text-primary mb-0"><i class="bi bi-calendar-event-fill me-2"></i>Form Pemantauan Kesehatan - Bulan Ke-{{ $m }}</h6>
                                @if($item)
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> Telah Disimpan</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-exclamation-circle me-1"></i> Belum Disimpan</span>
                                @endif
                            </div>

                            <form action="{{ route('pengguna.bulanan_anak_72.save') }}" method="POST">
                                @csrf
                                <input type="hidden" name="bulan_ke" value="{{ $m }}">

                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <div class="list-group">
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="sesak_napas" value="1" {{ ($item->sesak_napas ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">1. Sesak napas / cuping hidung kembang kempis / dada tertarik ke dalam</span>
                                            </label>
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="batuk" value="1" {{ ($item->batuk ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">2. Batuk dengan bunyi grok-grok / mengi</span>
                                            </label>
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="suhu_abnormal" value="1" {{ ($item->suhu_abnormal ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">3. Suhu tubuh panas > 38.5 C / ada tanda perdarahan (mimisan/gusi berdarah/muntah kopi/BAB hitam)</span>
                                            </label>
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="bab_sering" value="1" {{ ($item->bab_sering ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">4. BAB lebih sering / lebih encer dengan mata cekung / haus minum lahap / diare disertai darah</span>
                                            </label>
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="kencing_sedikit" value="1" {{ ($item->kencing_sedikit ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">5. Jumlah air kencing sedikit / tidak kencing selama 6 jam, warna kuning pekat, kecoklatan, atau warna lainnya</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="list-group">
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="kulit_pucat_biru" value="1" {{ ($item->kulit_pucat_biru ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">6. Warna kulit tampak biru / memar di sekitar mulut / tangan / kaki</span>
                                            </label>
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="aktivitas_lemah" value="1" {{ ($item->aktivitas_lemah ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">7. Aktivitas tampak lemah / tidak bergerak / menangis merintih</span>
                                            </label>
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="telinga_cairan" value="1" {{ ($item->telinga_cairan ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">8. Hisapan bayi lemah / tidak bergerak, Muntah susu / cairan hijau, Kencing < 6x/hari, Warna kencing kurang pekat</span>
                                            </label>
                                            <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                                <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="tidak_makan" value="1" {{ ($item->tidak_makan ?? false) ? 'checked' : '' }}>
                                                <span class="small fw-medium">9. Tidak mau makan / minum, Berat badan tidak naik sesuai pertumbuhan</span>
                                            </label>

                                            <div class="p-3 bg-light rounded-3 mt-2 border-primary border-start border-4">
                                                <label class="form-label small fw-bold text-primary mb-1">10. Tanggal, Nama & Paraf Kader/Nakes</label>
                                                <input type="text" name="paraf_kader_nakes" value="{{ $item->paraf_kader_nakes ?? '' }}" class="form-control bg-white" placeholder="Contoh: 18/10/26 - Bidan Ratna">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end">
                                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                                            <i class="bi bi-save2-fill me-2"></i> Simpan Pemantauan Bulan {{ $m }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
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
    .bg-gradient-info {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
@endsection
