@extends('pengguna.master')

@section('title', 'Keluarga Berencana - MomSpire')
@section('header_title', 'Keluarga Berencana')
@section('header_subtitle', 'Rencanakan kehamilan yang sehat melalui program Keluarga Berencana (KB).')

@section('content')
<div class="row g-4 animate__animated animate__fadeIn">
    <!-- Success Alert -->
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-4 mb-4 animate__animated animate__bounceIn" role="alert">
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

        <form action="{{ route('pengguna.kb.save') }}" method="POST">
            @csrf
            
            <!-- Card Wrapper -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-gradient-primary rounded-circle p-3 text-white">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-gradient">Keluarga Berencana (Diisi oleh Ibu)</h5>
                                <p class="text-muted small mb-0">Rencanakan kehamilan yang sehat demi masa depan keluarga Ibu.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-4 py-2 shadow-sm fw-semibold">
                            <i class="bi bi-save2-fill me-1"></i> Simpan Rencana KB
                        </button>
                    </div>
                </div>

                <div class="card-body p-4 bg-white border-top">
                    <!-- Informative Top Section -->
                    <div class="alert alert-info rounded-4 border-0 p-4 mb-4" style="background-color: #f0fdf4;">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md-3 text-center">
                                <div class="bg-white p-3 rounded-4 shadow-sm inline-block">
                                    <i class="bi bi-balloon-heart fs-1 text-success"></i>
                                </div>
                            </div>
                            <div class="col-12 col-md-9">
                                <h6 class="fw-bold text-success mb-2">Merencanakan Kehamilan yang Sehat</h6>
                                <p class="text-muted small mb-0">
                                    Kehamilan yang sehat adalah kehamilan yang direncanakan, diinginkan, dan dijaga perkembangannya dengan baik. 
                                    Penting memiliki kesehatan fisik dan mental dalam kondisi layak untuk hamil, yaitu:
                                </p>
                                <ul class="text-muted small mt-2 mb-0">
                                    <li>Usia 20-35 tahun dan jarak kehamilan minimal 2 tahun untuk mengurangi angka kematian ibu melahirkan.</li>
                                    <li>Jumlah anak kurang dari 3 untuk kecukupan gizi dan perawatan anak.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <!-- Persetujuan & Paraf Ibu -->
                            <div class="p-4 rounded-4 bg-light border-0 shadow-sm" style="border-left: 5px solid var(--accent2) !important;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-gradient-secondary rounded-circle p-2 text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-journal-text text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">Pernyataan Kesediaan KB Pasca Salin</h6>
                                        <p class="text-muted small mb-3">
                                            "Bersedia menggunakan KB pasca salin sesudah melahirkan demi kebaikan kesehatan Ibu dan bayi."
                                        </p>
                                        
                                        <div class="form-group pt-3 border-top">
                                            <label for="paraf_ibu" class="form-label small fw-bold text-muted mb-2"><i class="bi bi-pencil-fill me-1 text-primary"></i> Paraf / Tanda Tangan Ibu:</label>
                                            <input type="text" name="paraf_ibu" id="paraf_ibu" value="{{ $kb->paraf_ibu ?? '' }}" class="form-control rounded-pill px-4" placeholder="Ketik nama lengkap atau inisial Ibu sebagai paraf...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Gradient styles */
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
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--accent1) 0%, var(--accent2) 100%);
    }

    /* Form check customizations */
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

    @media (min-width: 992px) {
        .border-end-lg {
            border-right: 1px solid #e2e8f0;
        }
    }
</style>
@endsection
