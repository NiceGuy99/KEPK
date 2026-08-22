@extends('layouts.app')

@section('title', 'Status Usulan Proposal')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Status Usulan Proposal</h3>
        <p class="text-muted mb-0">Daftar seluruh protokol penelitian yang telah Anda ajukan ke KEPK.</p>
    </div>
    <a href="{{ route('peneliti.pengajuan') }}" class="btn btn-primary btn-modern" style="background: #0d9488; border: none;">
        <i class="bi bi-plus-lg me-1"></i> Ajukan Proposal Baru
    </a>
</div>

<div class="card card-modern">
    <div class="card-body p-0">
        @if($proposals->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                Belum ada data usulan proposal.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Kode Proposal</th>
                            <th>Judul Penelitian</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status Usulan</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proposals as $idx => $p)
                            <tr>
                                <td class="ps-4 text-muted">{{ $idx + 1 }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $p->kode_proposal ?? '-' }}</span></td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $p->judul_penelitian }}</div>
                                    <small class="text-muted">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }} | {{ $p->tempat_penelitian ?? '-' }}</small>
                                </td>
                                <td>{{ date('d M Y', strtotime($p->tanggal_pengajuan)) }}</td>
                                <td>
                                    @if($p->status_id == 1)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                                    @elseif($p->status_id == 2)
                                        <span class="badge bg-info text-dark"><i class="bi bi-person-workspace me-1"></i> Sedang Ditelaah</span>
                                    @elseif($p->status_id == 3)
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i> Perlu Revisi</span>
                                    @elseif($p->status_id == 4)
                                        <span class="badge bg-primary"><i class="bi bi-check2-circle me-1"></i> Sudah Direvisi</span>
                                    @elseif($p->status_id == 5)
                                        <span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i> Selesai (Lolos)</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('peneliti.detail', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Detail Usulan">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>

                                        @if($p->status_id == 3)
                                            <a href="{{ route('peneliti.revisi', $p->id) }}" class="btn btn-sm btn-warning text-dark fw-medium" title="Unggah Revisi">
                                                <i class="bi bi-upload"></i> Revisi
                                            </a>
                                        @endif

                                        @if($p->status_id == 5 && $p->sertifikat)
                                            <a href="{{ route('sertifikat.cetak', $p->id) }}" target="_blank" class="btn btn-sm btn-success" title="Cetak Sertifikat">
                                                <i class="bi bi-printer"></i> Sertifikat
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
