@extends('pengguna.master')

@section('title', 'Pemantauan Ibu Nifas - MomSpire')
@section('header_title', 'Pemantauan Ibu Nifas')
@section('header_subtitle', 'Catat kondisi kesehatan harian Ibu selama masa nifas (hari ke-1 sampai ke-42).')

@section('content')
<div class="row g-4 animate__animated animate__fadeIn" x-data="nifasApp()">
    <!-- Alert Success -->
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Catatan Tersimpan!</h6>
                        <span class="small text-muted">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Left Column: Day Selection & Guide -->
    <div class="col-12 col-xl-5">
        <!-- Day Selector Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1 text-gradient"><i class="bi bi-calendar3 me-2"></i>Pilih Hari Pemantauan</h5>
                <p class="text-muted small mb-4">Pilih hari ke-1 sampai ke-42 untuk melihat atau mengisi catatan harian.</p>

                <!-- Tabs: Hari 1-14 vs Hari 15-42 -->
                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill py-2 fw-semibold small" id="tab-1-14" data-bs-toggle="pill" data-bs-target="#panel-1-14" type="button" role="tab">
                            Hari 1 - 14 (Minggu 1-2)
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill py-2 fw-semibold small" id="tab-15-42" data-bs-toggle="pill" data-bs-target="#panel-15-42" type="button" role="tab">
                            Hari 15 - 42 (Minggu 3-6)
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Panel 1 - 14 -->
                    <div class="tab-pane fade show active" id="panel-1-14" role="tabpanel">
                        <div class="day-grid">
                            <template x-for="day in Array.from({length: 14}, (_, i) => i + 1)">
                                <button type="button" 
                                        @click="selectDay(day)"
                                        :class="{'active': activeDay === day, 'has-data': records[day]}"
                                        class="day-btn">
                                    <span class="day-num" x-text="day"></span>
                                    <template x-if="records[day]">
                                        <i class="bi bi-check-circle-fill check-icon"></i>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Panel 15 - 42 -->
                    <div class="tab-pane fade" id="panel-15-42" role="tabpanel">
                        <div class="day-grid">
                            <template x-for="day in Array.from({length: 28}, (_, i) => i + 15)">
                                <button type="button" 
                                        @click="selectDay(day)"
                                        :class="{'active': activeDay === day, 'has-data': records[day]}"
                                        class="day-btn">
                                    <span class="day-num" x-text="day"></span>
                                    <template x-if="records[day]">
                                        <i class="bi bi-check-circle-fill check-icon"></i>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info / Warning Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-light-warning">
            <div class="card-body p-4">
                <div class="d-flex gap-3">
                    <div class="bg-warning rounded-circle p-2 text-white d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Tanda Bahaya Masa Nifas</h6>
                        <p class="text-muted small mb-0">Segera ke Puskesmas, Rumah Sakit, atau hubungi Bidan jika Ibu mengalami salah satu atau lebih dari gejala-gejala yang ada di form pemantauan (seperti demam tinggi, sakit kepala hebat, darah nifas berbau, pendarahan hebat, atau dada sesak).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Form For Selected Day -->
    <div class="col-12 col-xl-7">
        <form action="{{ route('pengguna.nifas.save') }}" method="POST">
            @csrf
            <!-- Hidden input for selected day -->
            <input type="hidden" name="hari_ke" :value="activeDay">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-soft-primary rounded-circle p-3 text-primary">
                                <span class="fw-bold fs-4" x-text="'H-' + activeDay"></span>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Form Pemantauan Hari Ke-<span x-text="activeDay"></span></h5>
                                <p class="text-muted small mb-0">Isi data kesehatan masa nifas Ibu untuk hari ini.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-4 py-2 shadow-sm fw-semibold">
                            <i class="bi bi-save2-fill me-1"></i> Simpan Catatan
                        </button>
                    </div>
                </div>

                <div class="card-body p-4 bg-white border-top">
                    <!-- SECTION A: PELAYANAN KESEHATAN & PEMANTAUAN UMUM -->
                    <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill">A</span>
                        Pelayanan Kesehatan & Pemantauan Umum
                    </h6>
                    
                    <div class="row g-3 mb-4">
                        <!-- Pemeriksaan Nifas -->
                        <div class="col-12">
                            <div class="form-check-card p-3 rounded-4 bg-light border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="pemeriksaan_nifas" id="pemeriksaan_nifas" :checked="form.pemeriksaan_nifas" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-dark small" for="pemeriksaan_nifas">
                                        Pemeriksaan kesehatan nifas oleh Nakes (hari ini)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Konsumsi Vitamin A -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="konsumsi_vitamin_a" id="konsumsi_vitamin_a" :checked="form.konsumsi_vitamin_a" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-dark small" for="konsumsi_vitamin_a">
                                        Mengkonsumsi Kapsul Vitamin A
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Konsumsi Tablet Tambah Darah -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="konsumsi_ttd" id="konsumsi_ttd" :checked="form.konsumsi_ttd" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-dark small" for="konsumsi_ttd">
                                        Mengkonsumsi Tablet Tambah Darah (TTD)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Pemenuhan Gizi -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="pemenuhan_gizi" id="pemenuhan_gizi" :checked="form.pemenuhan_gizi" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-dark small" for="pemenuhan_gizi">
                                        Pemenuhan gizi seimbang sesuai kebutuhan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Masalah Jiwa -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="masalah_jiwa" id="masalah_jiwa" :checked="form.masalah_jiwa" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-dark small" for="masalah_jiwa">
                                        Mengalami kecemasan / masalah kesehatan jiwa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION B: GEJALA FISIK & GEJALA BAHAYA -->
                    <h6 class="fw-bold text-danger mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                        <span class="badge bg-danger rounded-pill">B</span>
                        Pemantauan Gejala & Kondisi Fisik Ibu
                    </h6>

                    <div class="row g-3 mb-4">
                        <!-- Demam -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="demam" id="demam" :checked="form.demam" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="demam">
                                        Mengalami Demam (> 38°C)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Sakit Kepala -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="sakit_kepala" id="sakit_kepala" :checked="form.sakit_kepala" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="sakit_kepala">
                                        Mengalami Sakit Kepala Hebat
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Pandangan Kabur -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="pandangan_kabur" id="pandangan_kabur" :checked="form.pandangan_kabur" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="pandangan_kabur">
                                        Pandangan Mata Kabur
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Nyeri Ulu Hati -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="nyeri_ulu_hati" id="nyeri_ulu_hati" :checked="form.nyeri_ulu_hati" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="nyeri_ulu_hati">
                                        Nyeri pada Ulu Hati
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Jantung Berdebar -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="jantung_berdebar" id="jantung_berdebar" :checked="form.jantung_berdebar" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="jantung_berdebar">
                                        Jantung Berdebar-debar keras
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Keluar Cairan Lahir -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="keluar_cairan_lahir" id="keluar_cairan_lahir" :checked="form.keluar_cairan_lahir" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="keluar_cairan_lahir">
                                        Keluar cairan berbau dari jalan lahir
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Napas Pendek -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="napas_pendek" id="napas_pendek" :checked="form.napas_pendek" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="napas_pendek">
                                        Napas Pendek / Terengah-engah
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Payudara Bengkak -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="payudara_bengkak" id="payudara_bengkak" :checked="form.payudara_bengkak" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="payudara_bengkak">
                                        Payudara bengkak/nyeri/ada benjolan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Gangguan BAK -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="gangguan_bak" id="gangguan_bak" :checked="form.gangguan_bak" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="gangguan_bak">
                                        Gangguan buang air kecil (sakit/sulit)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Kelamin Bengkak -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="kelamin_bengkak" id="kelamin_bengkak" :checked="form.kelamin_bengkak" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="kelamin_bengkak">
                                        Area kelamin bengkak / nyeri / luka
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Darah Nifas Berbau -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="darah_nifas_berbau" id="darah_nifas_berbau" :checked="form.darah_nifas_berbau" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="darah_nifas_berbau">
                                        Darah nifas berbau/mengalir/perut sangat nyeri
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Pendarahan Hebat -->
                        <div class="col-12 col-md-6">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0 h-100">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="pendarahan_hebat" id="pendarahan_hebat" :checked="form.pendarahan_hebat" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="pendarahan_hebat">
                                        Pendarahan hebat (> 2 pembalut dalam 5 menit)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Keputihan -->
                        <div class="col-12">
                            <div class="form-check-card p-3 rounded-4 bg-light-danger border-0">
                                <div class="form-check d-flex align-items-start gap-3">
                                    <input type="checkbox" name="keputihan" id="keputihan" :checked="form.keputihan" class="form-check-input custom-checkbox-lg mt-1">
                                    <label class="form-check-label fw-semibold text-danger small" for="keputihan">
                                        Keputihan berlebih / gatal / berbau
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CADRES / MEDICAL STAFF SIGNATURE -->
                    <h6 class="fw-bold text-secondary mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge"></i>
                        Paraf & Catatan Kader / Tenaga Kesehatan
                    </h6>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="paraf_kader_nakes" class="form-label small fw-semibold text-muted">Tanggal, Nama & Paraf Kader/Nakes</label>
                                <input type="text" name="paraf_kader_nakes" id="paraf_kader_nakes" :value="form.paraf_kader_nakes" placeholder="Contoh: 12 Mei 2026 - Bidan Susi (Paraf)" class="form-control rounded-pill border-light bg-light px-3 py-2 small">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function nifasApp() {
        return {
            activeDay: 1,
            // JSON raw data dari backend
            records: @json($records),
            form: {
                pemeriksaan_nifas: false,
                konsumsi_vitamin_a: false,
                konsumsi_ttd: false,
                pemenuhan_gizi: false,
                masalah_jiwa: false,
                demam: false,
                sakit_kepala: false,
                pandangan_kabur: false,
                nyeri_ulu_hati: false,
                jantung_berdebar: false,
                keluar_cairan_lahir: false,
                napas_pendek: false,
                payudara_bengkak: false,
                gangguan_bak: false,
                kelamin_bengkak: false,
                darah_nifas_berbau: false,
                pendarahan_hebat: false,
                keputihan: false,
                paraf_kader_nakes: ''
            },

            init() {
                this.selectDay(1);
            },

            selectDay(day) {
                this.activeDay = day;
                const rec = this.records[day];
                if (rec) {
                    this.form.pemeriksaan_nifas = !!rec.pemeriksaan_nifas;
                    this.form.konsumsi_vitamin_a = !!rec.konsumsi_vitamin_a;
                    this.form.konsumsi_ttd = !!rec.konsumsi_ttd;
                    this.form.pemenuhan_gizi = !!rec.pemenuhan_gizi;
                    this.form.masalah_jiwa = !!rec.masalah_jiwa;
                    this.form.demam = !!rec.demam;
                    this.form.sakit_kepala = !!rec.sakit_kepala;
                    this.form.pandangan_kabur = !!rec.pandangan_kabur;
                    this.form.nyeri_ulu_hati = !!rec.nyeri_ulu_hati;
                    this.form.jantung_berdebar = !!rec.jantung_berdebar;
                    this.form.keluar_cairan_lahir = !!rec.keluar_cairan_lahir;
                    this.form.napas_pendek = !!rec.napas_pendek;
                    this.form.payudara_bengkak = !!rec.payudara_bengkak;
                    this.form.gangguan_bak = !!rec.gangguan_bak;
                    this.form.kelamin_bengkak = !!rec.kelamin_bengkak;
                    this.form.darah_nifas_berbau = !!rec.darah_nifas_berbau;
                    this.form.pendarahan_hebat = !!rec.pendarahan_hebat;
                    this.form.keputihan = !!rec.keputihan;
                    this.form.paraf_kader_nakes = rec.paraf_kader_nakes || '';
                } else {
                    // Reset to empty/false
                    for (let key in this.form) {
                        if (typeof this.form[key] === 'boolean') {
                            this.form[key] = false;
                        } else {
                            this.form[key] = '';
                        }
                    }
                }
            }
        };
    }
</script>

<style>
    /* Day selector grid */
    .day-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }
    .day-btn {
        aspect-ratio: 1/1;
        border-radius: 12px;
        border: 2px solid #f1f5f9;
        background-color: white;
        color: #64748b;
        font-weight: 700;
        font-size: 14px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .day-btn:hover {
        border-color: var(--accent1);
        color: var(--accent1);
        transform: scale(1.05);
    }
    .day-btn.active {
        background: linear-gradient(135deg, var(--accent1) 0%, var(--accent2) 100%);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .day-btn.has-data {
        background-color: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }
    .day-btn.has-data.active {
        background: linear-gradient(135deg, var(--accent1) 0%, var(--accent2) 100%);
        color: white;
    }
    .check-icon {
        position: absolute;
        bottom: 2px;
        right: 2px;
        font-size: 10px;
        color: #22c55e;
    }
    .day-btn.active .check-icon {
        color: white;
    }

    /* Warning card alert */
    .bg-light-warning {
        background-color: #fffbeb;
        border: 1px solid #fef3c7;
    }
    
    /* Light danger color theme */
    .bg-light-danger {
        background-color: #fef2f2;
    }

    /* Gradient buttons & titles */
    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--accent1) 0%, var(--accent2) 100%);
        border: 0;
        color: white;
    }
    .btn-gradient-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
    }
    .bg-soft-primary {
        background-color: #e0f2fe;
    }

    /* Form check card styling */
    .form-check-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .form-check-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }
    .custom-checkbox-lg {
        width: 1.5em;
        height: 1.5em;
        border-radius: 0.35em !important;
        border-color: #cbd5e1;
    }
    .custom-checkbox-lg:checked {
        background-color: var(--accent1);
        border-color: var(--accent1);
    }

    /* Active pills colors */
    .nav-pills .nav-link {
        color: #64748b;
    }
    .nav-pills .nav-link.active {
        background-color: var(--accent1);
        color: white;
    }
</style>
@endsection
