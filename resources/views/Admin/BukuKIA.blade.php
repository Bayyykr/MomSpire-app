@extends('Admin.master')

@section('title', 'Data Buku KIA - MomSpire')
@section('header_title', 'Data Buku KIA')
@section('header_subtitle', 'Akses cepat dokumen resmi <strong>Buku KIA (Permenkes)</strong>.')

@push('head')
<style>
    .kia-hero {
        background: linear-gradient(135deg, rgba(230,57,128,0.12), rgba(0,184,148,0.12));
        border: 1px solid rgba(230,57,128,0.18);
        border-radius: 18px;
        padding: 1.25rem;
    }
    .kia-actions .btn {
        min-width: 170px;
    }
    .kia-viewer {
        width: 100%;
        min-height: 76vh;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: #fff;
    }
</style>
@endpush

@section('content')
    <section class="kia-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="mb-1"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Buku KIA (Permenkes)</h5>
                <p class="mb-0 text-muted">Gunakan viewer di bawah untuk membaca langsung, atau buka tab baru untuk tampilan penuh.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 kia-actions">
                    <a class="btn btn-outline-primary" href="{{ route('admin.kia.pdf') }}" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka Tab Baru
                </a>
                    <a class="btn btn-primary-custom" href="{{ route('admin.kia.pdf') }}" download>
                    <i class="bi bi-download me-1"></i>Download PDF
                </a>
            </div>
        </div>
    </section>

    <div class="admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-book-half me-2"></i>Preview Dokumen</h5>
            <small class="text-muted">Sumber: folder root `buku/`</small>
        </div>
        <div class="card-body">
                <iframe class="kia-viewer" src="{{ route('admin.kia.pdf') }}" title="Buku KIA Permenkes"></iframe>
        </div>
    </div>
@endsection
