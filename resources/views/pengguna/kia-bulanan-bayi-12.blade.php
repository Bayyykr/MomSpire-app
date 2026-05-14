@extends('pengguna.master')

@section('title', 'Pemantauan Bayi 6 - 12 Bulan - MomSpire')
@section('header_title', 'Bayi Umur 6 - 12 Bulan')
@section('header_subtitle', 'Catat pemantauan bulanan kesehatan si kecil secara mandiri dari bulan ke-6 hingga ke-11.')

@section('content')
<div class="row g-4" x-data="{ activeMonth: {{ session('active_month', 6) }} }">
    <!-- Info Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-calendar2-check-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">🥣 Masa Pengenalan MPASI & Pemantauan Rutin (Usia 6 - 12 Bulan)</h6>
                    <p class="mb-0 small opacity-90">Pada rentang usia ini, bayi mulai diberikan MPASI (Makanan Pendamping ASI) seperti Bubur Sup Daging Kacang Merah (6-8 bulan) dan Nasi Tim Ikan Kembung Telur Puyuh (9-12 bulan). Lakukan pemantauan kesehatan setiap bulan secara rutin dan segera bawa ke Puskesmas jika terdapat tanda bahaya.</p>
                </div>
            </div>
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

    <!-- ================= PEMANTAUAN BULANAN (6 - 11) ================= -->
    <div class="col-12">
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
