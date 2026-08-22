@extends('layouts.app')

@section('title', 'Usulan Peneliti')

@section('content')
<div class="mb-4">
    <a href="{{ route('sekretaris.peneliti') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Peneliti</a>
    <h3 class="fw-bold text-dark mb-1">Daftar Usulan: {{ $peneliti->nama_lengkap }}</h3>
    <p class="text-muted mb-0">Instansi: {{ $peneliti->profil?->asal_kampus ?? '-' }} | Kontak: {{ $peneliti->profil?->nomor_telepon ?? $peneliti->email }}</p>
</div>

<div class="card card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode</th>
                        <th>Judul Penelitian</th>
                        <th>Jenis Riset</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $idx => $p)
                        <tr>
                            <td class="ps-4 text-muted">{{ $idx + 1 }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $p->judul_penelitian }}</div>
                                <small class="text-muted">Tempat: {{ $p->tempat_penelitian ?? '-' }}</small>
                            </td>
                            <td>{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</td>
                            <td>{{ date('d M Y', strtotime($p->tanggal_pengajuan)) }}</td>
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
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('sekretaris.verifikasi', $p->id) }}" class="btn btn-sm btn-primary btn-modern" style="background: #0d9488; border: none;">
                                    <i class="bi bi-check2-square me-1"></i> Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Peneliti ini belum mengajukan usulan protokol riset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection