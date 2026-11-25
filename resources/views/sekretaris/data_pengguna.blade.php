@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Manajemen Data Pengguna</h3>
        <p class="text-muted mb-0">Kelola akun dan hak akses peneliti, sekretariat, penelaah, dan ketua komite etik.</p>
    </div>
    <button class="btn btn-primary btn-modern shadow-sm" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;" data-bs-toggle="modal" data-bs-target="#modalTambahPengguna">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
    </button>
</div>

<!-- FORM PENCARIAN & FILTER ROLE -->
<div class="card card-modern p-3 mb-4">
    <form method="GET" action="{{ route('sekretaris.pengguna') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control form-control-modern border-start-0 ps-0" placeholder="Cari nama lengkap, NIK, email, kampus..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="role" class="form-select form-control-modern">
                <option value="">-- Semua Hak Akses (Role) --</option>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}" {{ request('role') == $r->id ? 'selected' : '' }}>{{ $r->nama_role }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-modern flex-fill" style="background: #0d9488; border: none;">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('sekretaris.pengguna') }}" class="btn btn-light btn-modern border">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card card-modern">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i> Daftar Pengguna Sistem (Total: {{ $users->total() }})</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Profil Pengguna</th>
                        <th>Kontak & Email</th>
                        <th>Instansi / Kampus</th>
                        <th>Hak Akses (Role)</th>
                        <th>Status</th>
                        <th class="text-center pe-4" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $idx => $u)
                        @php
                            $initials = collect(explode(' ', trim($u->nama_lengkap)))
                                ->filter()
                                ->map(fn($part) => mb_substr($part, 0, 1))
                                ->take(2)
                                ->join('');
                            $avatarBg = match($u->role_id) {
                                1 => 'linear-gradient(135deg, #0d9488 0%, #0284c7 100%)',
                                2 => 'linear-gradient(135deg, #1e40af 0%, #3b82f6 100%)',
                                3 => 'linear-gradient(135deg, #6b21a8 0%, #a855f7 100%)',
                                default => 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)'
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted">{{ $users->firstItem() + $idx }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-xs" 
                                         style="width: 38px; height: 38px; min-width: 38px; background: {{ $avatarBg }}; font-size: 0.8rem; letter-spacing: 0.5px;">
                                        {{ strtoupper($initials ?: 'U') }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->nama_lengkap }}</div>
                                        <small class="text-muted"><i class="bi bi-person-vcard me-1"></i>NIK: {{ $u->profil?->nik ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i class="bi bi-envelope me-1 text-teal" style="color: #0d9488;"></i> {{ $u->email }}</div>
                                <small class="text-muted"><i class="bi bi-telephone me-1"></i> {{ $u->profil?->nomor_telepon ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="text-dark fw-medium">{{ $u->profil?->asal_kampus ?? '-' }}</div>
                                <small class="text-muted">{{ $u->profil?->statusPengguna?->nama_status ?? '-' }}</small>
                            </td>
                            <td>
                                @if($u->role_id == 1)
                                    <span class="badge px-3 py-1 fw-semibold" style="background: rgba(13, 148, 136, 0.12); color: #0f766e; border: 1px solid rgba(13, 148, 136, 0.3);">
                                        <i class="bi bi-shield-check me-1"></i> Sekretaris
                                    </span>
                                @elseif($u->role_id == 2)
                                    <span class="badge px-3 py-1 fw-semibold" style="background: rgba(30, 64, 175, 0.12); color: #1e40af; border: 1px solid rgba(30, 64, 175, 0.3);">
                                        <i class="bi bi-award me-1"></i> Ketua
                                    </span>
                                @elseif($u->role_id == 3)
                                    <span class="badge px-3 py-1 fw-semibold" style="background: rgba(107, 33, 168, 0.12); color: #6b21a8; border: 1px solid rgba(107, 33, 168, 0.3);">
                                        <i class="bi bi-mortarboard me-1"></i> Penelaah
                                    </span>
                                @else
                                    <span class="badge px-3 py-1 fw-semibold" style="background: rgba(217, 119, 6, 0.12); color: #b45309; border: 1px solid rgba(217, 119, 6, 0.3);">
                                        <i class="bi bi-person me-1"></i> Peneliti
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                        <i class="bi bi-dash-circle me-1"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <form action="{{ route('sekretaris.pengguna.toggle', $u->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }}" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;" title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            <i class="bi bi-power fs-6"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-outline-primary" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalEditPengguna{{ $u->id }}" title="Edit Pengguna">
                                        <i class="bi bi-pencil fs-6"></i>
                                    </button>

                                    <form action="{{ route('sekretaris.pengguna.delete', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;" title="Hapus Akun">
                                            <i class="bi bi-trash fs-6"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- MODAL EDIT PENGGUNA --}}
                                <div class="modal fade text-start" id="modalEditPengguna{{ $u->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 16px;">
                                            <form action="{{ route('sekretaris.pengguna.update', $u->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Data Pengguna</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Nama Lengkap & Gelar *</label>
                                                            <input type="text" name="nama_lengkap" class="form-control form-control-modern" value="{{ $u->nama_lengkap }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Alamat Email *</label>
                                                            <input type="email" name="email" class="form-control form-control-modern" value="{{ $u->email }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Hak Akses (Role) *</label>
                                                            <select name="role_id" class="form-select form-control-modern" required>
                                                                @foreach($roles as $r)
                                                                    <option value="{{ $r->id }}" {{ $u->role_id == $r->id ? 'selected' : '' }}>{{ $r->nama_role }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Status Pengguna *</label>
                                                            <select name="status_id" class="form-select form-control-modern" required>
                                                                @foreach($statuses as $st)
                                                                    <option value="{{ $st->id }}" {{ $u->profil?->status_id == $st->id ? 'selected' : '' }}>{{ $st->nama_status }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">NIK</label>
                                                            <input type="text" name="nik" class="form-control form-control-modern" value="{{ $u->profil?->nik }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Nomor Telepon/WA</label>
                                                            <input type="text" name="nomor_telepon" class="form-control form-control-modern" value="{{ $u->profil?->nomor_telepon }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Asal Instansi/Kampus</label>
                                                            <input type="text" name="asal_kampus" class="form-control form-control-modern" value="{{ $u->profil?->asal_kampus }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Status Akun</label>
                                                            <select name="is_active" class="form-select form-control-modern" required>
                                                                <option value="1" {{ $u->is_active ? 'selected' : '' }}>Aktif</option>
                                                                <option value="0" {{ !$u->is_active ? 'selected' : '' }}>Nonaktif</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label small fw-bold">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                                                            <input type="password" name="password" class="form-control form-control-modern" placeholder="Masukkan sandi baru...">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary btn-modern" style="background: #0d9488; border: none;">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ditemukan data pengguna yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $users->links() }}
        </div>
    @endif
</div>

{{-- MODAL TAMBAH PENGGUNA --}}
<div class="modal fade" id="modalTambahPengguna" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <form action="{{ route('sekretaris.pengguna.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill text-primary me-2"></i> Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap & Gelar *</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-modern" required placeholder="Contoh: Dr. Budi, Sp.PD / Ahmad, S.Kep">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Alamat Email (Untuk Login) *</label>
                            <input type="email" name="email" class="form-control form-control-modern" required placeholder="email@domain.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kata Sandi Awal *</label>
                            <input type="password" name="password" class="form-control form-control-modern" required minlength="5" placeholder="Minimal 5 karakter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Hak Akses (Role) *</label>
                            <select name="role_id" class="form-select form-control-modern" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Pengguna *</label>
                            <select name="status_id" class="form-select form-control-modern" required>
                                @foreach($statuses as $st)
                                    <option value="{{ $st->id }}">{{ $st->nama_status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">NIK</label>
                            <input type="text" name="nik" class="form-control form-control-modern" placeholder="Nomor Induk Kependudukan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nomor Telepon/WA</label>
                            <input type="text" name="nomor_telepon" class="form-control form-control-modern" placeholder="08xxxxxxxxxx">
                            <small class="text-muted" style="font-size: 0.72rem;">Digunakan untuk menerima notifikasi & kredensial login</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Asal Instansi/Kampus</label>
                            <input type="text" name="asal_kampus" class="form-control form-control-modern" placeholder="RSUD / Universitas...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Akun</label>
                            <select name="is_active" class="form-select form-control-modern" required>
                                <option value="1" selected>Langsung Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="p-3 rounded-3 border" style="background: rgba(13, 148, 136, 0.05); border-color: rgba(13, 148, 136, 0.2) !important;">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="kirim_wa" value="1" id="kirimWaCheck" checked style="cursor: pointer;">
                                    <label class="form-check-label small fw-semibold text-dark" for="kirimWaCheck" style="cursor: pointer;">
                                        <i class="bi bi-whatsapp text-success me-1"></i> Kirimkan username/email dan password via WhatsApp ke nomor di atas
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1 ps-4" style="font-size: 0.72rem;">
                                    Pesan sambutan berisi kredensial login akan otomatis dikirim jika nomor telepon/WA diisi.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-modern" style="background: #0d9488; border: none;">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection