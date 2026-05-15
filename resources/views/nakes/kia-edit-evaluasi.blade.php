@extends($role . '.master')

@section('title', 'Evaluasi Kesehatan Ibu Hamil - MomSpire')

@section('header_title', 'Evaluasi Kesehatan Ibu Hamil')
@section('header_subtitle', 'Lengkapi data evaluasi kesehatan ibu hamil untuk dicetak pada Buku KIA Halaman 51.')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card role-card">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-white rounded-circle p-2 me-3">
                                <i class="bi bi-clipboard2-pulse fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Data Ibu: {{ $dataKia->ibu->nama ?? 'N/A' }}</h5>
                                <p class="text-muted small mb-0">Diisi oleh Dokter</p>
                            </div>
                        </div>
                        <a href="{{ route($role . '.kia') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">

                    <form action="{{ route($role . '.kia.save_evaluasi', $dataKia->id) }}" method="POST">
                        @csrf

                        <!-- Informasi Dasar -->
                        <div class="card bg-light border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">Nama Dokter</label>
                                        <input type="text" name="nama_dokter" class="form-control"
                                            value="{{ $evaluasi->nama_dokter ?? auth()->user()->name }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">Tanggal Periksa</label>
                                        <input type="date" name="tanggal_periksa" class="form-control"
                                            value="{{ $evaluasi->tanggal_periksa ?? date('Y-m-d') }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">Fasilitas Kesehatan</label>
                                        <input type="text" name="fasilitas_kesehatan" class="form-control"
                                            value="{{ $evaluasi->fasilitas_kesehatan ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- Kondisi Kesehatan Ibu & Status Imunisasi -->
                            <div class="col-12 col-md-6">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                    <h6 class="fw-bold mb-4 border-bottom pb-2 text-warning">Kondisi Kesehatan Ibu</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-6 col-md-4">
                                            <label class="form-label small fw-semibold">TB (cm)</label>
                                            <input type="number" step="0.1" name="tb" class="form-control" value="{{ $evaluasi->tb ?? '' }}">
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <label class="form-label small fw-semibold">BB (kg)</label>
                                            <input type="number" step="0.1" name="bb" class="form-control" value="{{ $evaluasi->bb ?? '' }}">
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label small fw-semibold">LiLa (cm)</label>
                                            <input type="number" step="0.1" name="lila" class="form-control" value="{{ $evaluasi->lila ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="table-responsive mb-4">
                                        <label class="form-label small fw-semibold d-block mb-2 text-muted">Isian Baris LiLa (4 Kolom IMT)</label>
                                        <table class="table table-bordered table-sm text-center align-middle">
                                            <thead class="table-light">
                                                <tr class="bg-dark text-white">
                                                    <th colspan="4" class="py-1 small">IMT</th>
                                                </tr>
                                                <tr class="small">
                                                    <th style="width: 25%">Kurus</th>
                                                    <th style="width: 25%">Normal</th>
                                                    <th style="width: 25%">Gemuk</th>
                                                    <th style="width: 25%">Obesitas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="p-1"><input type="number" step="0.1" name="lila_kurus" class="form-control form-control-sm border-0 text-center" value="{{ $evaluasi->lila_kurus ?? '' }}"></td>
                                                    <td class="p-1"><input type="number" step="0.1" name="lila_normal" class="form-control form-control-sm border-0 text-center" value="{{ $evaluasi->lila_normal ?? '' }}"></td>
                                                    <td class="p-1"><input type="number" step="0.1" name="lila_gemuk" class="form-control form-control-sm border-0 text-center" value="{{ $evaluasi->lila_gemuk ?? '' }}"></td>
                                                    <td class="p-1"><input type="number" step="0.1" name="lila_obesitas" class="form-control form-control-sm border-0 text-center" value="{{ $evaluasi->lila_obesitas ?? '' }}"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning mt-4">Status Imunisasi TD
                                            (Tetanus Diphtheria)</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>TT</th>
                                                        <th>Perlindungan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_1" value="1" {{ ($evaluasi->imunisasi_tt_1 ?? false) ? 'checked' : '' }}>
                                                            Awal</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_2" value="1" {{ ($evaluasi->imunisasi_tt_2 ?? false) ? 'checked' : '' }}> 3
                                                            Tahun</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_3" value="1" {{ ($evaluasi->imunisasi_tt_3 ?? false) ? 'checked' : '' }}> 5
                                                            Tahun</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_4" value="1" {{ ($evaluasi->imunisasi_tt_4 ?? false) ? 'checked' : '' }}> 10
                                                            Tahun</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_5" value="1" {{ ($evaluasi->imunisasi_tt_5 ?? false) ? 'checked' : '' }}> >
                                                            25 Tahun</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pemeriksaan Khusus -->
                            <div class="col-12 col-md-6">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-4 border-bottom pb-2 text-warning">Pemeriksaan Khusus
                                            (Inspeksi/Inspekulo)</h6>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Porsio</label>
                                                <select name="inspeksi_porsio" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="Normal" {{ ($evaluasi->inspeksi_porsio ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="Tidak normal" {{ ($evaluasi->inspeksi_porsio ?? '') == 'Tidak normal' ? 'selected' : '' }}>Tidak normal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Uretra</label>
                                                <select name="inspeksi_uretra" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="Normal" {{ ($evaluasi->inspeksi_uretra ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="Tidak normal" {{ ($evaluasi->inspeksi_uretra ?? '') == 'Tidak normal' ? 'selected' : '' }}>Tidak normal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Vagina</label>
                                                <select name="inspeksi_vagina" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="Normal" {{ ($evaluasi->inspeksi_vagina ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="Tidak normal" {{ ($evaluasi->inspeksi_vagina ?? '') == 'Tidak normal' ? 'selected' : '' }}>Tidak normal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Vulva</label>
                                                <select name="inspeksi_vulva" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="Normal" {{ ($evaluasi->inspeksi_vulva ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="Tidak normal" {{ ($evaluasi->inspeksi_vulva ?? '') == 'Tidak normal' ? 'selected' : '' }}>Tidak normal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Fluksus</label>
                                                <select name="inspeksi_fluksus" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="+" {{ ($evaluasi->inspeksi_fluksus ?? '') == '+' ? 'selected' : '' }}>+</option>
                                                    <option value="-" {{ ($evaluasi->inspeksi_fluksus ?? '') == '-' ? 'selected' : '' }}>-</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Fluor</label>
                                                <select name="inspeksi_fluor" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="+" {{ ($evaluasi->inspeksi_fluor ?? '') == '+' ? 'selected' : '' }}>+</option>
                                                    <option value="-" {{ ($evaluasi->inspeksi_fluor ?? '') == '-' ? 'selected' : '' }}>-</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Riwayat Checklist -->
                            <div class="col-12 col-md-4">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning">Riwayat Kesehatan Ibu
                                            Sekarang</h6>
                                        @php $rkes = $evaluasi->riwayat_kesehatan_ibu ?? []; @endphp
                                        <div class="row g-2">
                                            @foreach(['Alergi', 'Autoimun', 'Hepatitis B', 'Jantung', 'Sifilis', 'Asma', 'Diabetes', 'Hipertensi', 'Jiwa', 'TB'] as $item)
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="riwayat_kesehatan_ibu[]" value="{{ $item }}" {{ in_array($item, $rkes) ? 'checked' : '' }}>
                                                        <label class="form-check-label small">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" name="riwayat_kesehatan_ibu_lainnya"
                                                class="form-control form-control-sm" placeholder="Lainnya..."
                                                value="{{ $evaluasi->riwayat_kesehatan_ibu_lainnya ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning">Riwayat Penyakit Keluarga
                                        </h6>
                                        @php $rkel = $evaluasi->riwayat_penyakit_keluarga ?? []; @endphp
                                        <div class="row g-2">
                                            @foreach(['Alergi', 'Autoimun', 'Hepatitis B', 'Jantung', 'Sifilis', 'Asma', 'Diabetes', 'Hipertensi', 'Jiwa', 'TB'] as $item)
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="riwayat_penyakit_keluarga[]" value="{{ $item }}" {{ in_array($item, $rkel) ? 'checked' : '' }}>
                                                        <label class="form-check-label small">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" name="riwayat_penyakit_keluarga_lainnya"
                                                class="form-control form-control-sm" placeholder="Lainnya..."
                                                value="{{ $evaluasi->riwayat_penyakit_keluarga_lainnya ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning">Riwayat Perilaku Berisiko
                                            (1 Bln Sblm Hamil)</h6>
                                        @php $rper = $evaluasi->riwayat_perilaku ?? []; @endphp
                                        <div class="row g-2">
                                            @foreach(['Aktivitas fisik kurang', 'Kosmetik berbahaya', 'Obat Teratogenik', 'Alkohol', 'Merokok', 'Pola makan berisiko'] as $item)
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="riwayat_perilaku[]" value="{{ $item }}" {{ in_array($item, $rper) ? 'checked' : '' }}>
                                                        <label class="form-check-label small">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" name="riwayat_perilaku_lainnya"
                                                class="form-control form-control-sm" placeholder="Lainnya..."
                                                value="{{ $evaluasi->riwayat_perilaku_lainnya ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Riwayat Kehamilan -->
                            <div class="col-12">
                                <div class="card bg-light border-0 shadow-sm rounded-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning">Riwayat Kehamilan dan
                                            Proses Melahirkan</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered bg-white">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tahun</th>
                                                        <th>BB (gram)</th>
                                                        <th>Proses Melahirkan</th>
                                                        <th>Penolong</th>
                                                        <th>Masalah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $rk = $evaluasi->riwayat_kehamilan ?? []; @endphp
                                                    @for($i = 0; $i < 3; $i++)
                                                        <tr>
                                                            <td class="text-center align-middle">{{ $i + 1 }}</td>
                                                            <td><input type="text" name="riwayat_kehamilan[{{ $i }}][tahun]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['tahun'] ?? '' }}"></td>
                                                            <td><input type="number" name="riwayat_kehamilan[{{ $i }}][bb]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['bb'] ?? '' }}"></td>
                                                            <td><input type="text" name="riwayat_kehamilan[{{ $i }}][proses]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['proses'] ?? '' }}"></td>
                                                            <td><input type="text" name="riwayat_kehamilan[{{ $i }}][penolong]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['penolong'] ?? '' }}"></td>
                                                            <td><input type="text" name="riwayat_kehamilan[{{ $i }}][masalah]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['masalah'] ?? '' }}"></td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pemeriksaan Dokter Trimester 1 (Kanan) -->
                            <div class="col-12 mt-5">
                                <h5 class="fw-bold text-primary mb-3">Pemeriksaan Dokter Trimester 1 (Sisi Kanan Halaman 51)
                                </h5>
                                <div class="card bg-light border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">Keadaan Umum</h6>
                                        <div class="row g-3">
                                            @php
                                                $keadaanFields = [
                                                    ['name' => 'konjungtiva', 'label' => 'Konjungtiva', 'opt1' => 'Anemia', 'opt2' => 'Tidak Anemia'],
                                                    ['name' => 'sklera', 'label' => 'Sklera', 'opt1' => 'Ikterik', 'opt2' => 'Tidak Ikterik'],
                                                    ['name' => 'kulit', 'label' => 'Kulit', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'leher', 'label' => 'Leher', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'gigi_mulut', 'label' => 'Gigi Mulut', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'tht', 'label' => 'THT', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'dada_jantung', 'label' => 'Dada (Jantung)', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'dada_paru', 'label' => 'Dada (Paru)', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'perut', 'label' => 'Perut', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'tungkai', 'label' => 'Tungkai', 'opt1' => 'Normal', 'opt2' => 'Tidak normal']
                                                ];
                                            @endphp
                                            @foreach($keadaanFields as $kf)
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold">{{ $kf['label'] }}</label>
                                                    <div class="input-group input-group-sm">
                                                        <select class="form-select" style="max-width: 150px;"
                                                            name="{{ $kf['name'] }}">
                                                            <option value="">Pilih...</option>
                                                            <option value="{{ $kf['opt1'] }}" {{ ($pemeriksaan->{$kf['name']} ?? '') == $kf['opt1'] ? 'selected' : '' }}>{{ $kf['opt1'] }}</option>
                                                            <option value="{{ $kf['opt2'] }}" {{ ($pemeriksaan->{$kf['name']} ?? '') == $kf['opt2'] ? 'selected' : '' }}>{{ $kf['opt2'] }}</option>
                                                        </select>
                                                        <input type="text" class="form-control"
                                                            name="keterangan_{{ $kf['name'] }}"
                                                            value="{{ $pemeriksaan->{'keterangan_' . $kf['name']} ?? '' }}"
                                                            placeholder="Keterangan...">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card bg-light border-0 shadow-sm rounded-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">USG Trimester 1</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">HPHT</label>
                                                <input type="date" class="form-control form-control-sm" name="hpht"
                                                    value="{{ $pemeriksaan->hpht ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Keteraturan Haid</label>
                                                <select class="form-select form-select-sm" name="keteraturan_haid">
                                                    <option value="">Pilih...</option>
                                                    <option value="Teratur" {{ ($pemeriksaan->keteraturan_haid ?? '') == 'Teratur' ? 'selected' : '' }}>Teratur</option>
                                                    <option value="Tidak Teratur" {{ ($pemeriksaan->keteraturan_haid ?? '') == 'Tidak Teratur' ? 'selected' : '' }}>Tidak Teratur</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Umur Kehamilan (HPHT) -
                                                    Minggu</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="usia_kehamilan_hpht"
                                                    value="{{ $pemeriksaan->usia_kehamilan_hpht ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">HPL berdasar HPHT</label>
                                                <input type="date" class="form-control form-control-sm" name="hpl_hpht"
                                                    value="{{ $pemeriksaan->hpl_hpht ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Umur Kehamilan (USG) -
                                                    Minggu</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="usia_kehamilan_usg"
                                                    value="{{ $pemeriksaan->usia_kehamilan_usg ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">HPL berdasar USG</label>
                                                <input type="date" class="form-control form-control-sm" name="hpl_usg"
                                                    value="{{ $pemeriksaan->hpl_usg ?? '' }}">
                                            </div>

                                            <div class="col-md-4 mt-4">
                                                <label class="form-label small fw-semibold">Jumlah GS</label>
                                                <select class="form-select form-select-sm" name="jumlah_gs">
                                                    <option value="">Pilih...</option>
                                                    <option value="Tunggal" {{ ($pemeriksaan->jumlah_gs ?? '') == 'Tunggal' ? 'selected' : '' }}>Tunggal</option>
                                                    <option value="Kembar" {{ ($pemeriksaan->jumlah_gs ?? '') == 'Kembar' ? 'selected' : '' }}>Kembar</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8 mt-4">
                                                <label class="form-label small fw-semibold">Diameter GS</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.1" class="form-control"
                                                        name="diameter_gs_cm"
                                                        value="{{ $pemeriksaan->diameter_gs_cm ?? '' }}" placeholder="cm">
                                                    <span class="input-group-text">Sesuai umur kehamilan</span>
                                                    <input type="number" class="form-control" name="diameter_gs_minggu"
                                                        value="{{ $pemeriksaan->diameter_gs_minggu ?? '' }}"
                                                        placeholder="Minggu">
                                                    <span class="input-group-text">+</span>
                                                    <input type="number" class="form-control" name="diameter_gs_hari"
                                                        value="{{ $pemeriksaan->diameter_gs_hari ?? '' }}"
                                                        placeholder="Hari">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Jumlah Bayi</label>
                                                <select class="form-select form-select-sm" name="jumlah_bayi">
                                                    <option value="">Pilih...</option>
                                                    <option value="Tunggal" {{ ($pemeriksaan->jumlah_bayi ?? '') == 'Tunggal' ? 'selected' : '' }}>Tunggal</option>
                                                    <option value="Kembar" {{ ($pemeriksaan->jumlah_bayi ?? '') == 'Kembar' ? 'selected' : '' }}>Kembar</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label small fw-semibold">CRL</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.1" class="form-control" name="crl_cm"
                                                        value="{{ $pemeriksaan->crl_cm ?? '' }}" placeholder="cm">
                                                    <span class="input-group-text">Sesuai umur kehamilan</span>
                                                    <input type="number" class="form-control" name="crl_minggu"
                                                        value="{{ $pemeriksaan->crl_minggu ?? '' }}" placeholder="Minggu">
                                                    <span class="input-group-text">+</span>
                                                    <input type="number" class="form-control" name="crl_hari"
                                                        value="{{ $pemeriksaan->crl_hari ?? '' }}" placeholder="Hari">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Letak Produk Kehamilan</label>
                                                <select class="form-select form-select-sm" name="letak_produk_kehamilan">
                                                    <option value="">Pilih...</option>
                                                    <option value="Intrauterin" {{ ($pemeriksaan->letak_produk_kehamilan ?? '') == 'Intrauterin' ? 'selected' : '' }}>Intrauterin</option>
                                                    <option value="Ekstrauterin" {{ ($pemeriksaan->letak_produk_kehamilan ?? '') == 'Ekstrauterin' ? 'selected' : '' }}>Ekstrauterin</option>
                                                    <option value="Tidak dapat ditentukan" {{ ($pemeriksaan->letak_produk_kehamilan ?? '') == 'Tidak dapat ditentukan' ? 'selected' : '' }}>Tidak dapat ditentukan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Pulsasi Jantung</label>
                                                <select class="form-select form-select-sm" name="pulsasi_jantung">
                                                    <option value="">Pilih...</option>
                                                    <option value="Tampak" {{ ($pemeriksaan->pulsasi_jantung ?? '') == 'Tampak' ? 'selected' : '' }}>Tampak</option>
                                                    <option value="Tidak tampak" {{ ($pemeriksaan->pulsasi_jantung ?? '') == 'Tidak tampak' ? 'selected' : '' }}>Tidak tampak</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Kecurigaan Temuan
                                                    Abnormal</label>
                                                <div class="input-group input-group-sm">
                                                    <select class="form-select" style="max-width: 100px;"
                                                        name="kecurigaan_temuan_abnormal">
                                                        <option value="">Pilih...</option>
                                                        <option value="Ya" {{ ($pemeriksaan->kecurigaan_temuan_abnormal ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                                        <option value="Tidak" {{ ($pemeriksaan->kecurigaan_temuan_abnormal ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                                    </select>
                                                    <input type="text" class="form-control"
                                                        name="kecurigaan_temuan_abnormal_sebutkan"
                                                        value="{{ $pemeriksaan->kecurigaan_temuan_abnormal_sebutkan ?? '' }}"
                                                        placeholder="Sebutkan jika Ya">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit"
                                class="btn btn-warning px-5 py-2 rounded-pill shadow-sm fw-semibold text-white">
                                <i class="bi bi-save2-fill me-2"></i> Simpan Evaluasi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection