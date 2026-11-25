@extends('layouts.app')

@section('title', 'Daftar Sertifikat Terbit - Ketua KEPK')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1">Daftar Sertifikat Kelaikan Etik (Ketua KEPK)</h3>
        <p class="text-muted mb-0">Monitoring dan penerbitan berkas sertifikat Ethical Clearance penelitian.</p>
    </div>
    <div>
        <a href="{{ route('ketua.dashboard') }}" class="btn btn-outline-secondary btn-modern">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>
    </div>
</div>

@if(session('pesan_sukses'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('pesan_sukses') }}
    </div>
@endif

<div class="card card-modern p-3 mb-4 shadow-sm border-0" style="border-radius: 14px;">
    <form method="GET" action="{{ route('ketua.sertifikat') }}" class="row g-2 align-items-center">
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control form-control-modern border-start-0" placeholder="Cari nomor sertifikat, judul penelitian, peneliti, asal kampus..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-modern flex-fill" style="background: #0d9488; border: none;">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('ketua.sertifikat') }}" class="btn btn-light btn-modern border">Reset</a>
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
                        <th>Kode & Judul Riset</th>
                        <th>Peneliti Utama</th>
                        <th>Nomor Sertifikat</th>
                        <th>Tanggal Terbit</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sertifikat as $idx => $s)
                        <tr>
                            <td class="ps-4 text-muted">{{ method_exists($sertifikat, 'firstItem') ? ($sertifikat->firstItem() + $idx) : ($idx + 1) }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($s->proposal?->judul_penelitian ?? '-', 50) }}</div>
                                <small class="text-muted">Kode: {{ $s->proposal?->kode_proposal ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $s->proposal?->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $s->proposal?->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-bold">{{ $s->nomor_sertifikat ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="text-muted small"><i class="bi bi-calendar-check me-1"></i> {{ $s->tanggal_terbit ? date('d M Y', strtotime($s->tanggal_terbit)) : '-' }}</span>
                            </td>
                            <td class="text-center pe-4">
                                @if($s->proposal_id)
                                    <a href="{{ route('sertifikat.cetak', $s->proposal_id) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-modern">
                                        <i class="bi bi-printer me-1"></i> Cetak PDF
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-patch-question fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada sertifikat kelaikan etik yang diterbitkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($sertifikat, 'hasPages') && $sertifikat->hasPages())
            <div class="card-footer bg-white py-3 border-0">
                {{ $sertifikat->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
