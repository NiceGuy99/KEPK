@extends('layouts.app')

@section('title', 'Riwayat Telaah - Ketua KEPK')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Riwayat Hasil Telaah Etik (Ketua KEPK)</h3>
    <p class="text-muted mb-0">Arsip hasil telaah dan rekomendasi etik dari para penelaah protokol riset.</p>
</div>

<div class="card card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode & Judul Riset</th>
                        <th>Peneliti</th>
                        <th>Penelaah</th>
                        <th>Tgl Review</th>
                        <th>Rekomendasi</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $idx => $r)
                        <tr>
                            <td class="ps-4 text-muted">{{ method_exists($reviews, 'firstItem') ? ($reviews->firstItem() + $idx) : ($idx + 1) }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($r->proposal?->judul_penelitian ?? '-', 50) }}</div>
                                <small class="text-muted">Kode: {{ $r->proposal?->kode_proposal ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $r->proposal?->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $r->proposal?->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-medium text-dark"><i class="bi bi-person-badge me-1"></i> {{ $r->penelaah?->profil?->nama_lengkap ?? $r->penelaah?->email ?? '-' }}</div>
                            </td>
                            <td>{{ $r->tanggal_review ? date('d M Y', strtotime($r->tanggal_review)) : '-' }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold">
                                    {{ $r->rekomendasi ?? 'Selesai' }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('ketua.detail-telaah', $r->id) }}" class="btn btn-sm btn-outline-primary btn-modern">
                                    <i class="bi bi-eye me-1"></i> Detail Telaah
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-clock-history fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada riwayat telaah etik yang selesai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($reviews, 'hasPages') && $reviews->hasPages())
            <div class="card-footer bg-white py-3 border-0">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
