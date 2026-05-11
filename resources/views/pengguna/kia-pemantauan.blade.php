@extends('pengguna.master')

@section('title', 'Pemantauan Ibu Hamil - MomSpire')
@section('header_title', 'Pemantauan Ibu Hamil')
@section('header_subtitle', 'Pantau pelayanan kesehatan dan kondisi kesehatan mingguan Anda secara mandiri.')

@section('content')
<div class="row g-4" x-data="{ activeTrimester: 1 }">
    <!-- Tab Selector -->
    <div class="col-12">
        <div class="d-flex justify-content-center gap-3">
            <button @click="activeTrimester = 1" 
                    :class="activeTrimester === 1 ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all">
                <i class="bi bi-calendar2-heart-fill me-2"></i> Trimester I & II (Minggu 4 - 24)
            </button>
            <button @click="activeTrimester = 2" 
                    :class="activeTrimester === 2 ? 'btn-gradient-secondary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all">
                <i class="bi bi-calendar3-fill me-2"></i> Trimester II & III (Minggu 25 - 42)
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
                        <h6 class="fw-bold mb-0">Berhasil!</h6>
                        <span class="small text-muted">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Wrapper -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-1 text-gradient">Lembar Pemantauan Mingguan</h5>
                <p class="text-muted small mb-0">Beri tanda centang (✓) pada setiap pemeriksaan kesehatan dan kondisi yang Anda alami.</p>
            </div>

            <div class="card-body p-0 bg-white">
                <!-- ================== TRIMESTER I & II (Weeks 4-24) ================== -->
                <div x-show="activeTrimester === 1" x-transition class="p-4">
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-bordered table-hover text-center align-middle small mb-0">
                            <thead class="table-header-custom text-white">
                                <tr>
                                    <th rowspan="3" style="width: 70px;">Minggu</th>
                                    <th colspan="2">Pelayanan Kesehatan</th>
                                    <th colspan="9">Pemantauan Mingguan (Cek jika mengalami kondisi di bawah ini)</th>
                                    <th rowspan="3" style="width: 100px;">Aksi</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="width: 100px;">Pemeriksaan Kehamilan</th>
                                    <th rowspan="2" style="width: 100px;">Kelas Ibu Hamil</th>
                                    <th rowspan="2" style="width: 90px;">Demam > 2 Hari</th>
                                    <th rowspan="2" style="width: 90px;">Pusing / Sakit Berat</th>
                                    <th rowspan="2" style="width: 90px;">Sulit Tidur / Cemas</th>
                                    <th rowspan="2" style="width: 90px;">Risiko TB</th>
                                    <th style="width: 100px; font-weight: bold;">Gerakan Bayi</th>
                                    <th rowspan="2" style="width: 90px;">Nyeri Perut Hebat</th>
                                    <th rowspan="2" style="width: 90px;">Keluar Cairan</th>
                                    <th rowspan="2" style="width: 110px;">Sakit Kencing / Gatal</th>
                                    <th rowspan="2" style="width: 90px;">Diare Berulang</th>
                                </tr>
                                <tr>
                                    <th class="bg-secondary bg-opacity-25 py-1 text-dark text-xs font-normal" style="font-size: 10px;">(Aktif mgg 25)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(4, 24) as $w)
                                    @php $p = $pemantauans->get($w); @endphp
                                    <form action="{{ route('pengguna.pemantauan.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="minggu_ke" value="{{ $w }}">
                                        <tr>
                                            <td class="fw-bold bg-light">{{ $w }}</td>
                                            <td><input type="checkbox" name="pemeriksaan_kehamilan" {{ $p && $p->pemeriksaan_kehamilan ? 'checked' : '' }} class="form-check-input custom-check bg-blue"></td>
                                            <td><input type="checkbox" name="kelas_ibu_hamil" {{ $p && $p->kelas_ibu_hamil ? 'checked' : '' }} class="form-check-input custom-check bg-indigo"></td>
                                            <td><input type="checkbox" name="demam_lebih_2_hari" {{ $p && $p->demam_lebih_2_hari ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="pusing_sakit_kepala" {{ $p && $p->pusing_sakit_kepala ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="sulit_tidur_cemas" {{ $p && $p->sulit_tidur_cemas ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="risiko_tb" {{ $p && $p->risiko_tb ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            
                                            <!-- Gerakan Bayi: Disabled for Week 4-24 -->
                                            <td class="{{ $w <= 24 ? 'bg-secondary bg-opacity-25' : '' }}">
                                                <input type="checkbox" name="gerakan_bayi" {{ $p && $p->gerakan_bayi ? 'checked' : '' }} {{ $w <= 24 ? 'disabled' : '' }} class="form-check-input custom-check bg-danger">
                                            </td>

                                            <td><input type="checkbox" name="nyeri_perut_hebat" {{ $p && $p->nyeri_perut_hebat ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="keluar_cairan_lahir" {{ $p && $p->keluar_cairan_lahir ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="sakit_saat_kencing" {{ $p && $p->sakit_saat_kencing ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="diare_berulang" {{ $p && $p->diare_berulang ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-gradient-primary rounded-pill px-3 shadow-xs">
                                                    <i class="bi bi-save"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Accordion View for Mobile Users (I & II) -->
                    <div class="d-lg-none">
                        <div class="accordion accordion-flush" id="accordionTrimester1">
                            @foreach(range(4, 24) as $w)
                                @php $p = $pemantauans->get($w); @endphp
                                <div class="accordion-item border-bottom mb-2 rounded-3 overflow-hidden shadow-xs">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMgg{{ $w }}">
                                            <span class="badge bg-primary rounded-pill me-2">Mgg {{ $w }}</span>
                                            Status Pemantauan
                                        </button>
                                    </h2>
                                    <div id="collapseMgg{{ $w }}" class="accordion-collapse collapse" data-bs-parent="#accordionTrimester1">
                                        <div class="accordion-body bg-light">
                                            <form action="{{ route('pengguna.pemantauan.save') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="minggu_ke" value="{{ $w }}">
                                                
                                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-file-medical-fill me-2"></i>Pelayanan Kesehatan</h6>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="pemeriksaan_kehamilan" id="mgg{{$w}}_pem" {{ $p && $p->pemeriksaan_kehamilan ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_pem">Pemeriksaan Kehamilan</label>
                                                </div>
                                                <div class="form-check mb-4">
                                                    <input type="checkbox" name="kelas_ibu_hamil" id="mgg{{$w}}_kls" {{ $p && $p->kelas_ibu_hamil ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_kls">Kelas Ibu Hamil</label>
                                                </div>

                                                <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Pemantauan Kondisi</h6>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="demam_lebih_2_hari" id="mgg{{$w}}_demam" {{ $p && $p->demam_lebih_2_hari ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_demam">Demam lebih dari 2 hari</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="pusing_sakit_kepala" id="mgg{{$w}}_pusing" {{ $p && $p->pusing_sakit_kepala ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_pusing">Pusing / sakit kepala berat</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="sulit_tidur_cemas" id="mgg{{$w}}_cemas" {{ $p && $p->sulit_tidur_cemas ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_cemas">Sulit tidur / cemas berlebih</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="risiko_tb" id="mgg{{$w}}_tb" {{ $p && $p->risiko_tb ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_tb">Risiko TB (Batuk berlebih / kontak serumah)</label>
                                                </div>

                                                <div class="form-check mb-2 p-2 rounded {{ $w <= 24 ? 'bg-secondary bg-opacity-10 text-muted' : '' }}">
                                                    <input type="checkbox" name="gerakan_bayi" id="mgg{{$w}}_gerakan" {{ $p && $p->gerakan_bayi ? 'checked' : '' }} {{ $w <= 24 ? 'disabled' : '' }} class="form-check-input ms-1">
                                                    <label class="form-check-label small ms-2" for="mgg{{$w}}_gerakan">
                                                        Gerakan bayi kurang dari 10x / 12 jam
                                                        @if($w <= 24)
                                                            <span class="d-block text-xs text-danger">(Aktif mulai Minggu ke-25)</span>
                                                        @endif
                                                    </label>
                                                </div>

                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="nyeri_perut_hebat" id="mgg{{$w}}_nyeri" {{ $p && $p->nyeri_perut_hebat ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_nyeri">Nyeri perut hebat</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="keluar_cairan_lahir" id="mgg{{$w}}_cairan" {{ $p && $p->keluar_cairan_lahir ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_cairan">Keluar cairan dari jalan lahir</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="sakit_saat_kencing" id="mgg{{$w}}_kencing" {{ $p && $p->sakit_saat_kencing ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_kencing">Sakit kencing / keputihan / gatal</label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input type="checkbox" name="diare_berulang" id="mgg{{$w}}_diare" {{ $p && $p->diare_berulang ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_diare">Diare berulang</label>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill shadow-xs mt-2">
                                                    <i class="bi bi-save2-fill"></i> Simpan Minggu ke-{{ $w }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ================== TRIMESTER II & III (Weeks 25-42) ================== -->
                <div x-show="activeTrimester === 2" x-transition class="p-4">
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-bordered table-hover text-center align-middle small mb-0">
                            <thead class="table-header-custom-secondary text-white">
                                <tr>
                                    <th rowspan="2" style="width: 70px;">Minggu</th>
                                    <th colspan="2">Pelayanan Kesehatan</th>
                                    <th colspan="9">Pemantauan Mingguan (Cek jika mengalami kondisi di bawah ini)</th>
                                    <th rowspan="2" style="width: 100px;">Aksi</th>
                                </tr>
                                <tr>
                                    <th style="width: 100px;">Pemeriksaan Kehamilan</th>
                                    <th style="width: 100px;">Kelas Ibu Hamil</th>
                                    <th style="width: 90px;">Demam > 2 Hari</th>
                                    <th style="width: 90px;">Pusing / Sakit Berat</th>
                                    <th style="width: 90px;">Sulit Tidur / Cemas</th>
                                    <th style="width: 90px;">Risiko TB</th>
                                    <th style="width: 100px;">Gerakan Bayi</th>
                                    <th style="width: 90px;">Nyeri Perut Hebat</th>
                                    <th style="width: 90px;">Keluar Cairan</th>
                                    <th style="width: 110px;">Sakit Kencing / Gatal</th>
                                    <th style="width: 90px;">Diare Berulang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(range(25, 42) as $w)
                                    @php $p = $pemantauans->get($w); @endphp
                                    <form action="{{ route('pengguna.pemantauan.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="minggu_ke" value="{{ $w }}">
                                        <tr>
                                            <td class="fw-bold bg-light">{{ $w }}</td>
                                            <td><input type="checkbox" name="pemeriksaan_kehamilan" {{ $p && $p->pemeriksaan_kehamilan ? 'checked' : '' }} class="form-check-input custom-check bg-blue"></td>
                                            <td><input type="checkbox" name="kelas_ibu_hamil" {{ $p && $p->kelas_ibu_hamil ? 'checked' : '' }} class="form-check-input custom-check bg-indigo"></td>
                                            <td><input type="checkbox" name="demam_lebih_2_hari" {{ $p && $p->demam_lebih_2_hari ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="pusing_sakit_kepala" {{ $p && $p->pusing_sakit_kepala ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="sulit_tidur_cemas" {{ $p && $p->sulit_tidur_cemas ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="risiko_tb" {{ $p && $p->risiko_tb ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            
                                            <!-- Gerakan Bayi: Enabled for Trimester II & III completely -->
                                            <td><input type="checkbox" name="gerakan_bayi" {{ $p && $p->gerakan_bayi ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>

                                            <td><input type="checkbox" name="nyeri_perut_hebat" {{ $p && $p->nyeri_perut_hebat ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="keluar_cairan_lahir" {{ $p && $p->keluar_cairan_lahir ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="sakit_saat_kencing" {{ $p && $p->sakit_saat_kencing ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td><input type="checkbox" name="diare_berulang" {{ $p && $p->diare_berulang ? 'checked' : '' }} class="form-check-input custom-check bg-danger"></td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-gradient-secondary rounded-pill px-3 shadow-xs">
                                                    <i class="bi bi-save"></i> Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Accordion View for Mobile Users (II & III) -->
                    <div class="d-lg-none">
                        <div class="accordion accordion-flush" id="accordionTrimester2">
                            @foreach(range(25, 42) as $w)
                                @php $p = $pemantauans->get($w); @endphp
                                <div class="accordion-item border-bottom mb-2 rounded-3 overflow-hidden shadow-xs">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMgg{{ $w }}">
                                            <span class="badge bg-secondary rounded-pill me-2">Mgg {{ $w }}</span>
                                            Status Pemantauan
                                        </button>
                                    </h2>
                                    <div id="collapseMgg{{ $w }}" class="accordion-collapse collapse" data-bs-parent="#accordionTrimester2">
                                        <div class="accordion-body bg-light">
                                            <form action="{{ route('pengguna.pemantauan.save') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="minggu_ke" value="{{ $w }}">
                                                
                                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-file-medical-fill me-2"></i>Pelayanan Kesehatan</h6>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="pemeriksaan_kehamilan" id="mgg{{$w}}_pem" {{ $p && $p->pemeriksaan_kehamilan ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_pem">Pemeriksaan Kehamilan</label>
                                                </div>
                                                <div class="form-check mb-4">
                                                    <input type="checkbox" name="kelas_ibu_hamil" id="mgg{{$w}}_kls" {{ $p && $p->kelas_ibu_hamil ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_kls">Kelas Ibu Hamil</label>
                                                </div>

                                                <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Pemantauan Kondisi</h6>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="demam_lebih_2_hari" id="mgg{{$w}}_demam" {{ $p && $p->demam_lebih_2_hari ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_demam">Demam lebih dari 2 hari</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="pusing_sakit_kepala" id="mgg{{$w}}_pusing" {{ $p && $p->pusing_sakit_kepala ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_pusing">Pusing / sakit kepala berat</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="sulit_tidur_cemas" id="mgg{{$w}}_cemas" {{ $p && $p->sulit_tidur_cemas ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_cemas">Sulit tidur / cemas berlebih</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="risiko_tb" id="mgg{{$w}}_tb" {{ $p && $p->risiko_tb ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_tb">Risiko TB (Batuk berlebih / kontak serumah)</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="gerakan_bayi" id="mgg{{$w}}_gerakan" {{ $p && $p->gerakan_bayi ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_gerakan">Gerakan bayi kurang dari 10x / 12 jam</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="nyeri_perut_hebat" id="mgg{{$w}}_nyeri" {{ $p && $p->nyeri_perut_hebat ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_nyeri">Nyeri perut hebat</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="keluar_cairan_lahir" id="mgg{{$w}}_cairan" {{ $p && $p->keluar_cairan_lahir ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_cairan">Keluar cairan dari jalan lahir</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="sakit_saat_kencing" id="mgg{{$w}}_kencing" {{ $p && $p->sakit_saat_kencing ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_kencing">Sakit kencing / keputihan / gatal</label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input type="checkbox" name="diare_berulang" id="mgg{{$w}}_diare" {{ $p && $p->diare_berulang ? 'checked' : '' }} class="form-check-input">
                                                    <label class="form-check-label small" for="mgg{{$w}}_diare">Diare berulang</label>
                                                </div>

                                                <button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill shadow-xs mt-2">
                                                    <i class="bi bi-save2-fill"></i> Simpan Minggu ke-{{ $w }}
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

    <!-- Educational Warning Info Box -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-warning text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-exclamation-octagon-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">🚨 Hubungi Tenaga Kesehatan Segera!</h6>
                    <p class="mb-0 small opacity-90">Jika Anda memberikan tanda centang (✓) pada salah satu kolom **Pemantauan Kondisi** (Demam, Pusing hebat, Nyeri perut, Keluar cairan, Sakit kencing, atau Diare), segera periksakan kondisi kehamilan Anda ke Puskesmas, Bidan, atau Rumah Sakit terdekat.</p>
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
    .custom-check.bg-blue:checked {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
    }
    .custom-check.bg-indigo:checked {
        background-color: #6366f1 !important;
        border-color: #6366f1 !important;
    }
    .custom-check.bg-danger:checked {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
    }

    .shadow-xs {
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
@endsection
