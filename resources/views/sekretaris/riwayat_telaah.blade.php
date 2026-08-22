@extends('layouts.app')

@section('title', 'Riwayat Telaah Protokol')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Riwayat Telaah Protokol Selesai</h3>
    <p class="text-muted mb-0">Daftar usulan penelitian dan hasil penilaian yang telah diselesaikan oleh para penelaah.</p>
</div>

<div class="card card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode</th>
                        <th>Peneliti Utama</th>
                        <th>Judul Penelitian</th>
                        <th>Nama Penelaah</th>
                        <th>Tgl Selesai Review</th>
                        <th>Rekomendasi</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $idx => $r)
                        <tr>
                            <td class="ps-4 text-muted">{{ $reviews->firstItem() + $idx }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $r->proposal?->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $r->proposal?->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $r->proposal?->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($r->proposal?->judul_penelitian, 50) }}</div>
                                <small class="text-muted">{{ $r->proposal?->jenisPenelitian?->nama_jenis ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $r->penelaah?->profil?->nama_lengkap ?? '-' }}</div>
                            </td>
                            <td>{{ $r->tanggal_review ? date('d M Y H:i', strtotime($r->tanggal_review)) : '-' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $r->rekomendasi ?? 'Selesai' }}</span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('sekretaris.detail-telaah', $r->id) }}" class="btn btn-sm btn-outline-info btn-modern">
                                    <i class="bi bi-eye me-1"></i> Lihat Daftar Tilik
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat telaah yang selesai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reviews->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection