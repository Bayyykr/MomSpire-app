@extends('pengguna.master')

@section('title', 'Kelas Ibu Balita - MomSpire')
@section('header_title', 'Kelas Ibu Balita')
@section('header_subtitle', 'Catat absensi kehadiran Kelas Ibu Balita (Sesi 1 - 60) secara mandiri.')

@section('content')
<div class="row g-4" x-data="{ activeTab: 1 }">
    <!-- Info Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-people-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">👶 Ibu, ayah, dan keluarga ikut Kelas Ibu Balita</h6>
                    <p class="mb-0 small opacity-90">Manfaat bagi ibu dan keluarga: Memperoleh informasi penting terkait pola asuh anak, tumbuh kembang, imunisasi, gizi, serta mendapat teman berdiskusi dan bertukar pengalaman.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Selector -->
    <div class="col-12">
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <button @click="activeTab = 1" 
                    :class="activeTab === 1 ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold">
                <i class="bi bi-person-check-fill me-1"></i> Sesi 1 - 15
            </button>
            <button @click="activeTab = 2" 
                    :class="activeTab === 2 ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold">
                <i class="bi bi-person-check-fill me-1"></i> Sesi 16 - 30
            </button>
            <button @click="activeTab = 3" 
                    :class="activeTab === 3 ? 'btn-gradient-secondary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold">
                <i class="bi bi-person-check me-1"></i> Sesi 31 - 45
            </button>
            <button @click="activeTab = 4" 
                    :class="activeTab === 4 ? 'btn-gradient-secondary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold">
                <i class="bi bi-person-check me-1"></i> Sesi 46 - 60
            </button>
        </div>
    </div>

    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Berhasil!</h6>
                        <span class="small text-muted">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Wrapper -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 animate__animated animate__fadeIn">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Absensi Kehadiran Kelas Ibu Balita</h5>
                <p class="text-muted small mb-0">Catat tanggal kehadiran Anda serta informasi/paraf dari kader kesehatan atau nakes.</p>
            </div>

            <div class="card-body p-4 bg-white">
                <!-- ================= TAB 1: Sesi 1 - 15 ================= -->
                <div x-show="activeTab === 1" x-transition>
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-bordered table-hover align-middle small mb-0 text-center">
                            <thead class="table-header-custom text-white">
                                <tr>
                                    <th style="width: 80px;">Sesi Ke-</th>
                                    <th style="width: 250px;">Tanggal Kehadiran</th>
                                    <th>Tanggal, Nama & Paraf Kader/Nakes</th>
                                    <th style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(1, 15) as $i)
                                    @php $item = $absensi->get($i); @endphp
                                    <form action="{{ route('pengguna.kelas_balita.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kehadiran_ke" value="{{ $i }}">
                                        <tr>
                                            <td class="fw-bold bg-light fs-5 text-secondary">{{ $i }}</td>
                                            <td>
                                                <input type="text" name="tanggal" value="{{ $item->tanggal ?? '' }}" placeholder="Contoh: 12 Juni 2026" class="form-control rounded-pill text-center border-2 px-3">
                                            </td>
                                            <td>
                                                <input type="text" name="kader_info" value="{{ $item->kader_info ?? '' }}" placeholder="Contoh: 12/06/26 - Bidan Ratna (Paraf)" class="form-control rounded-pill border-2 px-3">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-gradient-primary rounded-pill px-4 py-2 shadow-xs fw-semibold transition-all w-100">
                                                    <i class="bi bi-save2-fill me-1"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View -->
                    <div class="d-lg-none row g-3">
                        @foreach(range(1, 15) as $i)
                            @php $item = $absensi->get($i); @endphp
                            <div class="col-12">
                                <div class="card border-0 bg-light rounded-4 p-3 shadow-xs">
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                        <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold fs-6">Sesi {{ $i }}</span>
                                        @if($item)
                                            <span class="text-xs text-success"><i class="bi bi-check-circle-fill me-1"></i> Terisi</span>
                                        @else
                                            <span class="text-xs text-muted"><i class="bi bi-dash-circle me-1"></i> Belum diisi</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('pengguna.kelas_balita.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kehadiran_ke" value="{{ $i }}">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-secondary">Tanggal Kehadiran</label>
                                            <input type="text" name="tanggal" value="{{ $item->tanggal ?? '' }}" placeholder="Contoh: 12 Juni 2026" class="form-control rounded-pill border-2 px-3 small">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-secondary">Tanggal, Nama & Paraf Kader/Nakes</label>
                                            <input type="text" name="kader_info" value="{{ $item->kader_info ?? '' }}" placeholder="Contoh: 12/06/26 - Bidan Ratna" class="form-control rounded-pill border-2 px-3 small">
                                        </div>
                                        <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-xs fw-semibold py-2">
                                            <i class="bi bi-save2-fill me-1"></i> Simpan Sesi {{ $i }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ================= TAB 2: Sesi 16 - 30 ================= -->
                <div x-show="activeTab === 2" x-transition>
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-bordered table-hover align-middle small mb-0 text-center">
                            <thead class="table-header-custom text-white">
                                <tr>
                                    <th style="width: 80px;">Sesi Ke-</th>
                                    <th style="width: 250px;">Tanggal Kehadiran</th>
                                    <th>Tanggal, Nama & Paraf Kader/Nakes</th>
                                    <th style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(16, 30) as $i)
                                    @php $item = $absensi->get($i); @endphp
                                    <form action="{{ route('pengguna.kelas_balita.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kehadiran_ke" value="{{ $i }}">
                                        <tr>
                                            <td class="fw-bold bg-light fs-5 text-secondary">{{ $i }}</td>
                                            <td>
                                                <input type="text" name="tanggal" value="{{ $item->tanggal ?? '' }}" placeholder="Contoh: 12 Juni 2026" class="form-control rounded-pill text-center border-2 px-3">
                                            </td>
                                            <td>
                                                <input type="text" name="kader_info" value="{{ $item->kader_info ?? '' }}" placeholder="Contoh: 12/06/26 - Bidan Ratna (Paraf)" class="form-control rounded-pill border-2 px-3">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-gradient-primary rounded-pill px-4 py-2 shadow-xs fw-semibold transition-all w-100">
                                                    <i class="bi bi-save2-fill me-1"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View -->
                    <div class="d-lg-none row g-3">
                        @foreach(range(16, 30) as $i)
                            @php $item = $absensi->get($i); @endphp
                            <div class="col-12">
                                <div class="card border-0 bg-light rounded-4 p-3 shadow-xs">
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                        <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold fs-6">Sesi {{ $i }}</span>
                                        @if($item)
                                            <span class="text-xs text-success"><i class="bi bi-check-circle-fill me-1"></i> Terisi</span>
                                        @else
                                            <span class="text-xs text-muted"><i class="bi bi-dash-circle me-1"></i> Belum diisi</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('pengguna.kelas_balita.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kehadiran_ke" value="{{ $i }}">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-secondary">Tanggal Kehadiran</label>
                                            <input type="text" name="tanggal" value="{{ $item->tanggal ?? '' }}" placeholder="Contoh: 12 Juni 2026" class="form-control rounded-pill border-2 px-3 small">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-secondary">Tanggal, Nama & Paraf Kader/Nakes</label>
                                            <input type="text" name="kader_info" value="{{ $item->kader_info ?? '' }}" placeholder="Contoh: 12/06/26 - Bidan Ratna" class="form-control rounded-pill border-2 px-3 small">
                                        </div>
                                        <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-xs fw-semibold py-2">
                                            <i class="bi bi-save2-fill me-1"></i> Simpan Sesi {{ $i }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ================= TAB 3: Sesi 31 - 45 ================= -->
                <div x-show="activeTab === 3" x-transition>
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-bordered table-hover align-middle small mb-0 text-center">
                            <thead class="table-header-custom-secondary text-white">
                                <tr>
                                    <th style="width: 80px;">Sesi Ke-</th>
                                    <th style="width: 250px;">Tanggal Kehadiran</th>
                                    <th>Tanggal, Nama & Paraf Kader/Nakes</th>
                                    <th style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(31, 45) as $i)
                                    @php $item = $absensi->get($i); @endphp
                                    <form action="{{ route('pengguna.kelas_balita.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kehadiran_ke" value="{{ $i }}">
                                        <tr>
                                            <td class="fw-bold bg-light fs-5 text-secondary">{{ $i }}</td>
                                            <td>
                                                <input type="text" name="tanggal" value="{{ $item->tanggal ?? '' }}" placeholder="Contoh: 12 Juni 2026" class="form-control rounded-pill text-center border-2 px-3">
                                            </td>
                                            <td>
                                                <input type="text" name="kader_info" value="{{ $item->kader_info ?? '' }}" placeholder="Contoh: 12/06/26 - Bidan Ratna (Paraf)" class="form-control rounded-pill border-2 px-3">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-gradient-secondary rounded-pill px-4 py-2 shadow-xs fw-semibold transition-all w-100">
                                                    <i class="bi bi-save2-fill me-1"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View -->
                    <div class="d-lg-none row g-3">
                        @foreach(range(31, 45) as $i)
                            @php $item = $absensi->get($i); @endphp
                            <div class="col-12">
                                <div class="card border-0 bg-light rounded-4 p-3 shadow-xs">
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold fs-6">Sesi {{ $i }}</span>
                                        @if($item)
                                            <span class="text-xs text-success"><i class="bi bi-check-circle-fill me-1"></i> Terisi</span>
                                        @else
                                            <span class="text-xs text-muted"><i class="bi bi-dash-circle me-1"></i> Belum diisi</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('pengguna.kelas_balita.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kehadiran_ke" value="{{ $i }}">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-secondary">Tanggal Kehadiran</label>
                                            <input type="text" name="tanggal" value="{{ $item->tanggal ?? '' }}" placeholder="Contoh: 12 Juni 2026" class="form-control rounded-pill border-2 px-3 small">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-secondary">Tanggal, Nama & Paraf Kader/Nakes</label>
                                            <input type="text" name="kader_info" value="{{ $item->kader_info ?? '' }}" placeholder="Contoh: 12/06/26 - Bidan Ratna" class="form-control rounded-pill border-2 px-3 small">
                                        </div>
                                        <button type="submit" class="btn btn-secondary rounded-pill w-100 shadow-xs fw-semibold py-2">
                                            <i class="bi bi-save2-fill me-1"></i> Simpan Sesi {{ $i }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ================= TAB 4: Sesi 46 - 60 ================= -->
                <div x-show="activeTab === 4" x-transition>
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-bordered table-hover align-middle small mb-0 text-center">
                            <thead class="table-header-custom-secondary text-white">
                                <tr>
                                    <th style="width: 80px;">Sesi Ke-</th>
                                    <th style="width: 250px;">Tanggal Kehadiran</th>
                                    <th>Tanggal, Nama & Paraf Kader/Nakes</th>
                                    <th style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(46, 60) as $i)
                                    @php $item = $absensi->get($i); @endphp
                                    <form action="{{ route('pengguna.kelas_balita.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kehadiran_ke" value="{{ $i }}">
                                        <tr>
                                            <td class="fw-bold bg-light fs-5 text-secondary">{{ $i }}</td>
                                            <td>
                                                <input type="text" name="tanggal" value="{{ $item->tanggal ?? '' }}" placeholder="Contoh: 12 Juni 2026" class="form-control rounded-pill text-center border-2 px-3">
                                            </td>
                                            <td>
                                                <input type="text" name="kader_info" value="{{ $item->kader_info ?? '' }}" placeholder="Contoh: 12/06/26 - Bidan Ratna (Paraf)" class="form-control rounded-pill border-2 px-3">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-gradient-secondary rounded-pill px-4 py-2 shadow-xs fw-semibold transition-all w-100">
                                                    <i class="bi bi-save2-fill me-1"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View -->
                    <div class="d-lg-none row g-3">
                        @foreach(range(46, 60) as $i)
                            @php $item = $absensi->get($i); @endphp
                            <div class="col-12">
                                <div class="card border-0 bg-light rounded-4 p-3 shadow-xs">
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold fs-6">Sesi {{ $i }}</span>
                                        @if($item)
                                            <span class="text-xs text-success"><i class="bi bi-check-circle-fill me-1"></i> Terisi</span>
                                        @else
                                            <span class="text-xs text-muted"><i class="bi bi-dash-circle me-1"></i> Belum diisi</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('pengguna.kelas_balita.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kehadiran_ke" value="{{ $i }}">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-secondary">Tanggal Kehadiran</label>
                                            <input type="text" name="tanggal" value="{{ $item->tanggal ?? '' }}" placeholder="Contoh: 12 Juni 2026" class="form-control rounded-pill border-2 px-3 small">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-secondary">Tanggal, Nama & Paraf Kader/Nakes</label>
                                            <input type="text" name="kader_info" value="{{ $item->kader_info ?? '' }}" placeholder="Contoh: 12/06/26 - Bidan Ratna" class="form-control rounded-pill border-2 px-3 small">
                                        </div>
                                        <button type="submit" class="btn btn-secondary rounded-pill w-100 shadow-xs fw-semibold py-2">
                                            <i class="bi bi-save2-fill me-1"></i> Simpan Sesi {{ $i }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
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
    .btn-gradient-secondary {
        background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
        border: 0;
        color: white;
    }
    .btn-gradient-primary:hover, .btn-gradient-secondary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    }

    .table-header-custom {
        background: linear-gradient(90deg, var(--accent1), var(--accent2));
    }
    .table-header-custom-secondary {
        background: linear-gradient(90deg, #ec4899, #f43f5e);
    }
    .table-header-custom th, .table-header-custom-secondary th {
        vertical-align: middle;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    .form-control:focus {
        border-color: var(--accent1);
        box-shadow: 0 0 0 0.25rem rgba(109, 40, 217, 0.15);
    }

    .shadow-xs {
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
@endsection
