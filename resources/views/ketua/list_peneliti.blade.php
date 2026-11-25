@extends('layouts.app')

@section('title', 'Daftar Peneliti - Ketua KEPK')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-end flex-wrap gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1">Daftar Peneliti Terdaftar</h3>
        <p class="text-muted mb-0">Pantauan peneliti dan jumlah protokol riset yang diajukan ke KEPK RSUD Sidoarjo Barat.</p>
    </div>
    
    <div class="search-box" style="width: 100%; max-width: 400px;">
        <form action="" method="GET">
            <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama, NIK, atau Instansi..." value="{{ request('search') }}">
                <button class="btn btn-primary px-3" type="submit" style="background: #0d9488; border: none;">Cari</button>
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-light border px-3" title="Reset"><i class="bi bi-x-lg"></i></a>
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
                        <th class="py-3">Asal Instansi / Kampus</th>
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
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                    {{ $p->pengajuanProposal ? $p->pengajuanProposal->count() : 0 }} Usulan
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                @php
                                    $proposalRoute = Route::has('ketua.peneliti.proposal') ? route('ketua.peneliti.proposal', $p->id) : route('sekretaris.peneliti.proposal', $p->id);
                                @endphp
                                <a href="{{ $proposalRoute }}" class="btn btn-sm btn-outline-primary btn-modern">
                                    <i class="bi bi-file-earmark-text me-1"></i> Lihat Usulan
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada data peneliti yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
