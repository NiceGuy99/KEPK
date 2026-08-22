@extends('layouts.app')

@section('title', 'Dashboard Sekretaris KEPK')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold" style="color: #1e293b;">Dashboard Sekretariat KEPK</h3>
    <p class="text-muted">Ringkasan operasional penelaahan kelaikan etik dan administrasi usulan penelitian.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2 col-sm-4 col-6">
        <div class="card card-modern p-3 border-start border-primary border-4 shadow-sm">
            <span class="text-muted small fw-medium">Total Masuk</span>
            <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_proposal'] }}</h3>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="card card-modern p-3 border-start border-warning border-4 shadow-sm">
            <span class="text-muted small fw-medium">Perlu Verifikasi</span>
            <h3 class="fw-bold mb-0 text-warning">{{ $stats['menunggu_verifikasi'] }}</h3>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="card card-modern p-3 border-start border-info border-4 shadow-sm">
            <span class="text-muted small fw-medium">Sedang Ditelaah</span>
            <h3 class="fw-bold mb-0 text-info">{{ $stats['sedang_review'] }}</h3>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="card card-modern p-3 border-start border-danger border-4 shadow-sm">
            <span class="text-muted small fw-medium">Revisi Protokol</span>
            <h3 class="fw-bold mb-0 text-danger">{{ $stats['revisi'] }}</h3>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="card card-modern p-3 border-start border-success border-4 shadow-sm">
            <span class="text-muted small fw-medium">Lolos / Selesai</span>
            <h3 class="fw-bold mb-0 text-success">{{ $stats['selesai'] }}</h3>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6">
        <div class="card card-modern p-3 border-start border-secondary border-4 shadow-sm">
            <span class="text-muted small fw-medium">Total Akun</span>
            <h3 class="fw-bold mb-0 text-secondary">{{ $stats['total_user'] }}</h3>
        </div>
    </div>
</div>

<div class="card card-modern mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f1f5f9; border-radius: 16px 16px 0 0;">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-inboxes-fill text-primary me-2"></i> Usulan Penelitian Masuk Terbaru</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Peneliti</th>
                        <th>Judul Penelitian</th>
                        <th>Jenis Riset</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals->where('status_id', '!=', 5) as $p)
                        <tr>
                            <td class="ps-4"><span class="badge bg-light text-dark border">{{ $p->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $p->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $p->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ Str::limit($p->judul_penelitian, 65) }}</div>
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ date('d M Y', strtotime($p->tanggal_pengajuan)) }}</small>
                            </td>
                            <td><span class="badge bg-light text-secondary">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</span></td>
                            <td class="pe-4">
                                @if($p->status_id == 1)
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @elseif($p->status_id == 2)
                                    <span class="badge bg-info text-dark">Sedang Ditelaah</span>
                                @elseif($p->status_id == 3)
                                    <span class="badge bg-danger">Perlu Revisi</span>
                                @elseif($p->status_id == 4)
                                    <span class="badge bg-primary">Sudah Direvisi</span>
                                @else
                                    <span class="badge bg-secondary">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada usulan penelitian yang aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection