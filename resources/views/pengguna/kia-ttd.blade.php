@extends('pengguna.master')

@section('title', 'Catat Minum TTD/MMS - MomSpire')
@section('header_title', 'Catat Minum TTD/MMS')
@section('header_subtitle', 'Pantau kepatuhan konsumsi tablet tambah darah Anda setiap hari.')

@section('content')
<div class="row g-4" x-data="{ activeMonth: 1 }">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-gradient">Tabel Kepatuhan Minum TTD</h5>
                        <p class="text-muted small mb-0">Berikan tanda centang (✓) pada kotak sesuai tanggal Anda minum.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach(range(1, 10) as $m)
                            <button @click="activeMonth = {{ $m }}" 
                                    :class="activeMonth === {{ $m }} ? 'btn-primary' : 'btn-outline-secondary'"
                                    class="btn btn-sm rounded-pill px-3 transition-all">
                                Bln {{ $m }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-body p-4 bg-light">
                @foreach(range(1, 10) as $m)
                    @php $tracking = $trackings->get($m); @endphp
                    <div x-show="activeMonth === {{ $m }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <form action="{{ route('pengguna.ttd.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="bulan_ke" value="{{ $m }}">
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Usia Kehamilan</label>
                                    <input type="text" name="usia_kehamilan" value="{{ $tracking->usia_kehamilan ?? '' }}" class="form-control rounded-3 border-0 shadow-sm" placeholder="Contoh: 12 Minggu">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Bulan / Tahun</label>
                                    <input type="text" name="bulan_tahun" value="{{ $tracking->bulan_tahun ?? '' }}" class="form-control rounded-3 border-0 shadow-sm" placeholder="Contoh: 11/2024">
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-4 shadow-sm">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle mb-0">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                @foreach(range(1, 16) as $day)
                                                    <th class="py-2" style="width: 6.25%;">{{ $day }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach(range(1, 16) as $day)
                                                    <td class="py-3">
                                                        <input type="checkbox" name="h{{ $day }}" {{ ($tracking && $tracking->{"h$day"}) ? 'checked' : '' }} class="form-check-input custom-checkbox">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                        <thead class="bg-dark text-white border-top-0">
                                            <tr>
                                                @foreach(range(17, 31) as $day)
                                                    <th class="py-2">{{ $day }}</th>
                                                @endforeach
                                                <th class="bg-secondary">32+</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach(range(17, 31) as $day)
                                                    <td class="py-3">
                                                        <input type="checkbox" name="h{{ $day }}" {{ ($tracking && $tracking->{"h$day"}) ? 'checked' : '' }} class="form-check-input custom-checkbox">
                                                    </td>
                                                @endforeach
                                                <td class="bg-light"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                    <i class="bi bi-save2-fill me-2"></i> Simpan Bulan ke-{{ $m }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-info-circle-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Pentingnya TTD/MMS</h6>
                    <p class="mb-0 small opacity-75">Untuk mencegah kekurangan darah, TTD/MMS harus diminum setiap hari selama kehamilan. Sebaiknya diminum pada malam hari sebelum tidur untuk mengurangi rasa mual.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-info {
        background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
    }
    .custom-checkbox {
        width: 24px;
        height: 24px;
        cursor: pointer;
        border-color: #dee2e6;
    }
    .custom-checkbox:checked {
        background-color: var(--accent2);
        border-color: var(--accent2);
    }
    .transition-all {
        transition: all 0.2s ease;
    }
</style>
@endsection
