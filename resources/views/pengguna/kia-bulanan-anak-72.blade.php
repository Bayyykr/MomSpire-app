@extends('pengguna.master')

@section('title', 'Pemantauan Anak 2 - 6 Tahun - MomSpire')
@section('header_title', 'Anak Umur 2 - 6 Tahun')
@section('header_subtitle', 'Catat hasil pemantauan bulanan kondisi dan kesehatan anak secara mandiri dari bulan ke-24 hingga bulan ke-71.')

@section('content')
<div class="row g-4" x-data="{ activeTab: '{{ session('active_tab', 'tahun2') }}', activeMonth: {{ session('active_month', 24) }} }">
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
        <div class="d-flex justify-content-center gap-3 border-bottom pb-3 flex-wrap">
            <button @click="activeTab = 'tahun2'; activeMonth = 24;" 
                    :class="activeTab === 'tahun2' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar2 me-2"></i> Usia 2 Tahun (Bln 24-35)
            </button>
            <button @click="activeTab = 'tahun3'; activeMonth = 36;" 
                    :class="activeTab === 'tahun3' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar3 me-2"></i> Usia 3 Tahun (Bln 36-47)
            </button>
            <button @click="activeTab = 'tahun4'; activeMonth = 48;" 
                    :class="activeTab === 'tahun4' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar4 me-2"></i> Usia 4 Tahun (Bln 48-59)
            </button>
            <button @click="activeTab = 'tahun5'; activeMonth = 60;" 
                    :class="activeTab === 'tahun5' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar4-week me-2"></i> Usia 5 Tahun (Bln 60-71)
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
