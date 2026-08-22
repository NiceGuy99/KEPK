@extends('layouts.app')

@section('title', 'Dashboard Penelaah Etik')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold" style="color: #1e293b;">Selamat Datang, {{ Auth::user()->nama_lengkap }}!</h3>
    <p class="text-muted">Lembar kerja penelaahan kelaikan etik protokol penelitian KEPK RSUD Sidoarjo Barat.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-modern p-3 border-start border-primary border-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Total Tugas Telaah</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h3>
                </div>
                <div class="text-primary fs-2"><i class="bi bi-inboxes"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern p-3 border-start border-warning border-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Menunggu Telaah</span>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['menunggu'] }}</h3>
                </div>
                <div class="text-warning fs-2"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern p-3 border-start border-success border-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Selesai Ditelaah</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['selesai'] }}</h3>
                </div>
                <div class="text-success fs-2"><i class="bi bi-check2-circle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="card card-modern">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f1f5f9; border-radius: 16px 16px 0 0;">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-journal-check text-primary me-2"></i> Usulan Penelitian Yang Ditugaskan</h6>
        <a href="{{ route('penelaah.tugas') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Peneliti & Kampus</th>
                        <th>Judul Penelitian</th>
                        <th>Batas Waktu</th>
                        <th>Status Review</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugas as $t)
                        <tr>
                            <td class="ps-4"><span class="badge bg-light text-dark border">{{ $t->proposal?->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $t->proposal?->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $t->proposal?->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($t->proposal?->judul_penelitian, 60) }}</div>
                                <small class="text-muted">{{ $t->proposal?->jenisPenelitian?->nama_jenis ?? '-' }}</small>
                            </td>
                            <td>
                                @if($t->batas_waktu)
                                    <span class="badge bg-light text-danger border"><i class="bi bi-calendar-event me-1"></i> {{ date('d M Y', strtotime($t->batas_waktu)) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($t->status_review == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Selesai</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('penelaah.telaah', $t->id) }}" class="btn btn-sm btn-primary btn-modern" style="background: #0d9488; border: none;">
                                    <i class="bi bi-pencil-square me-1"></i> Telaah
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada usulan penelitian yang ditugaskan kepada Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
