@extends($role . '.master')

@section('title', 'Pelayanan Kesehatan Ibu - MomSpire')

@section('header_title', 'Pencatatan Pelayanan Kesehatan Ibu')
@section('header_subtitle', 'Lengkapi data pelayanan kesehatan ibu untuk dicetak pada Buku KIA Halaman 50.')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card role-card">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle p-2 me-3">
                            <i class="bi bi-journal-medical fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Data Ibu: {{ $dataKia->ibu->nama ?? 'N/A' }}</h5>
                            <p class="text-muted small mb-0">Diisi oleh Tenaga Kesehatan</p>
                        </div>
                    </div>
                    <a href="{{ route($role . '.kia') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                
                <!-- Tab Navigation for 6 Visits -->
                <ul class="nav nav-pills nav-fill mb-4 bg-light p-2 rounded-4" id="kunjungan-tab" role="tablist">
                    @for ($i = 1; $i <= 6; $i++)
                        @php
                            $tabLabel = '';
                            if ($i == 1) $tabLabel = 'Trimester I (1)';
                            elseif ($i == 2 || $i == 3) $tabLabel = "Trimester II ($i)";
                            else $tabLabel = "Trimester III ($i)";
                            
                            $isActive = (session('active_tab', 'kunjungan1') == 'kunjungan' . $i);
                        @endphp
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold rounded-pill {{ $isActive ? 'active bg-success' : 'text-dark' }}" 
                                id="tab-kunjungan-{{ $i }}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#pane-kunjungan-{{ $i }}" 
                                type="button" 
                                role="tab">
                                {{ $tabLabel }}
                            </button>
                        </li>
                    @endfor
                </ul>

                <div class="tab-content" id="kunjungan-tabContent">
                    @for ($i = 1; $i <= 6; $i++)
                        @php
                            $isActive = (session('active_tab', 'kunjungan1') == 'kunjungan' . $i);
                            $pData = $pelayanan->get($i);
                        @endphp
                        <div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" id="pane-kunjungan-{{ $i }}" role="tabpanel">
                            <form action="{{ route($role . '.kia.save_pelayanan', $dataKia->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="kunjungan_ke" value="{{ $i }}">

                                @php
                                    $tripelArr = explode(',', $pData->tripel_eliminasi ?? ',,');
                                    $valH = $tripelArr[0] ?? '';
                                    $valS = $tripelArr[1] ?? '';
                                    $valHepB = $tripelArr[2] ?? '';
                                @endphp

                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Tanggal dan Tempat</h6>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Tanggal periksa</label>
                                                    <input type="date" name="tanggal_periksa" class="form-control" value="{{ $pData->tanggal_periksa ?? '' }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Tempat periksa</label>
                                                    <input type="text" name="tempat_periksa" class="form-control" value="{{ $pData->tempat_periksa ?? '' }}" placeholder="Nama faskes">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Pemeriksaan Fisik</h6>
                                                
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Berat Badan (kg)</label>
                                                        <input type="number" step="0.1" name="berat_badan" class="form-control" value="{{ $pData->berat_badan ?? '' }}">
                                                    </div>
                                                    @if(in_array($i, [1]))
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tinggi Badan (cm)</label>
                                                        <input type="number" step="0.1" name="tinggi_badan" class="form-control" value="{{ $pData->tinggi_badan ?? '' }}">
                                                    </div>
                                                    @endif
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">LiLA (cm)</label>
                                                        <input type="number" step="0.1" name="lingkar_lengan_atas" class="form-control" value="{{ $pData->lingkar_lengan_atas ?? '' }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tekanan Darah</label>
                                                        <input type="text" name="tekanan_darah" class="form-control" value="{{ $pData->tekanan_darah ?? '' }}" placeholder="120/80">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tinggi Rahim (cm)</label>
                                                        <input type="number" step="0.1" name="tinggi_rahim" class="form-control" value="{{ $pData->tinggi_rahim ?? '' }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Letak/Denyut Jantung Bayi</label>
                                                        <input type="text" name="denyut_jantung_bayi" class="form-control" value="{{ $pData->denyut_jantung_bayi ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="card bg-light border-0 shadow-sm rounded-4">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Tindakan, Skrining & Laboratorium</h6>
                                                
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Status/Imunisasi Tetanus</label>
                                                        <input type="text" name="status_imunisasi_tt" class="form-control" value="{{ $pData->status_imunisasi_tt ?? '' }}">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Konseling</label>
                                                        <input type="text" name="konseling" class="form-control" value="{{ $pData->konseling ?? '' }}">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Skrining Dokter</label>
                                                        <input type="text" name="skrining_dokter" class="form-control" value="{{ $pData->skrining_dokter ?? '' }}">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tablet Tambah Darah (TTD)</label>
                                                        <input type="text" name="tablet_tambah_darah" class="form-control" value="{{ $pData->tablet_tambah_darah ?? '' }}" placeholder="Jumlah">
                                                    </div>
                                                    @if(in_array($i, [1, 4, 5]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Hemoglobin (Hb)</label>
                                                        <input type="text" name="tes_lab_hb" class="form-control" value="{{ $pData->tes_lab_hb ?? '' }}">
                                                    </div>
                                                    @endif
                                                    @if(in_array($i, [1]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Golongan Darah</label>
                                                        <input type="text" name="tes_golongan_darah" class="form-control" value="{{ $pData->tes_golongan_darah ?? '' }}">
                                                    </div>
                                                    @endif
                                                    @if(in_array($i, [2, 3, 4, 5, 6]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Protein Urine</label>
                                                        <input type="text" name="tes_lab_protein_urine" class="form-control" value="{{ $pData->tes_lab_protein_urine ?? '' }}">
                                                    </div>
                                                    @endif
                                                    @if(in_array($i, [4, 5, 6]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Gula Darah</label>
                                                        <input type="text" name="tes_lab_gula_darah" class="form-control" value="{{ $pData->tes_lab_gula_darah ?? '' }}">
                                                    </div>
                                                    @endif
                                                    @if(in_array($i, [1, 5]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">USG</label>
                                                        <input type="text" name="usg" class="form-control" value="{{ $pData->usg ?? '' }}">
                                                    </div>
                                                    @endif
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-semibold">Tripel Eliminasi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">H</span>
                                                            <input type="text" name="tripel_eliminasi_h" class="form-control" value="{{ $valH }}">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">S</span>
                                                            <input type="text" name="tripel_eliminasi_s" class="form-control" value="{{ $valS }}">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">Hep B</span>
                                                            <input type="text" name="tripel_eliminasi_hep_b" class="form-control" value="{{ $valHepB }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-semibold">Tata Laksana Kasus</label>
                                                        <input type="text" name="tata_laksana_kasus" class="form-control" value="{{ $pData->tata_laksana_kasus ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-success px-5 py-2 rounded-pill shadow-sm fw-semibold">
                                        <i class="bi bi-save2-fill me-2"></i> Simpan Kunjungan {{ $i }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endfor
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
