<div class="topbar">
    <div class="d-flex align-items-center gap-2 gap-md-3">
        <button class="btn-toggle-sidebar" id="sidebarToggle" title="Sembunyikan/Tampilkan Menu Navigasi">
            <i class="bi bi-list fs-5"></i>
        </button>
        
        <!-- Live Greeting & Date Status -->
        <div class="d-none d-lg-flex align-items-center gap-3 ms-1">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-teal-subtle text-teal border px-2.5 py-1.5 rounded-pill d-flex align-items-center gap-1.5" style="background: rgba(13, 148, 136, 0.08); color: #0d9488; border-color: rgba(13, 148, 136, 0.2) !important; font-size: 0.76rem;">
                    <span class="pulse-indicator"></span>
                    <span class="fw-semibold">e-KEPK Online</span>
                </span>
            </div>
            <div class="text-muted small d-flex align-items-center gap-2" style="font-size: 0.82rem;">
                <span id="dynamicGreeting" class="fw-semibold text-dark">Selamat Datang</span>
                <span class="text-muted opacity-50">•</span>
                <span><i class="bi bi-calendar3 me-1 text-primary"></i> {{ date('d M Y') }}</span>
                <span class="text-muted opacity-50">•</span>
                <span class="font-monospace fw-medium text-dark"><i class="bi bi-clock me-1 text-primary"></i><span id="liveTimeClock">00:00:00</span></span>
            </div>
        </div>
    </div>

    <!-- Right Controls: Notification Center, Theme Toggle & Profile Menu -->
    <div class="d-flex align-items-center gap-2 gap-sm-3">
        <!-- Notification Center Dropdown -->
        <div class="dropdown">
            <button class="btn btn-light bg-transparent border-0 rounded-circle p-2 position-relative notification-bell-btn" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Pusat Notifikasi">
                <i class="bi bi-bell fs-5 text-dark"></i>
                @if(isset($topbarUnreadCount) && $topbarUnreadCount > 0)
                    <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem; padding: 0.25em 0.45em;">
                        {{ $topbarUnreadCount > 9 ? '9+' : $topbarUnreadCount }}
                        <span class="visually-hidden">unread notifications</span>
                    </span>
                    <span class="notification-pulse"></span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0 notification-dropdown-menu" aria-labelledby="notificationDropdown" style="width: 330px; border-radius: 18px; box-shadow: var(--dropdown-shadow) !important; overflow: hidden; background: var(--card-bg);">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bell-fill text-primary"></i>
                        <h6 class="m-0 fw-bold text-dark" style="font-size: 0.92rem;">Notifikasi & Aktivitas</h6>
                    </div>
                    @if(isset($topbarUnreadCount) && $topbarUnreadCount > 0)
                        <span class="badge bg-teal text-white rounded-pill" style="font-size: 0.7rem; background: var(--primary);">{{ $topbarUnreadCount }} Baru</span>
                    @endif
                </div>

                <div class="notification-list" style="max-height: 320px; overflow-y: auto;">
                    @if(isset($topbarNotifications) && $topbarNotifications->isNotEmpty())
                        @foreach($topbarNotifications as $notif)
                            <a href="{{ $notif['url'] }}" class="dropdown-item p-3 border-bottom d-flex gap-3 align-items-start notification-item" style="border-color: var(--border-color) !important; white-space: normal; transition: background 0.15s ease;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: {{ $notif['bg'] ?? 'rgba(13, 148, 136, 0.1)' }};">
                                    <i class="bi {{ $notif['icon'] ?? 'bi-info-circle' }} fs-5"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.83rem;">{{ $notif['title'] }}</div>
                                    <div class="text-muted small text-truncate-2 mt-0.5" style="font-size: 0.77rem; line-height: 1.3;">{{ $notif['text'] }}</div>
                                    <div class="text-muted mt-1" style="font-size: 0.68rem;"><i class="bi bi-clock me-1"></i>{{ $notif['time'] }}</div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-4 px-3 text-muted">
                            <i class="bi bi-bell-slash fs-2 d-block mb-1 text-secondary opacity-50"></i>
                            <div class="small fw-medium">Belum ada notifikasi baru</div>
                            <div style="font-size: 0.72rem;">Semua tugas dan berkas telah diperbarui</div>
                        </div>
                    @endif
                </div>

                <div class="p-2 border-top text-center bg-light-subtle" style="border-color: var(--border-color) !important;">
                    <small class="text-muted fw-semibold" style="font-size: 0.75rem;">Sistem e-KEPK ETHOS RSUD Sibar</small>
                </div>
            </div>
        </div>

        <!-- Theme Toggle Button (Light / Dark Mode) -->
        <button type="button" class="btn-theme-toggle shadow-xs" id="themeToggleBtn" aria-label="Ganti Mode Gelap / Terang" title="Klik untuk beralih mode Gelap / Terang">
            <span class="theme-icon-container">
                <i class="bi bi-moon-stars-fill theme-icon-moon"></i>
                <i class="bi bi-sun-fill theme-icon-sun d-none"></i>
            </span>
            <span class="d-none d-sm-inline ms-1 theme-text">Gelap</span>
        </button>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-light bg-transparent border-0 dropdown-toggle d-flex align-items-center p-1 rounded-pill" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="transition: all 0.2s;">
                <div class="text-end me-2 me-sm-3 d-none d-sm-block">
                    <div class="fw-bold text-dark" style="font-size: 0.88rem; line-height: 1.2;">{{ Auth::user()?->nama_lengkap ?? 'Pengguna' }}</div>
                    <div class="fw-semibold" style="font-size: 0.72rem; color: #0d9488;">{{ ucfirst(Auth::user()?->role_name ?? '') }}</div>
                </div>
                <div class="text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; background: var(--primary-gradient);">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
            </button>
            
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2" aria-labelledby="profileDropdown" style="border-radius: 16px; min-width: 240px; box-shadow: var(--dropdown-shadow) !important;">
                <li class="px-3 py-2 border-bottom mb-1" style="border-color: var(--border-color) !important;">
                    <div class="fw-bold text-dark text-truncate">{{ Auth::user()?->nama_lengkap ?? 'Pengguna' }}</div>
                    <div class="text-muted small text-truncate">{{ Auth::user()?->email }}</div>
                    <span class="badge bg-primary-subtle text-primary mt-1" style="font-size: 0.7rem;">{{ ucfirst(Auth::user()?->role_name ?? '') }}</span>
                </li>
                <li>
                    <a class="dropdown-item py-2 px-3 rounded-3 d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modalLihatProfil">
                        <i class="bi bi-person-circle me-2.5 text-secondary fs-5"></i> Profil Pengguna
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2 px-3 rounded-3 d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modalGantiPassword">
                        <i class="bi bi-shield-lock me-2.5 text-secondary fs-5"></i> Ganti Password
                    </a>
                </li>
                <li>
                    <button type="button" class="dropdown-item py-2 px-3 rounded-3 d-flex align-items-center justify-content-between" id="dropdownThemeToggle">
                        <span class="d-flex align-items-center"><i class="bi bi-circle-half me-2.5 text-secondary fs-5"></i> Mode Tampilan</span>
                        <span class="badge border theme-badge-status" style="font-size: 0.72rem;">Gelap</span>
                    </button>
                </li>
                <li><hr class="dropdown-divider my-1" style="border-color: var(--border-color) !important;"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                        @csrf
                        <button type="button" class="dropdown-item py-2 px-3 rounded-3 d-flex align-items-center text-danger" id="btnLogoutTrigger">
                            <i class="bi bi-box-arrow-right me-2.5 fs-5"></i> Keluar (Logout)
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
