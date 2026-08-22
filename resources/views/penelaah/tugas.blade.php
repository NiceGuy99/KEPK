@extends('layouts.app')

@section('title', 'Tugas Penelaahan Saya')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Daftar Tugas Penelaahan Protokol</h3>
    <p class="text-muted mb-0">Tinjau dan isi telaah etik untuk setiap proposal riset yang ditugaskan kepada Anda.</p>
</div>

<div class="card card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode</th>
                        <th>Peneliti & Kampus</th>
                        <th>Judul Penelitian</th>
                        <th>Tgl Penugasan</th>
                        <th>Status Proposal</th>
                        <th>Status Penelaah</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugas as $idx => $t)
                        <tr>
                            <td class="ps-4 text-muted">{{ $idx + 1 }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $t->proposal?->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $t->proposal?->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $t->proposal?->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($t->proposal?->judul_penelitian, 60) }}</div>
                                <small class="text-muted">{{ $t->proposal?->jenisPenelitian?->nama_jenis ?? '-' }}</small>
                            </td>
                            <td>{{ date('d M Y', strtotime($t->tanggal_penugasan)) }}</td>
                            
                            <!-- Kolom Status Proposal -->
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $t->proposal?->statusProposal?->nama_status ?? 'Sedang Berjalan' }}
                                </span>
                            </td>

                            <!-- Kolom Status Penelaah (Pribadi) -->
                            <td>
                                @if($t->status_review == 'selesai')
                                    <span class="badge bg-success">Selesai Ditelaah</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu Telaah</span>
                                @endif
                            </td>

                            <td class="text-center pe-4">
                                <a href="{{ route('penelaah.telaah', $t->id) }}" class="btn btn-sm btn-primary btn-modern" style="background: #0d9488; border: none;">
                                    <i class="bi bi-pencil-square me-1"></i> Buka Lembar Telaah
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada tugas penelaahan aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection