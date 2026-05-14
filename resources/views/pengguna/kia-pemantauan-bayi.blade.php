@extends('pengguna.master')

@section('title', 'Pemantauan Harian Bayi 0-28 Hari - MomSpire')
@section('header_title', 'Pemantauan Harian Bayi (0 - 28 Hari)')
@section('header_subtitle', 'Pantau kondisi kesehatan harian si kecil secara mandiri untuk deteksi dini tanda bahaya.')

@section('content')
<div class="row g-4" x-data="{ activePeriod: 1 }">
    <!-- Tab Selector -->
    <div class="col-12">
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <button @click="activePeriod = 1" 
                    :class="activePeriod === 1 ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all">
                <i class="bi bi-calendar2-heart-fill me-2"></i> Minggu 1 & 2 (Hari 1 - 14)
            </button>
            <button @click="activePeriod = 2" 
                    :class="activePeriod === 2 ? 'btn-gradient-secondary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all">
                <i class="bi bi-calendar3-fill me-2"></i> Minggu 3 & 4 (Hari 15 - 28)
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Berhasil Disimpan!</h6>
                        <span class="small text-muted">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Wrapper -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-1 text-gradient">Lembar Pemantauan Harian Bayi</h5>
                <p class="text-muted small mb-0">Beri tanda centang (✓) jika si kecil mengalami kondisi di bawah ini pada hari yang bersangkutan.</p>
            </div>

            <div class="card-body p-0 bg-white">
                <!-- ================== PERIODE 1 (Hari 1-14) ================== -->
                <div x-show="activePeriod === 1" x-transition class="p-4">
                    <div class="table-responsive d-none d-xl-block">
                        <table class="table table-bordered table-hover text-center align-middle small mb-0">
                            <thead class="table-header-custom text-white">
                                <tr>
                                    <th rowspan="2" style="width: 60px;">Hari</th>
                                    <th colspan="6">Kondisi Bagian A</th>
                                    <th colspan="6">Kondisi Bagian B</th>
                                    <th rowspan="2" style="width: 120px;">Kader/Nakes</th>
                                    <th rowspan="2" style="width: 90px;">Aksi</th>
                                </tr>
                                <tr>
                                    <th style="width: 80px;">Sesak / Napas Cepat</th>
                                    <th style="width: 80px;">Lemah / Merintih</th>
                                    <th style="width: 80px;">Kulit Biru / Memar</th>
                                    <th style="width: 85px;">Hisapan Lemah / Kencing Sedikit</th>
                                    <th style="width: 80px;">Kejang / Mata Mendelik</th>
                                    <th style="width: 80px;">Suhu >38.5 / <36.5</th>
                                    
                                    <th style="width: 80px;">BAB Abnormal / Dempul</th>
                                    <th style="width: 80px;">Kencing Pekat / Tidak Kencing</th>
                                    <th style="width: 80px;">Tali Pusat Bernanah</th>
                                    <th style="width: 80px;">Mata Bernanah</th>
                                    <th style="width: 80px;">Bintil Air / Nanah</th>
                                    <th style="width: 80px;">Belum HB0 / BCG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(1, 14) as $d)
                                    @php $p = $pemantauans->get($d); @endphp
                                    <form action="{{ route('pengguna.pemantauan_bayi.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="hari_ke" value="{{ $d }}">
                                        <tr>
                                            <td class="fw-bold bg-light">{{ $d }}</td>
                                            <td><input type="checkbox" name="sesak_napas" {{ $p && $p->sesak_napas ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="aktivitas_lemah" {{ $p && $p->aktivitas_lemah ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="warna_kulit_biru" {{ $p && $p->warna_kulit_biru ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="hisapan_lemah" {{ $p && $p->hisapan_lemah ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="kejang" {{ $p && $p->kejang ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="suhu_abnormal" {{ $p && $p->suhu_abnormal ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            
                                            <td><input type="checkbox" name="bab_abnormal" {{ $p && $p->bab_abnormal ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="kencing_sedikit" {{ $p && $p->kencing_sedikit ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="tali_pusat_merah" {{ $p && $p->tali_pusat_merah ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="mata_merah" {{ $p && $p->mata_merah ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="kulit_bintil" {{ $p && $p->kulit_bintil ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="belum_imunisasi" {{ $p && $p->belum_imunisasi ? 'checked' : '' }} class="form-check-input custom-check bg-info"></td>
                                            
                                            <td>
                                                <input type="text" name="paraf_kader_nakes" value="{{ $p->paraf_kader_nakes ?? '' }}" class="form-control form-control-sm text-center" placeholder="Nama / Paraf">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-gradient-primary rounded-pill px-3 shadow-xs w-100">
                                                    <i class="bi bi-save"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Accordion View for Mobile & Tablet Users (Hari 1-14) -->
                    <div class="d-xl-none">
                        <div class="accordion accordion-flush" id="accordionBayi1">
                            @foreach(range(1, 14) as $d)
                                @php $p = $pemantauans->get($d); @endphp
                                <div class="accordion-item border-bottom mb-2 rounded-3 overflow-hidden shadow-xs">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHari{{ $d }}">
                                            <span class="badge bg-primary rounded-pill me-2">Hari {{ $d }}</span>
                                            Catatan Pemantauan Si Kecil
                                        </button>
                                    </h2>
                                    <div id="collapseHari{{ $d }}" class="accordion-collapse collapse" data-bs-parent="#accordionBayi1">
                                        <div class="accordion-body bg-light">
                                            <form action="{{ route('pengguna.pemantauan_bayi.save') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="hari_ke" value="{{ $d }}">
                                                
                                                <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-shield-exclamation me-2"></i>Kondisi Bagian A</h6>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="sesak_napas" id="h{{$d}}_sesak" {{ $p && $p->sesak_napas ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_sesak">Sesak napas / napas cepat / dada tertarik ke dalam</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="aktivitas_lemah" id="h{{$d}}_lemah" {{ $p && $p->aktivitas_lemah ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_lemah">Aktivitas tampak lemah / tidak bergerak / merintih</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="warna_kulit_biru" id="h{{$d}}_biru" {{ $p && $p->warna_kulit_biru ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_biru">Warna kulit tampak biru / memar di mulut/tangan/kaki</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="hisapan_lemah" id="h{{$d}}_hisap" {{ $p && $p->hisapan_lemah ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_hisap">Hisapan lemah / muntah hijau / kencing kurang 6x</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="kejang" id="h{{$d}}_kejang" {{ $p && $p->kejang ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_kejang">Kejang / mata mendelik / menangis melengking</label>
                                                </div>
                                                <div class="form-check mb-4">
                                                    <input type="checkbox" name="suhu_abnormal" id="h{{$d}}_suhu" {{ $p && $p->suhu_abnormal ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_suhu">Suhu tubuh panas >38.5°C atau dingin <36.5°C</label>
                                                </div>

                                                <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-activity me-2"></i>Kondisi Bagian B</h6>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="bab_abnormal" id="h{{$d}}_bab" {{ $p && $p->bab_abnormal ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_bab">BAB abnormal / tidak ada anus / BAB dempul / encer</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="kencing_sedikit" id="h{{$d}}_kencing" {{ $p && $p->kencing_sedikit ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_kencing">Air kencing sedikit / pekat / warna kecoklatan</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="tali_pusat_merah" id="h{{$d}}_tali" {{ $p && $p->tali_pusat_merah ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_tali">Tali pusat kemerahan / bernanah / berbau</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="mata_merah" id="h{{$d}}_mata" {{ $p && $p->mata_merah ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_mata">Mata merah / ada kotoran bernanah</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="kulit_bintil" id="h{{$d}}_kulit" {{ $p && $p->kulit_bintil ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_kulit">Kulit ada bintil-bintil berisi air / nanah</label>
                                                </div>
                                                <div class="form-check mb-4">
                                                    <input type="checkbox" name="belum_imunisasi" id="h{{$d}}_imun" {{ $p && $p->belum_imunisasi ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_imun">Belum dapat imunisasi Hepatitis B0 / BCG</label>
                                                </div>

                                                <div class="form-group mb-3 pt-2 border-top">
                                                    <label class="form-label small fw-bold text-muted">Paraf Kader / Tenaga Kesehatan:</label>
                                                    <input type="text" name="paraf_kader_nakes" value="{{ $p->paraf_kader_nakes ?? '' }}" class="form-control rounded-pill px-3" placeholder="Nama / inisial...">
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill shadow-xs">
                                                    <i class="bi bi-save2-fill"></i> Simpan Catatan Hari ke-{{ $d }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ================== PERIODE 2 (Hari 15-28) ================== -->
                <div x-show="activePeriod === 2" x-transition class="p-4">
                    <div class="table-responsive d-none d-xl-block">
                        <table class="table table-bordered table-hover text-center align-middle small mb-0">
                            <thead class="table-header-custom-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="width: 60px;">Hari</th>
                                    <th colspan="6">Kondisi Bagian A</th>
                                    <th colspan="6">Kondisi Bagian B</th>
                                    <th rowspan="2" style="width: 120px;">Kader/Nakes</th>
                                    <th rowspan="2" style="width: 90px;">Aksi</th>
                                </tr>
                                <tr>
                                    <th style="width: 80px;">Sesak / Napas Cepat</th>
                                    <th style="width: 80px;">Lemah / Merintih</th>
                                    <th style="width: 80px;">Kulit Biru / Memar</th>
                                    <th style="width: 85px;">Hisapan Lemah / Kencing Sedikit</th>
                                    <th style="width: 80px;">Kejang / Mata Mendelik</th>
                                    <th style="width: 80px;">Suhu >38.5 / <36.5</th>
                                    
                                    <th style="width: 80px;">BAB Abnormal / Dempul</th>
                                    <th style="width: 80px;">Kencing Pekat / Tidak Kencing</th>
                                    <th style="width: 80px;">Tali Pusat Bernanah</th>
                                    <th style="width: 80px;">Mata Bernanah</th>
                                    <th style="width: 80px;">Bintil Air / Nanah</th>
                                    <th style="width: 80px;">Belum HB0 / BCG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(15, 28) as $d)
                                    @php $p = $pemantauans->get($d); @endphp
                                    <form action="{{ route('pengguna.pemantauan_bayi.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="hari_ke" value="{{ $d }}">
                                        <tr>
                                            <td class="fw-bold bg-light">{{ $d }}</td>
                                            <td><input type="checkbox" name="sesak_napas" {{ $p && $p->sesak_napas ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="aktivitas_lemah" {{ $p && $p->aktivitas_lemah ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="warna_kulit_biru" {{ $p && $p->warna_kulit_biru ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="hisapan_lemah" {{ $p && $p->hisapan_lemah ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="kejang" {{ $p && $p->kejang ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="suhu_abnormal" {{ $p && $p->suhu_abnormal ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            
                                            <td><input type="checkbox" name="bab_abnormal" {{ $p && $p->bab_abnormal ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="kencing_sedikit" {{ $p && $p->kencing_sedikit ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="tali_pusat_merah" {{ $p && $p->tali_pusat_merah ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="mata_merah" {{ $p && $p->mata_merah ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="kulit_bintil" {{ $p && $p->kulit_bintil ? 'checked' : '' }} class="form-check-input custom-check bg-warning"></td>
                                            <td><input type="checkbox" name="belum_imunisasi" {{ $p && $p->belum_imunisasi ? 'checked' : '' }} class="form-check-input custom-check bg-info"></td>
                                            
                                            <td>
                                                <input type="text" name="paraf_kader_nakes" value="{{ $p->paraf_kader_nakes ?? '' }}" class="form-control form-control-sm text-center" placeholder="Nama / Paraf">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-gradient-secondary rounded-pill px-3 shadow-xs w-100">
                                                    <i class="bi bi-save"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Accordion View for Mobile & Tablet Users (Hari 15-28) -->
                    <div class="d-xl-none">
                        <div class="accordion accordion-flush" id="accordionBayi2">
                            @foreach(range(15, 28) as $d)
                                @php $p = $pemantauans->get($d); @endphp
                                <div class="accordion-item border-bottom mb-2 rounded-3 overflow-hidden shadow-xs">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHari{{ $d }}">
                                            <span class="badge bg-secondary rounded-pill me-2">Hari {{ $d }}</span>
                                            Catatan Pemantauan Si Kecil
                                        </button>
                                    </h2>
                                    <div id="collapseHari{{ $d }}" class="accordion-collapse collapse" data-bs-parent="#accordionBayi2">
                                        <div class="accordion-body bg-light">
                                            <form action="{{ route('pengguna.pemantauan_bayi.save') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="hari_ke" value="{{ $d }}">
                                                
                                                <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-shield-exclamation me-2"></i>Kondisi Bagian A</h6>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="sesak_napas" id="h{{$d}}_sesak2" {{ $p && $p->sesak_napas ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_sesak2">Sesak napas / napas cepat / dada tertarik ke dalam</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="aktivitas_lemah" id="h{{$d}}_lemah2" {{ $p && $p->aktivitas_lemah ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_lemah2">Aktivitas tampak lemah / tidak bergerak / merintih</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="warna_kulit_biru" id="h{{$d}}_biru2" {{ $p && $p->warna_kulit_biru ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_biru2">Warna kulit tampak biru / memar di mulut/tangan/kaki</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="hisapan_lemah" id="h{{$d}}_hisap2" {{ $p && $p->hisapan_lemah ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_hisap2">Hisapan lemah / muntah hijau / kencing kurang 6x</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="kejang" id="h{{$d}}_kejang2" {{ $p && $p->kejang ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_kejang2">Kejang / mata mendelik / menangis melengking</label>
                                                </div>
                                                <div class="form-check mb-4">
                                                    <input type="checkbox" name="suhu_abnormal" id="h{{$d}}_suhu2" {{ $p && $p->suhu_abnormal ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_suhu2">Suhu tubuh panas >38.5°C atau dingin <36.5°C</label>
                                                </div>

                                                <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-activity me-2"></i>Kondisi Bagian B</h6>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="bab_abnormal" id="h{{$d}}_bab2" {{ $p && $p->bab_abnormal ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_bab2">BAB abnormal / tidak ada anus / BAB dempul / encer</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="kencing_sedikit" id="h{{$d}}_kencing2" {{ $p && $p->kencing_sedikit ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_kencing2">Air kencing sedikit / pekat / warna kecoklatan</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="tali_pusat_merah" id="h{{$d}}_tali2" {{ $p && $p->tali_pusat_merah ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_tali2">Tali pusat kemerahan / bernanah / berbau</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="mata_merah" id="h{{$d}}_mata2" {{ $p && $p->mata_merah ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_mata2">Mata merah / ada kotoran bernanah</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="kulit_bintil" id="h{{$d}}_kulit2" {{ $p && $p->kulit_bintil ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_kulit2">Kulit ada bintil-bintil berisi air / nanah</label>
                                                </div>
                                                <div class="form-check mb-4">
                                                    <input type="checkbox" name="belum_imunisasi" id="h{{$d}}_imun2" {{ $p && $p->belum_imunisasi ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="h{{$d}}_imun2">Belum dapat imunisasi Hepatitis B0 / BCG</label>
                                                </div>

                                                <div class="form-group mb-3 pt-2 border-top">
                                                    <label class="form-label small fw-bold text-muted">Paraf Kader / Tenaga Kesehatan:</label>
                                                    <input type="text" name="paraf_kader_nakes" value="{{ $p->paraf_kader_nakes ?? '' }}" class="form-control rounded-pill px-3" placeholder="Nama / inisial...">
                                                </div>

                                                <button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill shadow-xs">
                                                    <i class="bi bi-save2-fill"></i> Simpan Catatan Hari ke-{{ $d }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Warning Info Box -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-warning text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-exclamation-octagon-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">🚨 Waspada Tanda Bahaya Bayi Baru Lahir!</h6>
                    <p class="mb-0 small opacity-90">Jika si kecil mengalami salah satu kondisi di atas (sesak napas, kejang, hisapan lemah, warna kulit biru, demam, mata/tali pusat bernanah), **SEGERA** bawa si kecil ke fasilitas kesehatan, Puskesmas, Dokter, atau Bidan terdekat untuk mendapatkan penanganan medis secepatnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Gradient Button Styles */
    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--accent1) 0%, var(--accent2) 100%);
        border: 0;
    }
    .btn-gradient-secondary {
        background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
        border: 0;
    }
    .btn-gradient-primary:hover, .btn-gradient-secondary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    /* Table custom stylings */
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

    /* Checkbox custom colors */
    .custom-check {
        width: 20px;
        height: 20px;
        cursor: pointer;
        border-radius: 4px !important;
        border: 1.5px solid #cbd5e1;
    }
    .custom-check.bg-danger:checked {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
    }
    .custom-check.bg-warning:checked {
        background-color: #f59e0b !important;
        border-color: #f59e0b !important;
    }
    .custom-check.bg-info:checked {
        background-color: #06b6d4 !important;
        border-color: #06b6d4 !important;
    }

    .shadow-xs {
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
@endsection
