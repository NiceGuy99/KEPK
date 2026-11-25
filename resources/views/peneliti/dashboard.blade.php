@extends('layouts.app')

@section('title', 'Dashboard Peneliti')

@section('content')
<!-- Welcome Banner Peneliti -->
<div class="card card-modern p-4 mb-4 border-0 text-white" style="background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%); border-radius: 20px; box-shadow: 0 10px 30px rgba(15, 118, 110, 0.25);">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1.5 rounded-pill" style="font-size: 0.78rem;">
                <i class="bi bi-person-workspace me-1"></i> Area Peneliti e-KEPK
            </span>
            <h2 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->nama_lengkap }}! 📄</h2>
            <p class="mb-0 text-white-50" style="font-size: 0.95rem;">
                Pantau progres kaji etik usulan riset Anda dan dapatkan sertifikat Ethical Clearance resmi dari RSUD Sidoarjo Barat.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                <a href="{{ route('peneliti.pengajuan') }}" class="btn btn-light btn-modern shadow-sm text-dark px-3 py-2">
                    <i class="bi bi-plus-circle-fill text-primary me-1"></i> Ajukan Usulan Baru
                </a>
                <a href="{{ route('peneliti.status') }}" class="btn btn-outline-light btn-modern px-3 py-2">
                    <i class="bi bi-clock-history me-1"></i> Status Proposal
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">Total Usulan</span>
                    <h3 class="fw-bold mb-0 text-dark mt-1"><span class="stat-counter" data-target="{{ $stats['total'] ?? 0 }}">0</span></h3>
                    <small class="text-muted" style="font-size: 0.75rem;">Protokol diajukan</small>
                </div>
                <div class="stat-icon-wrapper" style="background: rgba(13, 148, 136, 0.1); color: #0d9488;">
                    <i class="bi bi-folder-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">Verifikasi Berkas</span>
                    <h3 class="fw-bold mb-0 text-warning mt-1"><span class="stat-counter" data-target="{{ $stats['verifikasi'] ?? 0 }}">0</span></h3>
                    <small class="text-muted" style="font-size: 0.75rem;">Menunggu verifikasi</small>
                </div>
                <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">Sedang Ditelaah</span>
                    <h3 class="fw-bold mb-0 text-info mt-1"><span class="stat-counter" data-target="{{ $stats['review'] ?? 0 }}">0</span></h3>
                    <small class="text-muted" style="font-size: 0.75rem;">Dalam kajian reviewer</small>
                </div>
                <div class="stat-icon-wrapper" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                    <i class="bi bi-search"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">Lolos / Selesai</span>
                    <h3 class="fw-bold mb-0 text-success mt-1"><span class="stat-counter" data-target="{{ $stats['selesai'] ?? 0 }}">0</span></h3>
                    <small class="text-muted" style="font-size: 0.75rem;">Sertifikat diterbitkan</small>
                </div>
                <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-modern mb-4">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f1f5f9; border-radius: 18px 18px 0 0;">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i> Usulan Penelitian Terbaru</h6>
                <a href="{{ route('peneliti.pengajuan') }}" class="btn btn-sm btn-primary-gradient btn-modern px-3">
                    <i class="bi bi-plus-lg me-1"></i> Usulan Baru
                </a>
            </div>

            @if($proposals->isEmpty())
                <div class="text-center py-5 text-muted px-3">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                    <p class="mb-2">Anda belum memiliki usulan protokol penelitian yang diajukan.</p>
                    <a href="{{ route('peneliti.pengajuan') }}" class="btn btn-sm btn-primary-gradient btn-modern">Ajukan Protokol Sekarang</a>
                </div>
            @else
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Kode</th>
                                    <th>Judul Penelitian</th>
                                    <th>Jenis Riset</th>
                                    <th>Status Usulan</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proposals->take(5) as $p)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold">{{ $p->kode_proposal ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ Str::limit($p->judul_penelitian, 55) }}</div>
                                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ date('d M Y', strtotime($p->tanggal_pengajuan)) }}</small>
                                        </td>
                                        <td><span class="badge bg-light text-secondary border">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</span></td>
                                        <td>
                                            @if($p->status_id == 1)
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill">Menunggu Verifikasi</span>
                                            @elseif($p->status_id == 2)
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 rounded-pill">Sedang Ditelaah</span>
                                            @elseif($p->status_id == 3)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill">Perlu Revisi</span>
                                            @elseif($p->status_id == 4)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill">Sudah Direvisi</span>
                                            @elseif($p->status_id == 5)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">Lolos Kelaikan Etik</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1 rounded-pill">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-4">
                                            <a href="{{ route('peneliti.detail', $p->id) }}" class="btn btn-sm btn-outline-primary btn-modern" title="Lihat Detail Protokol">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Side Information -->
    <div class="col-lg-4">
        <div class="card card-modern p-4 mb-4 border-0 text-white" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);">
            <h5 class="fw-bold mb-3"><i class="bi bi-diagram-3-fill me-2"></i> Alur Ethical Clearance</h5>
            <div class="d-flex flex-column gap-3 small" style="opacity: 0.96;">
                <div class="d-flex align-items-start gap-2">
                    <span class="badge bg-white text-dark rounded-circle px-2 py-1 flex-shrink-0">1</span>
                    <div><strong>Pengajuan:</strong> Isi form dan unggah berkas protokol riset (format PDF).</div>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <span class="badge bg-white text-dark rounded-circle px-2 py-1 flex-shrink-0">2</span>
                    <div><strong>Verifikasi:</strong> Sekretariat memeriksa kelengkapan berkas administrasi.</div>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <span class="badge bg-white text-dark rounded-circle px-2 py-1 flex-shrink-0">3</span>
                    <div><strong>Penelaahan:</strong> Penelaah etik meneliti protokol berdasarkan 7 Standar Etik.</div>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <span class="badge bg-white text-dark rounded-circle px-2 py-1 flex-shrink-0">4</span>
                    <div><strong>Keputusan:</strong> Lolos / Revisi Protokol / Sidang Full Board.</div>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <span class="badge bg-white text-dark rounded-circle px-2 py-1 flex-shrink-0">5</span>
                    <div><strong>Sertifikat:</strong> Unduh sertifikat Ethical Clearance resmi jika lolos.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
