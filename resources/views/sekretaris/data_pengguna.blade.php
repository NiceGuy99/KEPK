@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Manajemen Pengguna</h3>
        <p class="text-muted mb-0">Kelola akun peneliti, sekretariat, penelaah, dan ketua komite etik.</p>
    </div>
    <button class="btn btn-primary btn-modern" style="background: #0d9488; border: none;" data-bs-toggle="modal" data-bs-target="#modalTambahPengguna">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
    </button>
</div>

<div class="card card-modern p-3 mb-4">
    <form method="GET" action="{{ route('sekretaris.pengguna') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control form-control-modern" placeholder="Cari nama, NIK, email, kampus..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-select form-control-modern">
                <option value="">-- Semua Hak Akses (Role) --</option>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}" {{ request('role') == $r->id ? 'selected' : '' }}>{{ $r->nama_role }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-modern w-100" style="background: #0d9488; border: none;">
                <i class="bi bi-search me-1"></i> Cari
            </button>
        </div>
        @if(request()->hasAny(['search', 'role']))
            <div class="col-md-2">
                <a href="{{ route('sekretaris.pengguna') }}" class="btn btn-light btn-modern border w-100">Reset</a>
            </div>
        @endif
    </form>
</div>

<div class="card card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama & NIK</th>
                        <th>Email & Kontak</th>
                        <th>Instansi / Kampus</th>
                        <th>Role</th>
                        <th>Status Akun</th>
                        <th class="text-center pe-4" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $idx => $u)
                        <tr>
                            <td class="ps-4 text-muted">{{ $users->firstItem() + $idx }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $u->nama_lengkap }}</div>
                                <small class="text-muted">NIK: {{ $u->profil?->nik ?? '-' }}</small>
                            </td>
                            <td>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> {{ $u->email }}</div>
                                <small class="text-muted"><i class="bi bi-telephone me-1"></i> {{ $u->profil?->nomor_telepon ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="text-dark">{{ $u->profil?->asal_kampus ?? '-' }}</div>
                                <small class="text-muted">{{ $u->profil?->statusPengguna?->nama_status ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border">{{ $u->role?->nama_role ?? '-' }}</span>
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <!-- Tombol Aksi Diseragamkan Ukurannya Menggunakan d-flex & gap-1 -->
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <form action="{{ route('sekretaris.pengguna.toggle', $u->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }}" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            <i class="bi bi-power fs-6"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-outline-primary" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" data-bs-toggle="modal" data-bs-target="#modalEditPengguna{{ $u->id }}" title="Edit Pengguna">
                                        <i class="bi bi-pencil fs-6"></i>
                                    </button>

                                    <form action="{{ route('sekretaris.pengguna.delete', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Akun">
                                            <i class="bi bi-trash fs-6"></i>
                                        </button>
                                    </form>
                                </div>

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
                                                            <label class="form-label small fw-medium">Nama Lengkap & Gelar *</label>
                                                            <input type="text" name="nama_lengkap" class="form-control form-control-modern" value="{{ $u->nama_lengkap }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-medium">Alamat Email *</label>
                                                            <input type="email" name="email" class="form-control form-control-modern" value="{{ $u->email }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-medium">Hak Akses (Role) *</label>
                                                            <select name="role_id" class="form-select form-control-modern" required>
                                                                @foreach($roles as $r)
                                                                    <option value="{{ $r->id }}" {{ $u->role_id == $r->id ? 'selected' : '' }}>{{ $r->nama_role }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-medium">Status Pengguna *</label>
                                                            <select name="status_id" class="form-select form-control-modern" required>
                                                                @foreach($statuses as $st)
                                                                    <option value="{{ $st->id }}" {{ $u->profil?->status_id == $st->id ? 'selected' : '' }}>{{ $st->nama_status }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-medium">NIK</label>
                                                            <input type="text" name="nik" class="form-control form-control-modern" value="{{ $u->profil?->nik }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-medium">Nomor Telepon/WA</label>
                                                            <input type="text" name="nomor_telepon" class="form-control form-control-modern" value="{{ $u->profil?->nomor_telepon }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-medium">Asal Instansi/Kampus</label>
                                                            <input type="text" name="asal_kampus" class="form-control form-control-modern" value="{{ $u->profil?->asal_kampus }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-medium">Status Akun</label>
                                                            <select name="is_active" class="form-select form-control-modern" required>
                                                                <option value="1" {{ $u->is_active ? 'selected' : '' }}>Aktif</option>
                                                                <option value="0" {{ !$u->is_active ? 'selected' : '' }}>Nonaktif</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label small fw-medium">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
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
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ditemukan data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>

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
                            <label class="form-label small fw-medium">Nama Lengkap & Gelar *</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-modern" required placeholder="Contoh: Dr. Budi, Sp.PD / Ahmad, S.Kep">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Alamat Email (Untuk Login) *</label>
                            <input type="email" name="email" class="form-control form-control-modern" required placeholder="email@domain.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Kata Sandi Awal *</label>
                            <input type="password" name="password" class="form-control form-control-modern" required minlength="5" placeholder="Minimal 5 karakter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Hak Akses (Role) *</label>
                            <select name="role_id" class="form-select form-control-modern" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Status Pengguna *</label>
                            <select name="status_id" class="form-select form-control-modern" required>
                                @foreach($statuses as $st)
                                    <option value="{{ $st->id }}">{{ $st->nama_status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">NIK</label>
                            <input type="text" name="nik" class="form-control form-control-modern" placeholder="Nomor Induk Kependudukan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Nomor Telepon/WA</label>
                            <input type="text" name="nomor_telepon" class="form-control form-control-modern" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Asal Instansi/Kampus</label>
                            <input type="text" name="asal_kampus" class="form-control form-label small fw-medium" placeholder="RSUD / Universitas...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Status Akun</label>
                            <select name="is_active" class="form-select form-control-modern" required>
                                <option value="1" selected>Langsung Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
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