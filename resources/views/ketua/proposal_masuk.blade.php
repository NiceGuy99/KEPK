@extends('layouts.app')

@section('title', 'Proposal Masuk - Ketua KEPK')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Proposal Usulan Masuk (Ketua KEPK)</h3>
    <p class="text-muted mb-0">Pemantauan berkas usulan penelitian yang masuk dan status verifikasi protokol.</p>
</div>

<!-- FILTER & PENCARIAN -->
<div class="card card-modern p-3 mb-4">
    <form method="GET" action="{{ route('ketua.proposal-masuk') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control form-control-modern border-start-0 ps-0" placeholder="Cari judul penelitian, kode proposal, peneliti, kampus..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status_id" class="form-select form-control-modern">
                <option value="">-- Semua Status Proposal --</option>
                @foreach($masterStatus as $ms)
                    <option value="{{ $ms->id }}" {{ request('status_id') == $ms->id ? 'selected' : '' }}>
                        {{ $ms->nama_status }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-modern flex-fill" style="background: #0d9488; border: none;">
                <i class="bi bi-filter me-1"></i> Terapkan
            </button>
            @if(request()->hasAny(['search', 'status_id']))
                <a href="{{ route('ketua.proposal-masuk') }}" class="btn btn-light btn-modern border">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card card-modern">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-inboxes text-primary me-2"></i> Daftar Proposal Usulan Penelitian (Total: {{ method_exists($proposals, 'total') ? $proposals->total() : $proposals->count() }})</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode</th>
                        <th>Peneliti & Kampus</th>
                        <th>Judul Penelitian</th>
                        <th>Tgl Masuk</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $idx => $p)
                        <tr>
                            <td class="ps-4 text-muted">{{ method_exists($proposals, 'firstItem') ? ($proposals->firstItem() + $idx) : ($idx + 1) }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted"><i class="bi bi-building me-1"></i>{{ $p->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($p->judul_penelitian, 55) }}</div>
                                <small class="text-muted">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</small>
                            </td>
                            <td>{{ date('d/m/Y', strtotime($p->tanggal_pengajuan)) }}</td>
                            <td>
                                @if($p->status_id == 1)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                                @elseif($p->status_id == 2)
                                    <span class="badge bg-info text-dark"><i class="bi bi-person-workspace me-1"></i> Sedang Ditelaah</span>
                                @elseif($p->status_id == 3)
                                    <span class="badge bg-danger"><i class="bi bi-exclamation-circle me-1"></i> Perlu Revisi</span>
                                @elseif($p->status_id == 4)
                                    <span class="badge bg-primary"><i class="bi bi-arrow-repeat me-1"></i> Sudah Direvisi</span>
                                @elseif($p->status_id == 5)
                                    <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Selesai (Lolos)</span>
                                @else
                                    <span class="badge bg-secondary">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('ketua.verifikasi', $p->id) }}" class="btn btn-sm btn-outline-primary btn-modern">
                                    <i class="bi bi-shield-check me-1"></i> Verifikasi Berkas
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada berkas usulan proposal masuk yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($proposals, 'hasPages') && $proposals->hasPages())
            <div class="card-footer bg-white py-3 border-0">
                {{ $proposals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
