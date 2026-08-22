@extends('layouts.app')

@section('title', 'Dashboard Peneliti')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold" style="color: #1e293b;">Selamat Datang, {{ Auth::user()->nama_lengkap }}!</h3>
    <p class="text-muted">Pantau status usulan ethical clearance dan ajukan protokol penelitian baru Anda di sini.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-modern p-3 border-start border-primary border-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Total Usulan</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h3>
                </div>
                <div class="text-primary fs-2"><i class="bi bi-folder-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-modern p-3 border-start border-warning border-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Menunggu Verifikasi</span>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['verifikasi'] }}</h3>
                </div>
                <div class="text-warning fs-2"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-modern p-3 border-start border-info border-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Sedang Ditelaah</span>
                    <h3 class="fw-bold mb-0 text-info">{{ $stats['review'] }}</h3>
                </div>
                <div class="text-info fs-2"><i class="bi bi-person-workspace"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-modern p-3 border-start border-success border-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Lolos / Selesai</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['selesai'] }}</h3>
                </div>
                <div class="text-success fs-2"><i class="bi bi-patch-check-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-modern p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Usulan Terbaru</h5>
                <a href="{{ route('peneliti.pengajuan') }}" class="btn btn-sm btn-primary btn-modern" style="background: #0d9488; border: none;">
                    <i class="bi bi-plus-lg me-1"></i> Ajukan Baru
                </a>
            </div>

            @if($proposals->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                    Belum ada usulan penelitian yang diajukan.<br>
                    <a href="{{ route('peneliti.pengajuan') }}" class="btn btn-sm btn-outline-primary mt-2">Buat Pengajuan Sekarang</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Judul Penelitian</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proposals->take(5) as $p)
                                <tr>
                                    <td><span class="badge bg-light text-dark border">{{ $p->kode_proposal ?? '-' }}</span></td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ Str::limit($p->judul_penelitian, 60) }}</div>
                                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ date('d M Y', strtotime($p->tanggal_pengajuan)) }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-secondary">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</span></td>
                                    <td>
                                        @if($p->status_id == 1)
                                            <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                        @elseif($p->status_id == 2)
                                            <span class="badge bg-info text-dark">Sedang Ditelaah</span>
                                        @elseif($p->status_id == 3)
                                            <span class="badge bg-danger">Perlu Revisi</span>
                                        @elseif($p->status_id == 4)
                                            <span class="badge bg-primary">Sudah Direvisi</span>
                                        @elseif($p->status_id == 5)
                                            <span class="badge bg-success">Lolos Kelaikan Etik</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('peneliti.detail', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-modern p-4 mb-4" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); color: white;">
            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i> Alur Ethical Clearance</h5>
            <ol class="ps-3 mb-0 small" style="opacity: 0.95; line-height: 1.8;">
                <li><strong>Pengajuan:</strong> Unggah berkas usulan penelitian (format PDF).</li>
                <li><strong>Verifikasi:</strong> Sekretariat memeriksa kelengkapan administrasi.</li>
                <li><strong>Penelaahan:</strong> Penelaah etik meneliti protokol riset (7 Standar Etik).</li>
                <li><strong>Keputusan:</strong> Lolos / Revisi Protokol / Fullboard.</li>
                <li><strong>Sertifikat:</strong> Unduh sertifikat ethical clearance resmi.</li>
            </ol>
        </div>
    </div>
</div>
@endsection
