@extends('layouts.app')

@section('title', 'Dashboard Penelaah Etik')

@section('content')
<!-- Welcome Banner Penelaah -->
<div class="card card-modern p-4 mb-4 border-0 text-white" style="background: linear-gradient(135deg, #0d9488 0%, #4338ca 100%); border-radius: 20px; box-shadow: 0 10px 30px rgba(67, 56, 202, 0.25);">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1.5 rounded-pill" style="font-size: 0.78rem;">
                <i class="bi bi-person-check-fill me-1"></i> Penelaah Protokol Etik KEPK
            </span>
            <h2 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->nama_lengkap }}! 🔬</h2>
            <p class="mb-0 text-white-50" style="font-size: 0.95rem;">
                Lembar kerja penelaahan 7 Standar Kelaikan Etik usulan protokol penelitian di RSUD Sidoarjo Barat.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('penelaah.tugas') }}" class="btn btn-light btn-modern shadow-sm text-dark px-3 py-2">
                <i class="bi bi-list-task text-primary me-1"></i> Buka Tugas Telaah
            </a>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-modern card-hover p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">Total Tugas Ditugaskan</span>
                    <h2 class="fw-bold mb-0 text-dark mt-1">{{ $stats['total'] ?? 0 }}</h2>
                    <small class="text-muted" style="font-size: 0.75rem;">Protokol yang masuk ke Anda</small>
                </div>
                <div class="stat-icon-wrapper" style="background: rgba(13, 148, 136, 0.1); color: #0d9488; width: 54px; height: 54px; font-size: 1.5rem;">
                    <i class="bi bi-inboxes-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern card-hover p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">Menunggu Ditelaah</span>
                    <h2 class="fw-bold mb-0 text-warning mt-1">{{ $stats['menunggu'] ?? 0 }}</h2>
                    <small class="text-muted" style="font-size: 0.75rem;">Belum selesai dikaji</small>
                </div>
                <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; width: 54px; height: 54px; font-size: 1.5rem;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern card-hover p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">Selesai Ditelaah</span>
                    <h2 class="fw-bold mb-0 text-success mt-1">{{ $stats['selesai'] ?? 0 }}</h2>
                    <small class="text-muted" style="font-size: 0.75rem;">Rekomendasi diserahkan</small>
                </div>
                <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 54px; height: 54px; font-size: 1.5rem;">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-modern">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f1f5f9; border-radius: 18px 18px 0 0;">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-journal-check text-primary me-2"></i> Usulan Penelitian Yang Ditugaskan</h6>
        <a href="{{ route('penelaah.tugas') }}" class="btn btn-sm btn-outline-primary btn-modern">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
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
                            <td class="ps-4">
                                <span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold">{{ $t->proposal?->kode_proposal ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $t->proposal?->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $t->proposal?->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($t->proposal?->judul_penelitian, 55) }}</div>
                                <small class="text-muted">{{ $t->proposal?->jenisPenelitian?->nama_jenis ?? '-' }}</small>
                            </td>
                            <td>
                                @if($t->batas_waktu)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">
                                        <i class="bi bi-calendar-event me-1"></i> {{ date('d M Y', strtotime($t->batas_waktu)) }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($t->status_review == 'selesai')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill">Selesai</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded-pill">Belum Selesai</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('penelaah.telaah', $t->id) }}" class="btn btn-sm btn-primary-gradient btn-modern px-3 py-1.5">
                                    <i class="bi bi-pencil-square me-1"></i> Buka Telaah
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard2-check fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada usulan penelitian yang ditugaskan kepada Anda saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
