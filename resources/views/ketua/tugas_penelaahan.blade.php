@extends('layouts.app')

@section('title', 'Tugas Penelaahan - Ketua KEPK')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Daftar Tugas Penelaahan Protokol (Ketua KEPK)</h3>
    <p class="text-muted mb-0">Pantau penugasan penelaah dan progres telaah etik usulan penelitian.</p>
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
                        <th>Penelaah Ditugaskan</th>
                        <th>Status Usulan</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $idx => $p)
                        <tr>
                            <td class="ps-4 text-muted">{{ $idx + 1 }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $p->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($p->judul_penelitian, 50) }}</div>
                                <small class="text-muted">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</small>
                            </td>
                            <td>
                                @if($p->penugasanReview && $p->penugasanReview->isNotEmpty())
                                    <div class="d-flex flex-column gap-1">
                                        @foreach($p->penugasanReview as $pr)
                                            <div class="small">
                                                <i class="bi bi-person-badge text-primary me-1"></i>
                                                {{ $pr->penelaah?->profil?->nama_lengkap ?? $pr->penelaah?->email ?? '-' }}
                                                @if($pr->status_review === 'selesai')
                                                    <span class="badge bg-success-subtle text-success ms-1">Selesai</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning ms-1">Review</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">Belum ada penelaah</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $p->statusProposal?->nama_status ?? 'Sedang Berjalan' }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('ketua.verifikasi', $p->id) }}" class="btn btn-sm btn-outline-primary btn-modern">
                                    <i class="bi bi-eye me-1"></i> Tinjau Protokol
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard2-check fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada tugas penelaahan aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
