@extends('layouts.app')

@section('title', 'Daftar Peneliti')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-end flex-wrap gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1">Daftar Peneliti Terdaftar</h3>
        <p class="text-muted mb-0">Daftar seluruh peneliti dan jumlah usulan protokol yang diajukan ke e-KEPK.</p>
    </div>
    
    <!-- Form Pencarian -->
    <div class="search-box" style="width: 100%; max-width: 400px;">
        <form action="" method="GET">
            <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama, NIK, atau Instansi..." value="{{ request('search') }}">
                <button class="btn btn-primary px-3" type="submit">Cari</button>
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-danger px-3" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card card-modern border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Nama Peneliti</th>
                        <th class="py-3">Email & Kontak</th>
                        <th class="py-3">Asal Instansi</th>
                        <th class="text-center py-3">Total Usulan</th>
                        <th class="text-center pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peneliti as $idx => $p)
                        <tr>
                            <td class="ps-4 text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->nama_lengkap ?? $p->profil?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">NIK: {{ $p->profil?->nik ?? '-' }}</small>
                            </td>
                            <td>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> {{ $p->email }}</div>
                                <small class="text-muted"><i class="bi bi-telephone me-1"></i> {{ $p->profil?->nomor_telepon ?? '-' }}</small>
                            </td>
                            <td>{{ $p->profil?->asal_kampus ?? '-' }}</td>
                            <td class="text-center"><span class="badge bg-primary rounded-pill px-3 py-2">{{ $p->pengajuanProposal->count() }} Usulan</span></td>
                            <td class="text-center pe-4">
                                <a href="{{ route('sekretaris.peneliti.proposal', $p->id) }}" class="btn btn-sm btn-outline-primary btn-modern">
                                    <i class="bi bi-file-earmark-text me-1"></i> Proposal
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
                                <span class="text-muted">
                                    {{ request('search') ? 'Pencarian "'.request('search').'" tidak ditemukan.' : 'Belum ada data peneliti.' }}
                                </span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection