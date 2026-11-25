@extends('layouts.app')

@section('title', 'Daftar Sertifikat Terbit')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Daftar Sertifikat Kelaikan Etik</h3>
    <p class="text-muted mb-0">Kelola penerbitan dan cetak sertifikat Ethical Clearance untuk riset yang telah selesai.</p>
</div>

@if(session('pesan_sukses'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('pesan_sukses') }}
    </div>
@endif

<div class="card card-modern p-3 mb-4 shadow-sm border-0" style="border-radius: 14px;">
    <form method="GET" action="{{ route('sekretaris.sertifikat') }}" class="row g-2 align-items-center">
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
                <a href="{{ route('sekretaris.sertifikat') }}" class="btn btn-light btn-modern border">Reset</a>
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
                        <th>Status Sertifikat</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposalsSelesai as $idx => $p)
                        <tr>
                            <td class="ps-4 text-muted">{{ $proposalsSelesai->firstItem() + $idx }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($p->judul_penelitian, 50) }}</div>
                                <small class="text-muted">Kode: {{ $p->kode_proposal ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $p->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $p->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                @if($p->sertifikat)
                                    <span class="badge bg-light text-dark border fw-bold">{{ $p->sertifikat->nomor_sertifikat }}</span>
                                @else
                                    <span class="text-muted small">- Belum Dibuat -</span>
                                @endif
                            </td>
                            <td>
                                @if($p->sertifikat)
                                    <span class="badge bg-success">Sudah Diterbitkan</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Dibuat</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($p->sertifikat)
                                    <!-- Tombol Aktif untuk Cetak & Edit Tanggal jika sudah ada -->
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('sertifikat.cetak', $p->id) }}" target="_blank" class="btn btn-sm btn-success btn-modern">
                                            <i class="bi bi-printer me-1"></i> Cetak
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-warning btn-modern" data-bs-toggle="modal" data-bs-target="#modalSertifikat{{ $p->id }}" title="Edit Tanggal Terbit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </div>
                                @else
                                    <!-- Tombol untuk memunculkan Modal Buat Sertifikat -->
                                    <button type="button" class="btn btn-sm btn-primary btn-modern" style="background: #0d9488; border: none;" data-bs-toggle="modal" data-bs-target="#modalSertifikat{{ $p->id }}">
                                        <i class="bi bi-file-earmark-plus me-1"></i> Buat Sertifikat
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada proposal yang berstatus selesai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($proposalsSelesai->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $proposalsSelesai->links() }}
        </div>
    @endif
</div>

<!-- MODAL INPUT / EDIT TANGGAL SERTIFIKAT (Outside table container) -->
@foreach($proposalsSelesai as $p)
    <div class="modal fade" id="modalSertifikat{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sekretaris.sertifikat.simpan', $p->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            {{ $p->sertifikat ? 'Edit / Perbarui Sertifikat' : 'Buat Sertifikat Baru' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Judul Penelitian</label>
                            <input type="text" class="form-control form-control-modern bg-light" value="{{ $p->judul_penelitian }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-medium">Nomor Sertifikat</label>
                            <input type="text" class="form-control form-control-modern bg-light" value="{{ $p->sertifikat->nomor_sertifikat ?? 'Otomatis digenerate oleh sistem' }}" disabled>
                            <small class="text-muted">Format: [No Urut 5 digit]/KEPKRSUDSIBAR/[Bulan]/[Tahun]</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-medium">Tanggal Terbit *</label>
                            <input type="date" name="tanggal_terbit" class="form-control form-control-modern" value="{{ $p->sertifikat->tanggal_terbit ?? date('Y-m-d') }}" required>
                            <small class="text-muted">Bulan dan tahun pada nomor sertifikat akan mengikuti tanggal ini.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm" style="background: #0d9488; border: none;">
                            <i class="bi bi-save me-1"></i> Simpan & Cetak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
</div>
@endsection