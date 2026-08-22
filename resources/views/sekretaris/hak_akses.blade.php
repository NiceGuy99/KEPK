@extends('layouts.app')

@section('title', 'Manajemen Hak Akses & Menu')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-shield-lock-fill text-teal me-2" style="color: #0d9488;"></i> Manajemen Hak Akses & Menu
        </h3>
        <p class="text-muted mb-0">Atur visibilitas menu dan hak akses untuk setiap peran (Role) secara dinamis.</p>
    </div>
    <div>
        <button type="submit" form="formHakAkses" class="btn btn-primary btn-modern shadow-sm" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Hak Akses
        </button>
    </div>
</div>

{{-- Info Card --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 100%); border-radius: 16px;">
    <div class="card-body p-3 p-md-4 d-flex align-items-center gap-3">
        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(13, 148, 136, 0.15); width: 50px; height: 50px; min-width: 50px;">
            <i class="bi bi-info-circle-fill fs-4" style="color: #0d9488;"></i>
        </div>
        <div class="small">
            <strong class="text-dark">Petunjuk Pengaturan Matriks Hak Akses:</strong>
            <p class="text-muted mb-0">
                Centang kotak pada baris menu dan kolom role yang ingin Anda berikan izin akses. Menu yang dicentang akan otomatis muncul di bilah sisi (*sidebar*) pengguna dengan role tersebut.
            </p>
        </div>
    </div>
</div>

<form id="formHakAkses" method="POST" action="{{ route('sekretaris.hak-akses.update') }}">
    @csrf

    <div class="card card-modern shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-grid-3x3-gap-fill text-muted"></i>
                <span class="fw-semibold text-dark">Matriks Izin Menu per Role</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="input-group input-group-sm" style="width: 260px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="filterMenuInput" class="form-control bg-light border-start-0" placeholder="Cari nama menu...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="matrixTable">
                <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="ps-4 py-3" style="width: 30%;">
                            <span class="text-uppercase small fw-bold text-secondary">Daftar Menu Navigasi</span>
                        </th>
                        <th class="py-3" style="width: 22%;">
                            <span class="text-uppercase small fw-bold text-secondary">Rute / Endpoint</span>
                        </th>
                        @foreach($roles as $role)
                            <th class="text-center py-3" style="min-width: 130px;">
                                <div class="badge rounded-pill mb-1 px-3 py-2 fs-7" 
                                     style="background: {{ $role->id == 1 ? '#0d9488' : ($role->id == 2 ? '#2563eb' : ($role->id == 3 ? '#d97706' : '#64748b')) }}; color: #ffffff;">
                                    {{ $role->nama_role }}
                                </div>
                                <div class="d-flex justify-content-center gap-1 mt-1">
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1 check-all-col" data-role="{{ $role->id }}" title="Centang Semua">
                                        <i class="bi bi-check-all"></i> Semua
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1 uncheck-all-col" data-role="{{ $role->id }}" title="Hapus Semua">
                                        <i class="bi bi-x"></i> Kosong
                                    </button>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($menusByKategori as $kategori => $groupMenus)
                        <tr class="table-group-header" style="background-color: #f1f5f9;">
                            <td colspan="{{ 2 + $roles->count() }}" class="ps-4 py-2">
                                <span class="badge bg-secondary text-uppercase px-2 py-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                    <i class="bi bi-folder2-open me-1"></i> Kategori: {{ $kategori }}
                                </span>
                            </td>
                        </tr>
                        @foreach($groupMenus as $menu)
                            <tr class="menu-row">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 p-2 me-3 d-flex align-items-center justify-content-center shadow-xs" 
                                             style="background: #f0fdf4; width: 36px; height: 36px; color: #0d9488;">
                                            <i class="{{ $menu->icon }} fs-6"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark menu-name">{{ $menu->nama_menu }}</div>
                                        </div>

                                    </div>
                                </td>
                                <td>
                                    <code class="text-muted small px-2 py-1 bg-light rounded border border-light-subtle">
                                        {{ $menu->route_name }}
                                    </code>
                                </td>
                                @foreach($roles as $role)
                                    @php
                                        $hasAccess = $role->menus->contains('id', $menu->id);
                                    @endphp
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input role-chk-{{ $role->id }}" 
                                                   type="checkbox" 
                                                   name="permissions[{{ $role->id }}][{{ $menu->id }}]" 
                                                   value="1" 
                                                   {{ $hasAccess ? 'checked' : '' }}
                                                   style="cursor: pointer; width: 2.5em; height: 1.3em;">
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">
                <i class="bi bi-info-circle me-1"></i> Total Menu: <strong>{{ $menus->count() }}</strong> item terdaftar.
            </span>
            <button type="submit" class="btn btn-primary btn-modern shadow-sm" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
                <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Hak Akses
            </button>
        </div>
    </div>
</form>

<style>
    .table-group-header {
        border-top: 2px solid #e2e8f0;
    }
    .form-check-input:checked {
        background-color: #0d9488;
        border-color: #0d9488;
    }
    .btn-xs {
        font-size: 0.72rem;
        border-radius: 4px;
    }
    .card-modern {
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick Check All per Role Column
    document.querySelectorAll('.check-all-col').forEach(btn => {
        btn.addEventListener('click', function() {
            const roleId = this.dataset.role;
            document.querySelectorAll('.role-chk-' + roleId).forEach(chk => {
                chk.checked = true;
            });
        });
    });

    // Quick Uncheck All per Role Column
    document.querySelectorAll('.uncheck-all-col').forEach(btn => {
        btn.addEventListener('click', function() {
            const roleId = this.dataset.role;
            document.querySelectorAll('.role-chk-' + roleId).forEach(chk => {
                chk.checked = false;
            });
        });
    });

    // Live Filter Menu
    const filterInput = document.getElementById('filterMenuInput');
    if (filterInput) {
        filterInput.addEventListener('keyup', function() {
            const filterVal = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.menu-row');
            
            rows.forEach(row => {
                const name = row.querySelector('.menu-name').textContent.toLowerCase();
                const route = row.querySelector('code').textContent.toLowerCase();
                if (name.includes(filterVal) || route.includes(filterVal)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection
