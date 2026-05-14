@extends('pengguna.master')

@section('title', 'Pemantauan Bayi 29 Hari - 3 Bulan - MomSpire')
@section('header_title', 'Bayi Umur 29 Hari - 3 Bulan')
@section('header_subtitle', 'Catat pemantauan mingguan kesehatan dan perkembangan si kecil secara mandiri.')

@section('content')
<div class="row g-4" x-data="{ activeTab: '{{ session('active_tab', 'mingguan') }}', activeWeek: {{ session('active_week', 5) }} }">
    <!-- Info Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-heart-pulse-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">👶 Perawatan & Pemantauan Bayi (Usia 29 Hari - 3 Bulan)</h6>
                    <p class="mb-0 small opacity-90">Lakukan pemantauan mingguan secara rutin dan berikan stimulasi tumbuh kembang seperti peluk, cium, ayun, dan senyum. Segera bawa ke Puskesmas/Faskes jika si kecil mengalami keluhan kesehatan atau belum bisa melakukan tahapan perkembangan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="col-12">
        <div class="d-flex justify-content-center gap-3 border-bottom pb-3">
            <button @click="activeTab = 'mingguan'" 
                    :class="activeTab === 'mingguan' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-5 transition-all fw-semibold">
                <i class="bi bi-calendar2-week me-2"></i> Pemantauan Mingguan (Minggu 5-9)
            </button>
            <button @click="activeTab = 'perkembangan'" 
                    :class="activeTab === 'perkembangan' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-5 transition-all fw-semibold">
                <i class="bi bi-check2-square me-2"></i> Checklist Perkembangan Anak
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

    <!-- ================= TAB 1: PEMANTAUAN MINGGUAN ================= -->
    <div class="col-12" x-show="activeTab === 'mingguan'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-gradient">Pilih Minggu Pemantauan</h5>
                    <p class="text-muted small mb-0">Klik pada tombol minggu untuk mencatat hasil pemantauan di minggu tersebut.</p>
                </div>
                <div class="d-flex gap-2">
                    @foreach(range(5, 9) as $w)
                        <button @click="activeWeek = {{ $w }}" 
                                :class="activeWeek === {{ $w }} ? 'btn-primary shadow-sm' : 'btn-outline-primary'"
                                class="btn rounded-circle fw-bold" style="width: 45px; height: 45px;">
                            {{ $w }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="card-body p-4">
                @foreach(range(5, 9) as $w)
                    @php $item = $mingguan->get($w); @endphp
                    <div x-show="activeWeek === {{ $w }}" x-transition>
                        <div class="alert alert-light border rounded-4 p-3 mb-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-calendar-event-fill me-2"></i>Form Pemantauan - Minggu Ke-{{ $w }}</h6>
                            @if($item)
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> Telah Disimpan</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-exclamation-circle me-1"></i> Belum Disimpan</span>
                            @endif
                        </div>

                        <form action="{{ route('pengguna.mingguan_bayi.save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="minggu_ke" value="{{ $w }}">

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
                                            <input type="text" name="paraf_kader_nakes" value="{{ $item->paraf_kader_nakes ?? '' }}" class="form-control bg-white" placeholder="Contoh: 20/06/26 - Bidan Sari">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                                        <i class="bi bi-save2-fill me-2"></i> Simpan Catatan Minggu {{ $w }}
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
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Bayi Anda</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>

            <div class="card-body p-4">
                @php $p = $perkembangan; @endphp
                <form action="{{ route('pengguna.perkembangan_bayi.save') }}" method="POST">
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
                                    <td class="fw-medium">Bayi bisa mengangkat kepala mandiri hingga setinggi 45 derajat?</td>
                                    <td class="text-center">
                                        <input type="radio" name="angkat_kepala_45" value="1" class="form-check-input fs-4" {{ (isset($p->angkat_kepala_45) && $p->angkat_kepala_45 == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="angkat_kepala_45" value="0" class="form-check-input fs-4" {{ (isset($p->angkat_kepala_45) && $p->angkat_kepala_45 == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Bayi bisa menggerakkan kepala dari kiri/kanan ke tengah?</td>
                                    <td class="text-center">
                                        <input type="radio" name="gerak_kepala" value="1" class="form-check-input fs-4" {{ (isset($p->gerak_kepala) && $p->gerak_kepala == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="gerak_kepala" value="0" class="form-check-input fs-4" {{ (isset($p->gerak_kepala) && $p->gerak_kepala == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Bayi bisa melihat dan menatap wajah anda?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tatap_wajah" value="1" class="form-check-input fs-4" {{ (isset($p->tatap_wajah) && $p->tatap_wajah == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tatap_wajah" value="0" class="form-check-input fs-4" {{ (isset($p->tatap_wajah) && $p->tatap_wajah == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Bayi bisa mengoceh spontan atau bereaksi dengan mengoceh?</td>
                                    <td class="text-center">
                                        <input type="radio" name="ngoceh" value="1" class="form-check-input fs-4" {{ (isset($p->ngoceh) && $p->ngoceh == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="ngoceh" value="0" class="form-check-input fs-4" {{ (isset($p->ngoceh) && $p->ngoceh == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Bayi suka tertawa keras?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tertawa_keras" value="1" class="form-check-input fs-4" {{ (isset($p->tertawa_keras) && $p->tertawa_keras == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tertawa_keras" value="0" class="form-check-input fs-4" {{ (isset($p->tertawa_keras) && $p->tertawa_keras == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Bayi bereaksi terkejut terhadap suara keras?</td>
                                    <td class="text-center">
                                        <input type="radio" name="terkejut_suara" value="1" class="form-check-input fs-4" {{ (isset($p->terkejut_suara) && $p->terkejut_suara == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="terkejut_suara" value="0" class="form-check-input fs-4" {{ (isset($p->terkejut_suara) && $p->terkejut_suara == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Bayi membalas tersenyum ketika diajak bicara/tersenyum?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tersenyum" value="1" class="form-check-input fs-4" {{ (isset($p->tersenyum) && $p->tersenyum == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tersenyum" value="0" class="form-check-input fs-4" {{ (isset($p->tersenyum) && $p->tersenyum == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Bayi mengenal ibu dengan penglihatan, penciuman, pendengaran, kontak?</td>
                                    <td class="text-center">
                                        <input type="radio" name="mengenal_ibu" value="1" class="form-check-input fs-4" {{ (isset($p->mengenal_ibu) && $p->mengenal_ibu == 1) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="mengenal_ibu" value="0" class="form-check-input fs-4" {{ (isset($p->mengenal_ibu) && $p->mengenal_ibu == 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Checklist Perkembangan
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
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
@endsection
