@extends('pengguna.master')

@section('title', 'Jadwal - MomSpire')
@section('header_title', 'Jadwal')
@section('header_subtitle', 'Jadwal kontrol dan imunisasi untuk ibu dan anak')

@push('head')
<style>
    .hero-modern { background: linear-gradient(135deg, #0ea5b7 0%, #ef4444 100%); color: #fff; border-radius: 20px; padding: 40px; margin-bottom: 32px; }
    .hero-modern h1 { font-size: 32px; font-weight: 800; margin-bottom: 8px; }
    .hero-modern p { font-size: 16px; opacity: 0.95; }
    .schedule-card { background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 10px 40px rgba(15,23,42,.08); }
    .schedule-item { background: #f8f9fa; border-radius: 12px; padding: 16px; margin-bottom: 12px; border-left: 4px solid #0ea5b7; display: flex; justify-content: space-between; align-items: center; }
    .schedule-item-date { font-weight: 600; color: #1f2937; }
    .schedule-item-status { font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; }
    .status-active { background: #dbeafe; color: #0ea5b7; }
    .status-completed { background: #d1fae5; color: #059669; }
</style>
@endpush

@section('content')
    <div class="hero-modern">
        <h1>📅 Jadwal Kontrol & Imunisasi</h1>
        <p>Kelola semua jadwal kesehatan Anda dan anak dengan mudah dalam satu tempat</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="schedule-card">
                <h5 class="fw-bold mb-3">🏥 Jadwal Kontrol</h5>
                <p class="text-muted small mb-3">Daftar kunjungan kontrol terjadwal dengan bidan/dokter</p>
                <div id="kontrolList">
                    <div class="schedule-item">
                        <div>
                            <div class="schedule-item-date">Pemeriksaan Trimester 2</div>
                            <div class="small text-muted">2026-05-12 • 09:00</div>
                        </div>
                        <span class="schedule-item-status status-active">Terjadwal</span>
                    </div>
                    <div class="schedule-item">
                        <div>
                            <div class="schedule-item-date">Konsultasi Nutrisi</div>
                            <div class="small text-muted">2026-06-20 • 10:30</div>
                        </div>
                        <span class="schedule-item-status status-active">Terjadwal</span>
                    </div>
                </div>
                <button id="addKontrolBtn" class="btn btn-outline-info btn-sm mt-3 w-100">+ Tambah Jadwal Kontrol</button>
            </div>
        </div>

        <div class="col-md-6">
            <div class="schedule-card">
                <h5 class="fw-bold mb-3">💉 Jadwal Imunisasi</h5>
                <p class="text-muted small mb-3">Riwayat dan jadwal imunisasi untuk anak</p>
                <div id="imunList">
                    <div class="schedule-item">
                        <div>
                            <div class="schedule-item-date">Imunisasi HB</div>
                            <div class="small text-muted">Bayi • 2025-12-01</div>
                        </div>
                        <span class="schedule-item-status status-completed">✓ Selesai</span>
                    </div>
                    <div class="schedule-item">
                        <div>
                            <div class="schedule-item-date">Imunisasi DTP</div>
                            <div class="small text-muted">Anak • 2026-01-15</div>
                        </div>
                        <span class="schedule-item-status status-active">Terjadwal</span>
                    </div>
                </div>
                <button id="addImunBtn" class="btn btn-outline-info btn-sm mt-3 w-100">+ Tambah Jadwal Imunisasi</button>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('pengguna.dashboard') }}" class="btn btn-outline-secondary">← Kembali ke Dashboard</a>
    </div>
@endsection

@push('scripts')
<script>
    (function(){
        document.getElementById('addKontrolBtn').addEventListener('click', function(){
            const item = document.createElement('div');
            item.className = 'schedule-item';
            const date = new Date();
            const dateText = date.toISOString().slice(0,10);
            const timeText = date.getHours().toString().padStart(2,'0') + ':' + date.getMinutes().toString().padStart(2,'0');
            item.innerHTML = `
                <div>
                    <div class="schedule-item-date">Janji Baru</div>
                    <div class="small text-muted">${dateText} • ${timeText}</div>
                </div>
                <span class="schedule-item-status status-active">Terjadwal</span>
            `;
            document.getElementById('kontrolList').prepend(item);
        });

        document.getElementById('addImunBtn').addEventListener('click', function(){
            const item = document.createElement('div');
            item.className = 'schedule-item';
            const date = new Date();
            const dateText = date.toISOString().slice(0,10);
            item.innerHTML = `
                <div>
                    <div class="schedule-item-date">Imunisasi Baru</div>
                    <div class="small text-muted">Anak • ${dateText}</div>
                </div>
                <span class="schedule-item-status status-active">Terjadwal</span>
            `;
            document.getElementById('imunList').prepend(item);
        });
    })();
</script>
@endpush
