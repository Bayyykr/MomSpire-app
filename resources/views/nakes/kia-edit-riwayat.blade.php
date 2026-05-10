@extends($role . '.master')

@section('title', 'Lengkapi Riwayat Kesehatan - MomSpire')

@section('header_title', 'Riwayat Singkat Kesehatan Ibu')
@section('header_subtitle', 'Lengkapi data kesehatan ibu untuk dicetak pada Buku KIA Halaman 2.')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card role-card">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle p-2 me-3">
                        <i class="bi bi-person-heart fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Data Ibu: {{ $dataKia->ibu->nama ?? 'N/A' }}</h5>
                        <p class="text-muted small mb-0">Hanya Tenaga Kesehatan (Bidan/Dokter) yang dapat mengisi bagian ini.</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route($role . '.kia.save_riwayat', $dataKia->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="usia_ibu" class="form-label fw-semibold">Usia Ibu</label>
                        <div class="input-group">
                            <input type="number" name="usia_ibu" id="usia_ibu" class="form-control" value="{{ $dataKia->riwayat->usia_ibu ?? '' }}" placeholder="Contoh: 28">
                            <span class="input-group-text">Tahun</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="kehamilan_ke" class="form-label fw-semibold">Kehamilan ke-</label>
                        <input type="number" name="kehamilan_ke" id="kehamilan_ke" class="form-control" value="{{ $dataKia->riwayat->kehamilan_ke ?? '' }}" placeholder="Contoh: 1">
                    </div>

                    <div class="mb-4">
                        <label for="jumlah_anak_lahir_hidup" class="form-label fw-semibold">Jumlah Anak Lahir Hidup</label>
                        <input type="number" name="jumlah_anak_lahir_hidup" id="jumlah_anak_lahir_hidup" class="form-control" value="{{ $dataKia->riwayat->jumlah_anak_lahir_hidup ?? '' }}" placeholder="Contoh: 0">
                    </div>

                    <div class="mb-4">
                        <label for="riwayat_keguguran" class="form-label fw-semibold">Riwayat Keguguran</label>
                        <input type="number" name="riwayat_keguguran" id="riwayat_keguguran" class="form-control" value="{{ $dataKia->riwayat->riwayat_keguguran ?? '' }}" placeholder="Contoh: 0">
                    </div>

                    <div class="mb-4">
                        <label for="riwayat_penyakit_ibu" class="form-label fw-semibold">Riwayat Penyakit Ibu</label>
                        <textarea name="riwayat_penyakit_ibu" id="riwayat_penyakit_ibu" class="form-control" rows="4" placeholder="Sebutkan riwayat penyakit jika ada (misal: Hipertensi, Diabetes, dll)">{{ $dataKia->riwayat->riwayat_penyakit_ibu ?? '' }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <a href="{{ route($role . '.kia') }}" class="btn btn-light px-4">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success px-5">
                            <i class="bi bi-check2-circle me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
