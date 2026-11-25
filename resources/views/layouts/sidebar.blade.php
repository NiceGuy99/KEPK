<nav id="sidebar">    
    <!-- Sidebar Header Branding -->
    <div class="sidebar-header">
        <a href="{{ url('/') }}" class="sidebar-brand">
            <img src="{{ asset('image/logo1.jpg') }}" alt="Logo ETHOS RSUD Sibar">
            <div>
                <div class="sidebar-brand-title">ETHOS KEPK</div>
                <div class="sidebar-brand-sub">RSUD Sidoarjo Barat</div>
            </div>
        </a>
    </div>

    <!-- Scrollable Menu Items -->
    <div class="sidebar-menu-wrapper">
        <div class="px-2 mb-2 text-uppercase text-muted fw-bold" style="font-size: 0.68rem; letter-spacing: 1px;">
            Menu Navigasi
        </div>
        <ul class="nav flex-column nav-sidebar">
            @if(isset($sidebarMenus) && $sidebarMenus->isNotEmpty())
                @foreach($sidebarMenus as $menu)
                    @php
                        $isActive = false;
                        $patterns = explode('|', $menu->route_pattern ?? $menu->route_name);
                        foreach ($patterns as $p) {
                            $p = trim($p);
                            if (!empty($p) && request()->routeIs($p)) {
                                $isActive = true;
                                break;
                            }
                        }
                        $menuUrl = Route::has($menu->route_name) ? route($menu->route_name) : url($menu->url ?? '#');
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $menuUrl }}">
                            <i class="{{ $menu->icon }}"></i>
                            <span>{{ $menu->nama_menu }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>

    <!-- Sidebar Footer with User Info -->
    <div class="sidebar-footer">
        <div class="sidebar-user-card shadow-sm">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; background: var(--primary-gradient); font-size: 0.85rem;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.82rem;">{{ Auth::user()?->nama_lengkap ?? 'Pengguna' }}</div>
                    <div class="d-flex align-items-center gap-1" style="font-size: 0.7rem; color: var(--primary);">
                        <span class="pulse-indicator"></span>
                        <span class="fw-semibold">{{ ucfirst(Auth::user()?->role_name ?? '') }}</span>
                    </div>
                </div>
            </div>
            <button class="btn btn-sm btn-light border-0 text-muted p-1" title="Profil Akun" data-bs-toggle="modal" data-bs-target="#modalLihatProfil">
                <i class="bi bi-gear fs-6"></i>
            </button>
        </div>
    </div>
</nav>
