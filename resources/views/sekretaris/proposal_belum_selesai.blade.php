@extends('layouts.app')

@section('title', 'Daftar Semua Proposal Masuk')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Daftar Semua Proposal Masuk</h3>
        <p class="text-muted mb-0">Monitoring seluruh usulan protokol penelitian yang masuk ke KEPK (Menunggu Verifikasi, Sedang Ditelaah, Revisi, hingga Selesai).</p>
    </div>
</div>

@if(session('pesan_sukses'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('pesan_sukses') }}
    </div>
@endif

<div class="card card-modern p-3 mb-4 shadow-sm border-0" style="border-radius: 14px;">
    <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control form-control-modern border-start-0" placeholder="Cari kode, judul riset, nama peneliti, kampus..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status_id" class="form-select form-control-modern">
                <option value="">-- Semua Status Usulan --</option>
                @foreach($masterStatus as $st)
                    <option value="{{ $st->id }}" {{ request('status_id') == $st->id ? 'selected' : '' }}>
                        {{ $st->nama_status }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-modern flex-fill" style="background: #0d9488; border: none;">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            @if(request()->hasAny(['search', 'status_id']))
                <a href="{{ url()->current() }}" class="btn btn-light btn-modern border">Reset</a>
            @endif
        </div>
    </form>
</div>

<div class="card card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode & Peneliti</th>
                        <th>Judul Penelitian</th>
                        <th>Jenis Riset</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status Proposal</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $idx => $p)
                        <tr>
                            <td class="ps-4 text-muted">{{ $proposals->firstItem() + $idx }}</td>
                            <td>
                                <div class="badge bg-light text-dark border mb-1">{{ $p->kode_proposal ?? '-' }}</div>
                                <div class="fw-bold text-dark">{{ $p->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $p->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $p->judul_penelitian }}</div>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> Tempat: {{ $p->tempat_penelitian ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</span>
                            </td>
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
                                    <span class="badge bg-success">Selesai (Lolos)</span>
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
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada usulan proposal yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($proposals->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $proposals->links() }}
        </div>
    @endif
</div>
@endsection
