@extends('pengguna.master')

@section('title', 'Pemantauan Bayi 6 - 12 Bulan - MomSpire')
@section('header_title', 'Bayi Umur 6 - 12 Bulan')
@section('header_subtitle', 'Catat pemantauan bulanan kesehatan dan perkembangan si kecil secara mandiri dari bulan ke-6 hingga ke-12.')

@section('content')
<div class="row g-4" x-data="{ activeTab: '{{ session('active_tab', 'bulanan') }}', activeMonth: {{ session('active_month', 6) }} }">
    <!-- Info Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-calendar2-check-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">🥣 Masa Pengenalan MPASI & Pemantauan Rutin (Usia 6 - 12 Bulan)</h6>
                    <p class="mb-0 small opacity-90">Pada rentang usia ini, bayi mulai diberikan MPASI (Makanan Pendamping ASI). Lakukan pemantauan kesehatan setiap bulan secara rutin serta perhatikan tahapan tumbuh kembang si kecil. Segera bawa ke Faskes jika anak belum bisa melakukan kemampuan sesuai usianya.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="col-12">
        <div class="d-flex justify-content-center gap-3 border-bottom pb-3 flex-wrap">
            <button @click="activeTab = 'bulanan'" 
                    :class="activeTab === 'bulanan' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar2-check me-2"></i> Pemantauan Bulanan (Bulan 6-11)
            </button>
            <button @click="activeTab = 'perkembangan9'" 
                    :class="activeTab === 'perkembangan9' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-check2-square me-2"></i> Tumbuh Kembang (6-9 Bulan)
            </button>
            <button @click="activeTab = 'perkembangan12'" 
                    :class="activeTab === 'perkembangan12' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-check2-all me-2"></i> Tumbuh Kembang (9-12 Bulan)
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

    <!-- ================= TAB 1: PEMANTAUAN BULANAN (6 - 11) ================= -->
    <div class="col-12" x-show="activeTab === 'bulanan'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-gradient">Pilih Bulan Pemantauan</h5>
                    <p class="text-muted small mb-0">Klik pada tombol bulan untuk mencatat hasil pemantauan di bulan tersebut.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach(range(6, 11) as $m)
                        <button @click="activeMonth = {{ $m }}" 
                                :class="activeMonth === {{ $m }} ? 'btn-primary shadow-sm' : 'btn-outline-primary'"
                                class="btn rounded-circle fw-bold" style="width: 45px; height: 45px;">
                            {{ $m }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="card-body p-4">
                @foreach(range(6, 11) as $m)
                    @php $item = $bulanan->get($m); @endphp
                    <div x-show="activeMonth === {{ $m }}" x-transition>
                        <div class="alert alert-light border rounded-4 p-3 mb-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-calendar-event-fill me-2"></i>Form Pemantauan - Bulan Ke-{{ $m }}</h6>
                            @if($item)
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> Telah Disimpan</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-exclamation-circle me-1"></i> Belum Disimpan</span>
                            @endif
                        </div>

                        <form action="{{ route('pengguna.bulanan_bayi_12.save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="bulan_ke" value="{{ $m }}">

                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <div class="list-group">
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="sesak_napas" value="1" {{ ($item->sesak_napas ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">Sesak napas / cuping hidung kembang kempis / tarikan dada ke dalam</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="batuk" value="1" {{ ($item->batuk ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">Batuk dengan bunyi grok-grok / mengi</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="suhu_abnormal" value="1" {{ ($item->suhu_abnormal ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">Suhu tubuh panas > 38.5 C / perdarahan (mimisan/gusi berdarah/muntah darah/BAB hitam)</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="bab_sering" value="1" {{ ($item->bab_sering ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">BAB lebih sering / encer, mata cekung, haus minum lahap, atau diare berdarah</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="kencing_sedikit" value="1" {{ ($item->kencing_sedikit ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">Kencing sedikit / tidak kencing 6 jam, warna kuning pekat / kecoklatan</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="list-group">
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="kulit_biru" value="1" {{ ($item->kulit_biru ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">Warna kulit tampak biru / memar di sekitar mulut / tangan / kaki</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="aktivitas_lemah" value="1" {{ ($item->aktivitas_lemah ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">Aktivitas tampak lemah / tidak bergerak / menangis merintih</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="hisapan_lemah" value="1" {{ ($item->hisapan_lemah ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">Hisapan bayi lemah / tidak bergerak, muntah susu cairan hijau</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="tidak_makan" value="1" {{ ($item->tidak_makan ?? false) ? 'checked' : '' }}>
                                            <span class="small fw-medium">Tidak mau makan / minum, berat badan tidak naik sesuai pertumbuhan</span>
                                        </label>

                                        <div class="p-3 bg-light rounded-3 mt-2 border-primary border-start border-4">
                                            <label class="form-label small fw-bold text-primary mb-1">Tanggal, Nama & Paraf Kader/Nakes</label>
                                            <input type="text" name="paraf_kader_nakes" value="{{ $item->paraf_kader_nakes ?? '' }}" class="form-control bg-white" placeholder="Contoh: 12/08/26 - Kader Rahma">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                                        <i class="bi bi-save2-fill me-2"></i> Simpan Catatan Bulan {{ $m }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ================= TAB 2: PERKEMBANGAN 6 - 9 BULAN ================= -->
    <div class="col-12" x-show="activeTab === 'perkembangan9'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Bayi Umur 6 - 9 Bulan</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>

            <div class="card-body p-4">
                @php $p9 = $perkembangan9 ?? null; @endphp
                <form action="{{ route('pengguna.perkembangan_bayi_9_bulan.save') }}" method="POST">
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
                                    <td class="fw-medium">Bayi bisa duduk secara mandiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="duduk_mandiri" value="1" class="form-check-input fs-4" {{ (isset($p9->duduk_mandiri) && $p9->duduk_mandiri == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="duduk_mandiri" value="0" class="form-check-input fs-4" {{ (isset($p9->duduk_mandiri) && $p9->duduk_mandiri == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Bayi tengkurap mandiri, menopang berat dengan dada/lengan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tengkurap_dada" value="1" class="form-check-input fs-4" {{ (isset($p9->tengkurap_dada) && $p9->tengkurap_dada == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tengkurap_dada" value="0" class="form-check-input fs-4" {{ (isset($p9->tengkurap_dada) && $p9->tengkurap_dada == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Bayi bisa merangkak meraih mainan atau mendekati seseorang?</td>
                                    <td class="text-center">
                                        <input type="radio" name="merangkak" value="1" class="form-check-input fs-4" {{ (isset($p9->merangkak) && $p9->merangkak == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="merangkak" value="0" class="form-check-input fs-4" {{ (isset($p9->merangkak) && $p9->merangkak == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Bayi bisa memindahkan benda dari satu tangan ke tangan lainnya?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pindah_benda" value="1" class="form-check-input fs-4" {{ (isset($p9->pindah_benda) && $p9->pindah_benda == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pindah_benda" value="0" class="form-check-input fs-4" {{ (isset($p9->pindah_benda) && $p9->pindah_benda == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Bayi bisa memungut 2 benda, kedua tangan pegang 2 benda pada saat bersamaan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pungut_2_benda" value="1" class="form-check-input fs-4" {{ (isset($p9->pungut_2_benda) && $p9->pungut_2_benda == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pungut_2_benda" value="0" class="form-check-input fs-4" {{ (isset($p9->pungut_2_benda) && $p9->pungut_2_benda == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Bayi bisa memungut benda sebesar kacang dengan cara meraup?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pungut_kacang" value="1" class="form-check-input fs-4" {{ (isset($p9->pungut_kacang) && $p9->pungut_kacang == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pungut_kacang" value="0" class="form-check-input fs-4" {{ (isset($p9->pungut_kacang) && $p9->pungut_kacang == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Bayi bersuara tanpa arti (mamama, bababa, dadada, tatata)?</td>
                                    <td class="text-center">
                                        <input type="radio" name="bersuara_tanpa_arti" value="1" class="form-check-input fs-4" {{ (isset($p9->bersuara_tanpa_arti) && $p9->bersuara_tanpa_arti == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="bersuara_tanpa_arti" value="0" class="form-check-input fs-4" {{ (isset($p9->bersuara_tanpa_arti) && $p9->bersuara_tanpa_arti == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Bayi mencari mainan/benda yang dijatuhkan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="cari_mainan" value="1" class="form-check-input fs-4" {{ (isset($p9->cari_mainan) && $p9->cari_mainan == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="cari_mainan" value="0" class="form-check-input fs-4" {{ (isset($p9->cari_mainan) && $p9->cari_mainan == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">9</td>
                                    <td class="fw-medium">Bayi bermain tepuk tangan / cilukba?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tepuk_tangan" value="1" class="form-check-input fs-4" {{ (isset($p9->tepuk_tangan) && $p9->tepuk_tangan == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tepuk_tangan" value="0" class="form-check-input fs-4" {{ (isset($p9->tepuk_tangan) && $p9->tepuk_tangan == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">10</td>
                                    <td class="fw-medium">Bayi bergembira dengan melempar benda?</td>
                                    <td class="text-center">
                                        <input type="radio" name="lempar_benda" value="1" class="form-check-input fs-4" {{ (isset($p9->lempar_benda) && $p9->lempar_benda == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="lempar_benda" value="0" class="form-check-input fs-4" {{ (isset($p9->lempar_benda) && $p9->lempar_benda == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">11</td>
                                    <td class="fw-medium">Bayi makan kue / biskuit sendiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="makan_kue" value="1" class="form-check-input fs-4" {{ (isset($p9->makan_kue) && $p9->makan_kue == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="makan_kue" value="0" class="form-check-input fs-4" {{ (isset($p9->makan_kue) && $p9->makan_kue == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Tumbuh Kembang 6-9 Bulan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= TAB 3: PERKEMBANGAN 9 - 12 BULAN ================= -->
    <div class="col-12" x-show="activeTab === 'perkembangan12'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Bayi Umur 9 - 12 Bulan</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>

            <div class="card-body p-4">
                @php $p12 = $perkembangan12 ?? null; @endphp
                <form action="{{ route('pengguna.perkembangan_bayi_12_bulan.save') }}" method="POST">
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
                                    <td class="fw-medium">Bayi bisa mengangkat badannya ke posisi berdiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="angkat_badan_berdiri" value="1" class="form-check-input fs-4" {{ (isset($p12->angkat_badan_berdiri) && $p12->angkat_badan_berdiri == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="angkat_badan_berdiri" value="0" class="form-check-input fs-4" {{ (isset($p12->angkat_badan_berdiri) && $p12->angkat_badan_berdiri == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Bayi belajar berdiri selama 30 detik dengan berpegangan di kursi/meja?</td>
                                    <td class="text-center">
                                        <input type="radio" name="belajar_berdiri" value="1" class="form-check-input fs-4" {{ (isset($p12->belajar_berdiri) && $p12->belajar_berdiri == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="belajar_berdiri" value="0" class="form-check-input fs-4" {{ (isset($p12->belajar_berdiri) && $p12->belajar_berdiri == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Bayi dapat berjalan dengan dituntun?</td>
                                    <td class="text-center">
                                        <input type="radio" name="jalan_dituntun" value="1" class="form-check-input fs-4" {{ (isset($p12->jalan_dituntun) && $p12->jalan_dituntun == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="jalan_dituntun" value="0" class="form-check-input fs-4" {{ (isset($p12->jalan_dituntun) && $p12->jalan_dituntun == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Bayi mengulurkan tangan/badan untuk meraih mainan yang diinginkan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="ulur_tangan_raih" value="1" class="form-check-input fs-4" {{ (isset($p12->ulur_tangan_raih) && $p12->ulur_tangan_raih == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="ulur_tangan_raih" value="0" class="form-check-input fs-4" {{ (isset($p12->ulur_tangan_raih) && $p12->ulur_tangan_raih == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Bayi bisa menggenggam erat pensil?</td>
                                    <td class="text-center">
                                        <input type="radio" name="genggam_pensil" value="1" class="form-check-input fs-4" {{ (isset($p12->genggam_pensil) && $p12->genggam_pensil == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="genggam_pensil" value="0" class="form-check-input fs-4" {{ (isset($p12->genggam_pensil) && $p12->genggam_pensil == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Bayi memasukkan benda ke mulut?</td>
                                    <td class="text-center">
                                        <input type="radio" name="masuk_benda_mulut" value="1" class="form-check-input fs-4" {{ (isset($p12->masuk_benda_mulut) && $p12->masuk_benda_mulut == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="masuk_benda_mulut" value="0" class="form-check-input fs-4" {{ (isset($p12->masuk_benda_mulut) && $p12->masuk_benda_mulut == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Bayi mengulangi menirukan bunyi yang didengar?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tiru_bunyi" value="1" class="form-check-input fs-4" {{ (isset($p12->tiru_bunyi) && $p12->tiru_bunyi == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tiru_bunyi" value="0" class="form-check-input fs-4" {{ (isset($p12->tiru_bunyi) && $p12->tiru_bunyi == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Bayi menyebut 2-3 kata suku kata yang sama tanpa arti?</td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_2_suku_kata" value="1" class="form-check-input fs-4" {{ (isset($p12->sebut_2_suku_kata) && $p12->sebut_2_suku_kata == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_2_suku_kata" value="0" class="form-check-input fs-4" {{ (isset($p12->sebut_2_suku_kata) && $p12->sebut_2_suku_kata == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">9</td>
                                    <td class="fw-medium">Bayi mengeksplorasi sekitar, ingin tahu, ingin menyentuh apa saja?</td>
                                    <td class="text-center">
                                        <input type="radio" name="eksplorasi_sekitar" value="1" class="form-check-input fs-4" {{ (isset($p12->eksplorasi_sekitar) && $p12->eksplorasi_sekitar == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="eksplorasi_sekitar" value="0" class="form-check-input fs-4" {{ (isset($p12->eksplorasi_sekitar) && $p12->eksplorasi_sekitar == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">10</td>
                                    <td class="fw-medium">Bayi bereaksi terhadap suara panggilan / namanya disebutkan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="reaksi_panggilan" value="1" class="form-check-input fs-4" {{ (isset($p12->reaksi_panggilan) && $p12->reaksi_panggilan == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="reaksi_panggilan" value="0" class="form-check-input fs-4" {{ (isset($p12->reaksi_panggilan) && $p12->reaksi_panggilan == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">11</td>
                                    <td class="fw-medium">Bayi senang diajak bermain cilukba?</td>
                                    <td class="text-center">
                                        <input type="radio" name="bermain_cilukba" value="1" class="form-check-input fs-4" {{ (isset($p12->bermain_cilukba) && $p12->bermain_cilukba == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="bermain_cilukba" value="0" class="form-check-input fs-4" {{ (isset($p12->bermain_cilukba) && $p12->bermain_cilukba == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">12</td>
                                    <td class="fw-medium">Bayi mengenal anggota keluarga, takut pada orang yang belum dikenal?</td>
                                    <td class="text-center">
                                        <input type="radio" name="kenal_keluarga" value="1" class="form-check-input fs-4" {{ (isset($p12->kenal_keluarga) && $p12->kenal_keluarga == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="kenal_keluarga" value="0" class="form-check-input fs-4" {{ (isset($p12->kenal_keluarga) && $p12->kenal_keluarga == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Tumbuh Kembang 9-12 Bulan
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
    .bg-gradient-info {
        background: linear-gradient(135deg, #c2410c 0%, #9a3412 100%);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
@endsection
