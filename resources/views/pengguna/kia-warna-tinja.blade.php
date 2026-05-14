@extends('pengguna.master')

@section('title', 'Pemantauan Warna Tinja Bayi - MomSpire')
@section('header_title', 'Warna Tinja Bayi')
@section('header_subtitle', 'Periksa warna tinja bayi Anda setiap hari untuk deteksi dini Atresia Bilier (sumbatan kandung empedu).')

@section('content')
<div class="row g-4">
    <!-- Info Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-megaphone-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">📢 Periksa warna tinja bayi Anda setiap hari!</h6>
                    <p class="mb-0 small opacity-90">Periksa warna tinja bayi setiap hari hingga berumur 4 bulan, dan catat warna tinja saat berumur **2 minggu**, **1 bulan**, dan **2-4 bulan**. Jika menemukan warna tinja lebih pucat mendekati nomor 1 sampai 3, segera bawa bayi ke dokter.</p>
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

    <!-- Main Content: Form & Legend -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pencatatan Berkala Warna Tinja</h5>
                <p class="text-muted small mb-0">Masukkan tanggal pemeriksaan dan pilih nomor warna tinja yang paling sesuai.</p>
            </div>

            <div class="card-body p-4">
                @php $t = $dataKia->warnaTinja; @endphp
                <form action="{{ route('pengguna.warna_tinja.save') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center mb-4">
                            <thead class="table-warning text-dark">
                                <tr>
                                    <th style="width: 25%;" class="py-3">Usia</th>
                                    <th style="width: 25%;" class="py-3">2 Minggu</th>
                                    <th style="width: 25%;" class="py-3">1 Bulan</th>
                                    <th style="width: 25%;" class="py-3">2 - 4 Bulan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold bg-light py-3">Tanggal <span class="d-block text-xs font-normal text-muted">(DD/MM/YYYY)</span></td>
                                    <td>
                                        <input type="text" name="tanggal_2_minggu" value="{{ $t->tanggal_2_minggu ?? '' }}" class="form-control text-center" placeholder="Tgl/Bln/Thn">
                                    </td>
                                    <td>
                                        <input type="text" name="tanggal_1_bulan" value="{{ $t->tanggal_1_bulan ?? '' }}" class="form-control text-center" placeholder="Tgl/Bln/Thn">
                                    </td>
                                    <td>
                                        <input type="text" name="tanggal_2_4_bulan" value="{{ $t->tanggal_2_4_bulan ?? '' }}" class="form-control text-center" placeholder="Tgl/Bln/Thn">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold bg-light py-3">Nomor Warna Tinja <span class="d-block text-xs font-normal text-muted">(Pilih 1 - 7)</span></td>
                                    <td>
                                        <select name="nomor_2_minggu" class="form-select text-center fw-bold">
                                            <option value="">- Pilih -</option>
                                            @foreach(range(1, 7) as $n)
                                                <option value="{{ $n }}" {{ ($t->nomor_2_minggu ?? '') == $n ? 'selected' : '' }}>Nomor {{ $n }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="nomor_1_bulan" class="form-select text-center fw-bold">
                                            <option value="">- Pilih -</option>
                                            @foreach(range(1, 7) as $n)
                                                <option value="{{ $n }}" {{ ($t->nomor_1_bulan ?? '') == $n ? 'selected' : '' }}>Nomor {{ $n }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="nomor_2_4_bulan" class="form-select text-center fw-bold">
                                            <option value="">- Pilih -</option>
                                            @foreach(range(1, 7) as $n)
                                                <option value="{{ $n }}" {{ ($t->nomor_2_4_bulan ?? '') == $n ? 'selected' : '' }}>Nomor {{ $n }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Catatan Warna Tinja
                        </button>
                    </div>
                </form>

                <div class="mt-4 pt-4 border-top">
                    <p class="text-muted small mb-0"><i class="bi bi-info-circle-fill text-warning me-2"></i>**Catatan Penting**: Perhatikan warna tinja bayi sampai berumur 4 bulan. Jika mata bayi masih kuning atau urin berwarna kuning keruh setelah usia 2 minggu, segera bawa bayi ke dokter.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend Sidebar -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1">Skala Warna Tinja</h5>
                <p class="text-muted small mb-0">Panduan warna tinja berdasarkan panduan resmi Buku KIA.</p>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-2">
                    <!-- Warning Colors (1-3) -->
                    <div class="alert alert-danger p-2 mb-2 rounded-3 text-center small fw-bold">
                        🚨 WASPADA ATRESIA BILIER (Warna 1 - 3)
                    </div>
                    
                    <div class="d-flex align-items-center p-2 rounded-3 border bg-light">
                        <div class="color-box me-3 fw-bold text-dark d-flex align-items-center justify-content-center shadow-sm" style="background-color: #f1f0e4; border: 1px solid #ccc;">1</div>
                        <span class="small fw-medium">Pucat / Putih Kapur (Bahaya)</span>
                    </div>
                    <div class="d-flex align-items-center p-2 rounded-3 border bg-light">
                        <div class="color-box me-3 fw-bold text-dark d-flex align-items-center justify-content-center shadow-sm" style="background-color: #e5e0c5; border: 1px solid #ccc;">2</div>
                        <span class="small fw-medium">Kuning Pucat / Krem (Bahaya)</span>
                    </div>
                    <div class="d-flex align-items-center p-2 rounded-3 border bg-light">
                        <div class="color-box me-3 fw-bold text-dark d-flex align-items-center justify-content-center shadow-sm" style="background-color: #ded18e; border: 1px solid #ccc;">3</div>
                        <span class="small fw-medium">Kuning Tanah Pucat (Bahaya)</span>
                    </div>

                    <!-- Normal Colors (4-7) -->
                    <div class="alert alert-success p-2 mb-2 mt-2 rounded-3 text-center small fw-bold">
                        ✅ KONDISI NORMAL (Warna 4 - 7)
                    </div>

                    <div class="d-flex align-items-center p-2 rounded-3 border bg-light">
                        <div class="color-box me-3 fw-bold text-dark d-flex align-items-center justify-content-center shadow-sm" style="background-color: #fcd34d;">4</div>
                        <span class="small fw-medium">Kuning Cerah (Normal)</span>
                    </div>
                    <div class="d-flex align-items-center p-2 rounded-3 border bg-light">
                        <div class="color-box me-3 fw-bold text-dark d-flex align-items-center justify-content-center shadow-sm" style="background-color: #d97706;">5</div>
                        <span class="small fw-medium">Kuning Keemasan (Normal)</span>
                    </div>
                    <div class="d-flex align-items-center p-2 rounded-3 border bg-light">
                        <div class="color-box me-3 fw-bold text-white d-flex align-items-center justify-content-center shadow-sm" style="background-color: #b45309;">6</div>
                        <span class="small fw-medium">Cokelat Kekuningan (Normal)</span>
                    </div>
                    <div class="d-flex align-items-center p-2 rounded-3 border bg-light">
                        <div class="color-box me-3 fw-bold text-white d-flex align-items-center justify-content-center shadow-sm" style="background-color: #78350f;">7</div>
                        <span class="small fw-medium">Cokelat Tua / Gelap (Normal)</span>
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
        color: #fff;
    }
    .btn-gradient-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: #fff;
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
    }
    .color-box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        font-size: 16px;
    }
</style>
@endsection
