@extends('pengguna.master')

@section('title', 'Pemantauan Bayi 3 - 6 Bulan - MomSpire')
@section('header_title', 'Bayi Umur 3 - 6 Bulan')
@section('header_subtitle', 'Catat pemantauan bulanan kesehatan dan perkembangan si kecil secara mandiri.')

@section('content')
<div class="row g-4" x-data="{ activeTab: '{{ session('active_tab', 'bulanan') }}', activeMonth: {{ session('active_month', 3) }} }">
    <!-- Info Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-calendar-heart-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">👶 Perawatan & Pemantauan Bayi (Usia 3 - 6 Bulan)</h6>
                    <p class="mb-0 small opacity-90">Lakukan pemantauan bulanan (Bulan ke-3, 4, dan 5) secara rutin serta berikan stimulasi tumbuh kembang seperti bermain cilukba, melihat cermin, tengkurap, dan meraih mainan. Jangan lupa perawatan gigi dengan mengelap gusi menggunakan kain basah.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="col-12">
        <div class="d-flex justify-content-center gap-3 border-bottom pb-3 flex-wrap">
            <button @click="activeTab = 'bulanan'" 
                    :class="activeTab === 'bulanan' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-5 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar2-heart me-2"></i> Pemantauan Bulanan (Bulan 3-5)
            </button>
            <button @click="activeTab = 'perkembangan'" 
                    :class="activeTab === 'perkembangan' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-5 transition-all fw-semibold mb-2">
                <i class="bi bi-check2-square me-2"></i> Checklist Perkembangan Anak (3-6 Bulan)
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

    <!-- ================= TAB 1: PEMANTAUAN BULANAN ================= -->
    <div class="col-12" x-show="activeTab === 'bulanan'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-gradient">Pilih Bulan Pemantauan</h5>
                    <p class="text-muted small mb-0">Klik pada tombol bulan untuk mencatat hasil pemantauan di bulan tersebut.</p>
                </div>
                <div class="d-flex gap-2">
                    @foreach([3, 4, 5] as $m)
                        <button @click="activeMonth = {{ $m }}" 
                                :class="activeMonth === {{ $m }} ? 'btn-primary shadow-sm' : 'btn-outline-primary'"
                                class="btn rounded-circle fw-bold" style="width: 45px; height: 45px;">
                            {{ $m }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="card-body p-4">
                @foreach([3, 4, 5] as $m)
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

                        <form action="{{ route('pengguna.bulanan_bayi.save') }}" method="POST">
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
                                            <input type="text" name="paraf_kader_nakes" value="{{ $item->paraf_kader_nakes ?? '' }}" class="form-control bg-white" placeholder="Contoh: 15/07/26 - Bidan Ratna">
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

    <!-- ================= TAB 2: CHECKLIST PERKEMBANGAN ================= -->
    <div class="col-12" x-show="activeTab === 'perkembangan'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Bayi Umur 3 - 6 Bulan</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>

            <div class="card-body p-4">
                @php $p = $perkembangan; @endphp
                <form action="{{ route('pengguna.perkembangan_bayi_6_bulan.save') }}" method="POST">
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
                                    <td class="fw-medium">Bayi bisa berbalik dari telungkup ke telentang?</td>
                                    <td class="text-center">
                                        <input type="radio" name="berbalik" value="1" class="form-check-input fs-4" {{ (isset($p->berbalik) && $p->berbalik == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="berbalik" value="0" class="form-check-input fs-4" {{ (isset($p->berbalik) && $p->berbalik == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Bayi bisa mengangkat kepala secara mandiri hingga tegak 90 derajat?</td>
                                    <td class="text-center">
                                        <input type="radio" name="kepala_tegak_90" value="1" class="form-check-input fs-4" {{ (isset($p->kepala_tegak_90) && $p->kepala_tegak_90 == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="kepala_tegak_90" value="0" class="form-check-input fs-4" {{ (isset($p->kepala_tegak_90) && $p->kepala_tegak_90 == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Bayi bisa mempertahankan posisi kepala tetap tegak dan stabil?</td>
                                    <td class="text-center">
                                        <input type="radio" name="kepala_stabil" value="1" class="form-check-input fs-4" {{ (isset($p->kepala_stabil) && $p->kepala_stabil == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="kepala_stabil" value="0" class="form-check-input fs-4" {{ (isset($p->kepala_stabil) && $p->kepala_stabil == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Bayi bisa menggenggam mainan kecil atau mainan bertangkai?</td>
                                    <td class="text-center">
                                        <input type="radio" name="genggam_mainan" value="1" class="form-check-input fs-4" {{ (isset($p->genggam_mainan) && $p->genggam_mainan == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="genggam_mainan" value="0" class="form-check-input fs-4" {{ (isset($p->genggam_mainan) && $p->genggam_mainan == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Bayi bisa meraih benda yang ada dalam jangkauannya?</td>
                                    <td class="text-center">
                                        <input type="radio" name="raih_benda" value="1" class="form-check-input fs-4" {{ (isset($p->raih_benda) && $p->raih_benda == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="raih_benda" value="0" class="form-check-input fs-4" {{ (isset($p->raih_benda) && $p->raih_benda == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Bayi bisa mengamati tangannya sendiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="amati_tangan" value="1" class="form-check-input fs-4" {{ (isset($p->amati_tangan) && $p->amati_tangan == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="amati_tangan" value="0" class="form-check-input fs-4" {{ (isset($p->amati_tangan) && $p->amati_tangan == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Bayi berusaha memperluas pandangan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="luas_pandang" value="1" class="form-check-input fs-4" {{ (isset($p->luas_pandang) && $p->luas_pandang == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="luas_pandang" value="0" class="form-check-input fs-4" {{ (isset($p->luas_pandang) && $p->luas_pandang == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Bayi mengarahkan matanya pada benda-benda kecil?</td>
                                    <td class="text-center">
                                        <input type="radio" name="arah_mata" value="1" class="form-check-input fs-4" {{ (isset($p->arah_mata) && $p->arah_mata == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="arah_mata" value="0" class="form-check-input fs-4" {{ (isset($p->arah_mata) && $p->arah_mata == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">9</td>
                                    <td class="fw-medium">Bayi mengeluarkan suara gembira bernada tinggi atau memekik?</td>
                                    <td class="text-center">
                                        <input type="radio" name="suara_gembira" value="1" class="form-check-input fs-4" {{ (isset($p->suara_gembira) && $p->suara_gembira == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="suara_gembira" value="0" class="form-check-input fs-4" {{ (isset($p->suara_gembira) && $p->suara_gembira == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">10</td>
                                    <td class="fw-medium">Bayi tersenyum ketika melihat mainan/gambar yang menarik saat bermain sendiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="senyum_mainan" value="1" class="form-check-input fs-4" {{ (isset($p->senyum_mainan) && $p->senyum_mainan == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="senyum_mainan" value="0" class="form-check-input fs-4" {{ (isset($p->senyum_mainan) && $p->senyum_mainan == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Checklist Perkembangan 3-6 Bulan
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
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
@endsection
