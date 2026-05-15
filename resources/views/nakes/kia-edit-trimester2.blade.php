@extends($role . '.master')

@section('title', 'Halaman 53 - Trimester 2 - MomSpire')

@section('content')
    <div class="p-3 p-sm-4 p-lg-5 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12">
                <h1 class="display-6 fw-bold mb-2">Halaman 53: Trimester 2</h1>
                <p class="lead text-muted mb-0">Skrining Preeklampsia, Diabetes, dan Catatan Pelayanan untuk Ibu
                    <strong>{{ $dataKia->ibu->nama ?? 'Unknown' }}</strong></p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route($role . '.kia.save_trimester2', $dataKia->id) }}" method="POST" id="trimester2Form">
                @csrf

                <input type="hidden" name="deleted_catatan" id="deleted_catatan" value="">

                <h4 class="fw-bold text-primary mb-4 border-bottom pb-2">Bagian Kiri (Halaman 53) - Skrining Preeklampsia &
                    Diabetes</h4>

                <div class="row g-4 mb-5">
                    <div class="col-12">
                        <h5 class="fw-bold">1. Skrining Preeklampsia (Umur kehamilan < 20 minggu)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kriteria</th>
                                                <th class="text-center" style="width: 150px;">Risiko Sedang<br><small
                                                        class="fw-normal">(Tampil di Baris)</small></th>
                                                <th class="text-center" style="width: 150px;">Risiko Tinggi<br><small
                                                        class="fw-normal">(Tampil di Bawah)</small></th>
                                                <th class="text-center" style="width: 100px;">Normal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $grup1 = [
                                                    'multipara_pasangan_baru' => 'Multipara Dengan Kehamilan oleh Pasangan Baru',
                                                    'teknologi_reproduksi' => 'Kehamilan Dengan Teknologi Reproduksi Berbantu',
                                                    'umur_35' => 'Umur >= 35 Tahun',
                                                    'nullipara' => 'Nullipara',
                                                    'jarak_10' => 'Multipara dengan Jarak Kehamilan Sebelumnya > 10 Tahun',
                                                    'riwayat_ibu_saudara' => 'Riwayat Preeklampsia pada Ibu atau Saudara Perempuan',
                                                    'obesitas' => 'Obesitas Sebelum Hamil (IMT > 30 kg/m2)',
                                                ];
                                                $grup2 = [
                                                    'riwayat_preeklampsia_sebelumnya' => 'Multipara Dengan Riwayat Preeklampsia Sebelumnya',
                                                    'kehamilan_multipel' => 'Kehamilan Multipel',
                                                    'diabetes' => 'Diabetes dalam Kehamilan',
                                                    'hipertensi' => 'Hipertensi Kronik',
                                                    'ginjal' => 'Penyakit Ginjal',
                                                    'autoimun' => 'Penyakit Autoimun, SLE',
                                                    'aps' => 'Anti Phospholipid Syndrome',
                                                ];
                                                $grup3 = [
                                                    'map_90' => 'Mean Arterial Pressure > 90 mmHg',
                                                    'proteinuria' => 'Proteinuria (Urin Celup +1)',
                                                ];
                                                $skriningData = $pemeriksaan->skrining_preeklampsia ?? [];
                                            @endphp

                                            <!-- Grup 1 -->
                                            <tr class="table-light">
                                                <td colspan="4" class="fw-bold small text-uppercase">Anamnesis - Kelompok
                                                    Risiko Sedang</td>
                                            </tr>
                                            @foreach($grup1 as $key => $label)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Sedang" {{ ($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Tinggi" {{ ($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="" {{ empty($skriningData[$key]) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <!-- Grup 2 -->
                                            <tr style="background-color: #fce4ec;">
                                                <td colspan="4" class="fw-bold small text-uppercase">Anamnesis - Kelompok
                                                    Risiko Tinggi</td>
                                            </tr>
                                            @foreach($grup2 as $key => $label)
                                                <tr style="background-color: #fffafa;">
                                                    <td>{{ $label }}</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Sedang" {{ ($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Tinggi" {{ ($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="" {{ empty($skriningData[$key]) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <!-- Grup 3 -->
                                            <tr class="table-secondary">
                                                <td colspan="4" class="fw-bold small text-uppercase">Pemeriksaan Fisik</td>
                                            </tr>
                                            @foreach($grup3 as $key => $label)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Sedang" {{ ($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Tinggi" {{ ($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="" {{ empty($skriningData[$key]) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Kesimpulan Skrining Preeklampsia</label>
                        <input type="text" class="form-control" name="kesimpulan_preeklampsia"
                            value="{{ $pemeriksaan->kesimpulan_preeklampsia }}"
                            placeholder="Contoh: Perlu Rujukan / Normal">
                    </div>

                    <div class="col-12 mt-5">
                        <h5 class="fw-bold">2. Skrining Diabetes Melitus Gestasional (24-28 Minggu)</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gula Darah Puasa (mg/dL)</label>
                        <input type="text" class="form-control" name="lab_gula_darah_puasa"
                            value="{{ $pemeriksaan->lab_gula_darah_puasa }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rencana Tindak Lanjut (Gula Darah Puasa)</label>
                        <input type="text" class="form-control" name="tindak_lanjut_puasa"
                            value="{{ $pemeriksaan->tindak_lanjut_puasa }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gula Darah 2 Jam Post Prandial (mg/dL)</label>
                        <input type="text" class="form-control" name="lab_gula_darah_2jam"
                            value="{{ $pemeriksaan->lab_gula_darah_2jam }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rencana Tindak Lanjut (Gula Darah 2 Jam)</label>
                        <input type="text" class="form-control" name="tindak_lanjut_2jam"
                            value="{{ $pemeriksaan->tindak_lanjut_2jam }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Periksa</label>
                        <input type="date" class="form-control" name="tgl_periksa_diabetes"
                            value="{{ $pemeriksaan->tgl_periksa_diabetes }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Dokter Pemeriksa</label>
                        <input type="text" class="form-control" name="nama_dokter_diabetes"
                            value="{{ $pemeriksaan->nama_dokter_diabetes }}">
                    </div>
                </div>

                <h4 class="fw-bold text-primary mb-4 border-bottom pb-2 mt-5">Bagian Kanan (Halaman 53) - Catatan Pelayanan
                    Trimester 2</h4>

                <div id="catatan-container">
                    @forelse($catatan as $index => $cat)
                        <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="{{ $index }}">
                            <input type="hidden" name="catatan[{{ $index }}][id]" value="{{ $cat->id }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold m-0 text-secondary">Catatan #<span
                                        class="catatan-counter">{{ $index + 1 }}</span></h6>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan"
                                    data-id="{{ $cat->id }}">Hapus Baris</button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Periksa</label>
                                    <input type="date" class="form-control" name="catatan[{{ $index }}][tanggal_periksa]"
                                        value="{{ $cat->tanggal_periksa }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="catatan[{{ $index }}][tanggal_kembali]"
                                        value="{{ $cat->tanggal_kembali }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                                    <textarea class="form-control" name="catatan[{{ $index }}][catatan]"
                                        rows="3">{{ $cat->catatan }}</textarea>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter">1</span></h6>
                                <button type="button"
                                    class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan d-none">Hapus
                                    Baris</button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Periksa</label>
                                    <input type="date" class="form-control" name="catatan[0][tanggal_periksa]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="catatan[0][tanggal_kembali]">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                                    <textarea class="form-control" name="catatan[0][catatan]" rows="3"></textarea>
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
        document.addEventListener('DOMContentLoaded', function () {
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

            container.addEventListener('click', function (e) {
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

            btnAdd.addEventListener('click', function () {
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