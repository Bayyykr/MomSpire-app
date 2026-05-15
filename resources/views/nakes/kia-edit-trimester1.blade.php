@extends($role . '.master')

@section('title', 'Halaman 52-53 - MomSpire')

@section('content')
<div class="p-3 p-sm-4 p-lg-5 mb-4">
    <div class="row align-items-center g-3">
        <div class="col-12">
            <h1 class="display-6 fw-bold mb-2">Halaman 52-53: Trimester 1</h1>
            <p class="lead text-muted mb-0">Input Hasil USG, Pemeriksaan Lab, Skrining, dan Catatan Pelayanan untuk Ibu <strong>{{ $dataKia->ibu->nama ?? 'Unknown' }}</strong></p>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 p-lg-5">
        <form action="{{ route($role . '.kia.save_trimester1', $dataKia->id) }}" method="POST" enctype="multipart/form-data" id="trimester1Form">
            @csrf
            
            <input type="hidden" name="deleted_catatan" id="deleted_catatan" value="">

            <h4 class="fw-bold text-primary mb-4 border-bottom pb-2">Bagian Kiri (Halaman 52)</h4>
            
            <div class="row g-4 mb-5">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Hasil USG (Gambar)</label>
                    <input type="file" class="form-control" name="gambar_usg" accept="image/jpeg,image/png,image/jpg">
                    @if($pemeriksaan->gambar_usg)
                    <div class="mt-2">
                        <span class="badge bg-success">Sudah ada gambar</span>
                        <img src="{{ asset($pemeriksaan->gambar_usg) }}" alt="USG Image" class="img-thumbnail mt-2" style="max-height: 100px;">
                    </div>
                    @endif
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar. (Max 2MB)</div>
                </div>

                <div class="col-12">
                    <h5 class="fw-bold mt-4">Pemeriksaan Laboratorium (Halaman 52)</h5>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Periksa Lab</label>
                    <input type="date" class="form-control" name="tgl_periksa_lab" value="{{ $pemeriksaan->tgl_periksa_lab }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hemoglobin (g/dL)</label>
                    <input type="text" class="form-control" name="lab_hemoglobin" value="{{ $pemeriksaan->lab_hemoglobin }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Golongan Darah & Rhesus</label>
                    <input type="text" class="form-control" name="lab_gol_darah" value="{{ $pemeriksaan->lab_gol_darah }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gula Darah Sewaktu (Mg/dL)</label>
                    <input type="text" class="form-control" name="lab_gula_darah" value="{{ $pemeriksaan->lab_gula_darah }}">
                </div>
                
                <div class="col-12">
                    <h6 class="fw-bold mt-2">Tripel Eliminasi</h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label">H</label>
                    <select class="form-select" name="lab_tripel_h">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" {{ $pemeriksaan->lab_tripel_h == 'Reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="Non reaktif" {{ $pemeriksaan->lab_tripel_h == 'Non reaktif' ? 'selected' : '' }}>Non reaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">S</label>
                    <select class="form-select" name="lab_tripel_s">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" {{ $pemeriksaan->lab_tripel_s == 'Reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="Non reaktif" {{ $pemeriksaan->lab_tripel_s == 'Non reaktif' ? 'selected' : '' }}>Non reaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hepatitis B</label>
                    <select class="form-select" name="lab_tripel_hep_b">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" {{ $pemeriksaan->lab_tripel_hep_b == 'Reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="Non reaktif" {{ $pemeriksaan->lab_tripel_hep_b == 'Non reaktif' ? 'selected' : '' }}>Non reaktif</option>
                    </select>
                </div>

                <div class="col-12">
                    <h5 class="fw-bold mt-4">Skrining Kesehatan Jiwa</h5>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Skrining</label>
                    <input type="date" class="form-control" name="tgl_skrining_jiwa" value="{{ $pemeriksaan->tgl_skrining_jiwa }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Skrining Kesehatan Jiwa</label>
                    <select class="form-select" name="skrining_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Ya" {{ $pemeriksaan->skrining_jiwa == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ $pemeriksaan->skrining_jiwa == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tindak Lanjut</label>
                    <select class="form-select" name="tindak_lanjut_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Edukasi" {{ $pemeriksaan->tindak_lanjut_jiwa == 'Edukasi' ? 'selected' : '' }}>Edukasi</option>
                        <option value="Konseling" {{ $pemeriksaan->tindak_lanjut_jiwa == 'Konseling' ? 'selected' : '' }}>Konseling</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perlu Rujukan</label>
                    <select class="form-select" name="rujukan_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Ya" {{ $pemeriksaan->rujukan_jiwa == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ $pemeriksaan->rujukan_jiwa == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-bold">Kesimpulan</label>
                    <input type="text" class="form-control" name="kesimpulan" value="{{ $pemeriksaan->kesimpulan }}">
                </div>
                <div class="col-md-6 mt-4">
                    <label class="form-label fw-bold">Rekomendasi</label>
                    <input type="text" class="form-control" name="rekomendasi" value="{{ $pemeriksaan->rekomendasi }}">
                </div>
            </div>

            <h4 class="fw-bold text-primary mb-4 border-bottom pb-2 mt-5">Bagian Kanan (Halaman 53) - Catatan Pelayanan</h4>
            
            <div id="catatan-container">
                @forelse($catatan as $index => $cat)
                <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="{{ $index }}">
                    <input type="hidden" name="catatan[{{ $index }}][id]" value="{{ $cat->id }}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter">{{ $index + 1 }}</span></h6>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan" data-id="{{ $cat->id }}">Hapus Baris</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Periksa</label>
                            <input type="date" class="form-control" name="catatan[{{ $index }}][tanggal_periksa]" value="{{ $cat->tanggal_periksa }}">
                            <small class="text-muted">Juga digunakan untuk area Stamp & Paraf</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="catatan[{{ $index }}][tanggal_kembali]" value="{{ $cat->tanggal_kembali }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                            <textarea class="form-control" name="catatan[{{ $index }}][catatan]" rows="3">{{ $cat->catatan }}</textarea>
                            <small class="text-muted">Tekan Enter untuk baris baru. Tercetak ke bawah di PDF.</small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter">1</span></h6>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan d-none">Hapus Baris</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Periksa</label>
                            <input type="date" class="form-control" name="catatan[0][tanggal_periksa]">
                            <small class="text-muted">Juga digunakan untuk area Stamp & Paraf</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="catatan[0][tanggal_kembali]">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                            <textarea class="form-control" name="catatan[0][catatan]" rows="3"></textarea>
                            <small class="text-muted">Tekan Enter untuk baris baru. Tercetak ke bawah di PDF.</small>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mb-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="btnAddCatatan">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Catatan
                </button>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-5">
                <a href="{{ route($role . '.kia') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                <button type="submit" class="btn btn-success px-4 rounded-pill">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let catIndex = {{ count($catatan) > 0 ? count($catatan) : 1 }};
    const container = document.getElementById('catatan-container');
    const btnAdd = document.getElementById('btnAddCatatan');
    const deletedInput = document.getElementById('deleted_catatan');

    function updateCounters() {
        const items = container.querySelectorAll('.catatan-item');
        items.forEach((item, idx) => {
            item.querySelector('.catatan-counter').textContent = idx + 1;
            const btnRemove = item.querySelector('.btn-remove-catatan');
            if (items.length === 1) {
                btnRemove.classList.add('d-none');
            } else {
                btnRemove.classList.remove('d-none');
            }
        });
    }

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-catatan')) {
            const item = e.target.closest('.catatan-item');
            const id = e.target.getAttribute('data-id');
            
            if (id) {
                const currentDeleted = deletedInput.value ? deletedInput.value.split(',') : [];
                currentDeleted.push(id);
                deletedInput.value = currentDeleted.join(',');
            }
            
            item.remove();
            updateCounters();
        }
    });

    btnAdd.addEventListener('click', function() {
        const template = `
            <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="${catIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter"></span></h6>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan">Hapus Baris</button>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Periksa</label>
                        <input type="date" class="form-control" name="catatan[${catIndex}][tanggal_periksa]">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" class="form-control" name="catatan[${catIndex}][tanggal_kembali]">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                        <textarea class="form-control" name="catatan[${catIndex}][catatan]" rows="3"></textarea>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        catIndex++;
        updateCounters();
    });

    updateCounters();
});
</script>
@endsection
