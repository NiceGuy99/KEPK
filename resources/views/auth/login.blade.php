<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - e-KEPK ETHOS RSUD Sidoarjo Barat</title>
    <link rel="icon" href="{{ asset('image/logo1.jpg') }}" type="image/jpeg">

    <!-- Anti-FOUC Theme Preloader -->
    <script>
        (function() {
            try {
                const storedTheme = localStorage.getItem('kepk_theme');
                const theme = storedTheme || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.setAttribute('data-bs-theme', theme);
            } catch (e) {
                console.error(e);
            }
        })();
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --secondary: #0284c7;
            --primary-gradient: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);
            
            --body-bg: linear-gradient(135deg, #0f172a 0%, #0f3d3e 50%, #042f2e 100%);
            --card-bg: rgba(255, 255, 255, 0.96);
            --header-bg: #ffffff;
            --border-color: #f1f5f9;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --input-bg: #f8fafc;
            --input-border: #cbd5e1;
            --input-focus-bg: #ffffff;
            --demo-btn-bg: #f1f5f9;
            --demo-btn-border: #e2e8f0;
            --demo-btn-text: #475569;
        }

        [data-theme="dark"], [data-bs-theme="dark"] {
            --body-bg: linear-gradient(135deg, #050b14 0%, #071e22 50%, #041619 100%);
            --card-bg: rgba(19, 29, 46, 0.95);
            --header-bg: #151f30;
            --border-color: #243044;
            --text-heading: #f8fafc;
            --text-body: #cbd5e1;
            --text-muted: #94a3b8;
            --input-bg: #0f172a;
            --input-border: #334155;
            --input-focus-bg: #151f30;
            --demo-btn-bg: #1e293b;
            --demo-btn-border: #334155;
            --demo-btn-text: #cbd5e1;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 30px 15px; 
            background: var(--body-bg);
            position: relative;
            overflow-x: hidden;
            transition: background 0.3s ease;
        }

        /* Ambient glowing circles */
        body::before {
            content: '';
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.25) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            border-radius: 50%;
            z-index: 0;
            filter: blur(40px);
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(2, 132, 199, 0.2) 0%, transparent 70%);
            bottom: -100px;
            right: -100px;
            border-radius: 50%;
            z-index: 0;
            filter: blur(40px);
        }

        /* Floating Theme Toggle */
        .btn-login-theme {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-login-theme:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            color: #38bdf8;
            border-color: rgba(56, 189, 248, 0.4);
        }

        .login-card { 
            border: 1px solid rgba(255, 255, 255, 0.15); 
            border-radius: 24px; 
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4); 
            overflow: hidden; 
            width: 100%; 
            max-width: 460px; 
            background: var(--card-bg); 
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            position: relative;
            z-index: 1;
            transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .login-header { 
            background: var(--header-bg); 
            padding: 30px 25px 15px; 
            text-align: center; 
            border-bottom: 1px solid var(--border-color); 
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .login-logo { 
            max-width: 220px; 
            height: 54px; 
            object-fit: contain; 
            margin-bottom: 8px; 
        }

        .login-body { 
            padding: 28px 30px 32px; 
        }

        .login-card h5, .login-card label {
            color: var(--text-heading) !important;
            transition: color 0.3s ease;
        }

        .login-card .text-muted {
            color: var(--text-muted) !important;
            transition: color 0.3s ease;
        }

        .input-group-text { 
            background-color: var(--input-bg); 
            border: 1px solid var(--input-border); 
            border-right: none; 
            border-radius: 12px 0 0 12px; 
            color: var(--text-muted); 
            padding: 12px 14px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .form-control-modern { 
            border-radius: 0 12px 12px 0; 
            padding: 12px 14px; 
            border: 1px solid var(--input-border); 
            border-left: none; 
            background-color: var(--input-bg); 
            color: var(--text-heading);
            font-size: 0.95rem; 
            transition: all 0.2s ease;
        }

        .form-control-modern:focus { 
            box-shadow: none; 
            background-color: var(--input-focus-bg); 
            color: var(--text-heading);
            border-color: var(--primary); 
        }

        .input-group:focus-within .input-group-text, 
        .input-group:focus-within .form-control-modern,
        .input-group:focus-within .btn-toggle-pw { 
            border-color: var(--primary); 
            color: var(--primary); 
        }

        .btn-toggle-pw {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: var(--text-muted);
            padding: 0 14px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-toggle-pw:hover {
            color: var(--primary);
        }

        .btn-modern { 
            background: var(--primary-gradient); 
            border: none; 
            border-radius: 12px; 
            font-weight: 700; 
            padding: 13px; 
            letter-spacing: 0.5px; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            color: white; 
            width: 100%; 
            box-shadow: 0 6px 18px rgba(13, 148, 136, 0.3);
        }

        .btn-modern:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 24px rgba(13, 148, 136, 0.4); 
            color: white; 
        }

        .demo-role-btn {
            background: var(--demo-btn-bg);
            border: 1px solid var(--demo-btn-border);
            border-radius: 10px;
            padding: 6px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--demo-btn-text);
            transition: all 0.2s;
            cursor: pointer;
        }

        .demo-role-btn:hover {
            background: rgba(13, 148, 136, 0.16);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<!-- Floating Theme Switcher -->
<div class="position-absolute top-0 end-0 p-3 p-md-4" style="z-index: 10;">
    <button type="button" class="btn-login-theme" id="loginThemeToggle" aria-label="Ganti Mode Gelap / Terang" title="Beralih Mode Gelap / Terang">
        <i class="bi bi-moon-stars-fill theme-icon-moon text-info"></i>
        <i class="bi bi-sun-fill theme-icon-sun text-warning d-none"></i>
        <span class="ms-1 fw-semibold theme-text" style="font-size: 0.8rem;">Gelap</span>
    </button>
</div>

<div class="login-card">
    <div class="login-header">
        <img src="{{ asset('image/logo.png') }}" alt="Logo e-KEPK" class="login-logo">
        <h5 class="fw-bold mb-0" style="letter-spacing: 0.3px;">RSUD SIDOARJO BARAT</h5>
        <div class="text-muted small fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">Komite Etik Penelitian Kesehatan (KEPK)</div>
    </div>

    <div class="login-body">
        <div class="text-center mb-4">
            <h5 class="fw-bold mb-1">Masuk ke Sistem ETHOS</h5>
            <p class="text-muted small mb-0">Silakan masukkan email dan kata sandi akun Anda</p>
        </div>
        
        @if(session('pesan_error'))
            <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-3 d-flex align-items-center" style="font-size: 0.85rem; border-left: 4px solid #ef4444 !important;">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2.5 text-danger flex-shrink-0"></i>
                <div>{{ session('pesan_error') }}</div>
            </div>
        @endif

        @if(session('pesan_sukses_logout'))
            <div class="alert alert-success shadow-sm border-0 rounded-3 mb-3 d-flex align-items-center" style="font-size: 0.85rem; border-left: 4px solid #10b981 !important;">
                <i class="bi bi-check-circle-fill fs-5 me-2.5 text-success flex-shrink-0"></i>
                <div>{{ session('pesan_sukses_logout') }}</div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" id="formLogin">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Alamat Email</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" id="emailInput" class="form-control form-control-modern" required placeholder="nama@rsud.com" value="{{ old('email') }}" style="border-radius: 0 12px 12px 0;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Kata Sandi</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control form-control-modern" required placeholder="••••••••" style="border-radius: 0;">
                    <button class="btn-toggle-pw" type="button" id="togglePassword" title="Tampilkan/Sembunyikan Sandi">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-modern mt-2" id="btnSubmit">
                <span>Masuk Sekarang</span>
                <i class="bi bi-arrow-right-short ms-1 fs-5 align-middle"></i>
            </button>
        </form>

        <!-- Quick Demo Accounts Helper -->
        <div class="mt-4 pt-3 border-top text-center" style="border-color: var(--border-color) !important;">
            <span class="text-muted fw-semibold d-block mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px; text-transform: uppercase;">
                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Klik untuk Masuk Cepat (Akun Demo)
            </span>
            <div class="d-flex flex-wrap gap-1.5 justify-content-center">
                <button type="button" class="demo-role-btn" onclick="fillLogin('sekretaris@rsud.com', 'password', 'Sekretaris')">
                    <i class="bi bi-shield-check text-success me-1"></i> Sekretaris
                </button>
                <button type="button" class="demo-role-btn" onclick="fillLogin('ketua@rsud.com', 'password', 'Ketua')">
                    <i class="bi bi-person-badge text-primary me-1"></i> Ketua KEPK
                </button>
                <button type="button" class="demo-role-btn" onclick="fillLogin('penelaah@rsud.com', 'password', 'Penelaah')">
                    <i class="bi bi-clipboard2-check text-info me-1"></i> Penelaah
                </button>
                <button type="button" class="demo-role-btn" onclick="fillLogin('peneliti.mhs@univ.ac.id', 'password', 'Peneliti')">
                    <i class="bi bi-journal-text text-secondary me-1"></i> Peneliti
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Theme Switcher Logic for Login Page
    const loginThemeToggle = document.getElementById('loginThemeToggle');

    function syncLoginThemeUI(theme) {
        if (!loginThemeToggle) return;
        const moonIcon = loginThemeToggle.querySelector('.theme-icon-moon');
        const sunIcon = loginThemeToggle.querySelector('.theme-icon-sun');
        const textLabel = loginThemeToggle.querySelector('.theme-text');
        const isDark = (theme === 'dark');

        if (isDark) {
            if (moonIcon) moonIcon.classList.add('d-none');
            if (sunIcon) sunIcon.classList.remove('d-none');
            if (textLabel) textLabel.textContent = 'Terang';
            loginThemeToggle.setAttribute('title', 'Beralih ke Mode Terang (Light Mode)');
        } else {
            if (moonIcon) moonIcon.classList.remove('d-none');
            if (sunIcon) sunIcon.classList.add('d-none');
            if (textLabel) textLabel.textContent = 'Gelap';
            loginThemeToggle.setAttribute('title', 'Beralih ke Mode Gelap (Dark Mode)');
        }
    }

    function applyLoginTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('kepk_theme', theme);
        syncLoginThemeUI(theme);
    }

    // Initialize toggle button state based on active theme
    const activeTheme = document.documentElement.getAttribute('data-theme') || 'light';
    syncLoginThemeUI(activeTheme);

    if (loginThemeToggle) {
        loginThemeToggle.addEventListener('click', function() {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = (current === 'dark') ? 'light' : 'dark';
            applyLoginTheme(next);
        });
    }

    // Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (togglePassword && passwordInput && toggleIcon) {
        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.className = isPassword ? 'bi bi-eye-slash text-primary' : 'bi bi-eye';
        });
    }

    // Quick Fill Demo Accounts
    function fillLogin(email, password, role) {
        const emailInput = document.getElementById('emailInput');
        const passInput = document.getElementById('passwordInput');
        if (emailInput && passInput) {
            emailInput.value = email;
            passInput.value = password;
            emailInput.focus();
        }
    }
</script>
</body>
</html>
