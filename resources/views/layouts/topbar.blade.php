<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn-toggle-sidebar" id="sidebarToggle" title="Sembunyikan/Tampilkan Menu">
            <i class="bi bi-list fs-5"></i>
        </button>
        
        <div class="d-flex align-items-center gap-2 ms-2">
            <img src="{{ asset('image/logo1.jpg') }}" alt="Logo ETHOS" style="height: 52px; width: auto; object-fit: contain; border-radius: 6px;">
            <div class="d-none d-sm-block mt-1">
                <h4 class="fw-bold mb-0" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.25rem;">
                    ETHOS
                </h4>
                <div class="text-muted fw-medium" style="font-size: 0.65rem; letter-spacing: 0.5px; margin-top: -2px;">RSUD SIDOARJO BARAT</div>
            </div>
        </div>
    </div>

    <div class="dropdown">
        <button class="btn btn-light bg-transparent border-0 dropdown-toggle d-flex align-items-center p-1" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="text-end me-3 d-none d-md-block">
                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1.2;">{{ Auth::user()?->nama_lengkap ?? 'Pengguna' }}</div>
                <div class="fw-semibold" style="font-size: 0.75rem; color: #0d9488;">{{ ucfirst(Auth::user()?->role_name ?? '') }}</div>
            </div>
            <div class="text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background: var(--primary-gradient);">
                <i class="bi bi-person-fill fs-5"></i>
            </div>
        </button>
        
        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="profileDropdown" style="border-radius: 12px; min-width: 220px;">
            <li>
                <a class="dropdown-item py-2 d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modalLihatProfil">
                    <i class="bi bi-person me-3 text-secondary fs-5"></i> Profil
                </a>
            </li>
            <li>
                <a class="dropdown-item py-2 d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modalGantiPassword">
                    <i class="bi bi-key me-3 text-secondary fs-5"></i> Ganti Password
                </a>
            </li>
            <li><hr class="dropdown-divider my-1"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 d-flex align-items-center text-danger" onclick="return confirm('Apakah Anda yakin ingin keluar dari aplikasi?');">
                        <i class="bi bi-box-arrow-right me-3 fs-5"></i> Log Out
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
