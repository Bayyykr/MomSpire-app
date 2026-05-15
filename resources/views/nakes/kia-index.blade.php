@extends($role . '.master')

@section('title', 'Manajemen Buku KIA - MomSpire')

@section('header_title', 'Manajemen Buku KIA')
@section('header_subtitle', 'Daftar data KIA pengguna yang perlu diverifikasi dan dilengkapi.')

@section('content')
<div class="card role-card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama Ibu</th>
                        <th>Email</th>
                        <th>Status Screening</th>
                        <th>Terakhir Update</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataKias as $kia)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $kia->ibu->nama ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $kia->user->email ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $kia->riwayat ? 'text-bg-success' : 'text-bg-warning' }}">
                                    {{ $kia->riwayat ? 'Lengkap' : 'Menunggu Nakes' }}
                                </span>
                            </td>
                            <td>{{ $kia->updated_at ? \Illuminate\Support\Carbon::parse($kia->updated_at)->diffForHumans() : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route($role . '.kia.edit_riwayat', $kia->id) }}" class="btn btn-sm btn-outline-primary mb-1">
                                    <i class="bi bi-pencil-square me-1"></i> Riwayat
                                </a>
                                <a href="{{ route($role . '.kia.edit_pelayanan', $kia->id) }}" class="btn btn-sm btn-outline-info mb-1">
                                    <i class="bi bi-journal-medical me-1"></i> Pelayanan
                                </a>
                                <a href="{{ route('admin.kia.export_pdf', $kia->id) }}" target="_blank" class="btn btn-sm btn-outline-success mb-1">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data KIA yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
