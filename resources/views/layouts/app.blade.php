<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem ETHOS') - RSUD Sidoarjo Barat</title>
    <link rel="icon" href="{{ asset('image/logo1.jpg') }}" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);
            --sidebar-bg: #ffffff;
            --body-bg: #f0fdf4;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--body-bg); 
            color: #334155;
            overflow-x: hidden;
            margin: 0;
        }
        
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            box-shadow: 4px 0 25px rgba(13, 148, 136, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding-top: 0 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #sidebar.hide-sidebar {
            margin-left: -260px;
        }
        
        #main-content {
            width: calc(100% - 260px);
            margin-left: 260px;
            min-height: 100vh;
            display: block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #main-content.expand-content {
            width: 100%;
            margin-left: 0;
        }

        .sidebar-logo {
            padding: 22px 25px;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(to right, #f0fdf4, #ffffff);
        }

        .nav-sidebar .nav-link {
            color: #64748b;
            padding: 12px 22px;
            font-weight: 500;
            border-radius: 0 30px 30px 0;
            margin-bottom: 5px;
            margin-right: 20px;
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active {
            background: var(--primary-gradient);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.25);
        }

        .nav-sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.15rem;
            vertical-align: text-bottom;
        }

        /* Topbar Fixed di atas area konten kanan */
        .topbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            position: fixed;
            top: 0;
            right: 0;
            width: calc(100% - 260px);
            z-index: 1050; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Pembungkus konten agar tidak tertutup topbar fixed */
        .content-wrapper {
            margin-top: 80px;
        }

        .dropdown-menu {
            z-index: 1060 !important; 
        }

        .btn-toggle-sidebar {
            background: #f1f5f9;
            border: none;
            color: #0d9488;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-toggle-sidebar:hover {
            background: #0d9488;
            color: #fff;
        }

        .card-modern { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(13, 148, 136, 0.04); background: #ffffff; }
        .form-control-modern, .form-select-modern { 
            border-radius: 10px; border: 1px solid #e2e8f0; background-color: #f8fafc; padding: 10px 15px; font-size: 0.95rem;
        }
        .form-control-modern:focus, .form-select-modern:focus { 
            border-color: #0d9488; box-shadow: 0 0 0 0.25rem rgba(13, 148, 136, 0.15); background-color: #ffffff; 
        }
        .btn-modern { border-radius: 10px; font-weight: 500; transition: all 0.3s; }
        .btn-modern:hover { transform: translateY(-2px); }
    </style>
    @stack('styles')
</head>
<body>  

    @include('layouts.sidebar')   

    <div id="main-content">
        @include('layouts.topbar')  

        <div class="content-wrapper container-fluid px-4 pb-5">
            @if(session('pesan_sukses'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {!! session('pesan_sukses') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('pesan_error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {!! session('pesan_error') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('pesan_global'))
                {!! session('pesan_global') !!}
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i> <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-1 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            @yield('content')
        </div>

        @include('layouts.footer')
    </div>

@include('layouts.modals.profil')
@include('layouts.modals.ganti_password')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const topbar = document.querySelector('.topbar');

        if(sidebarToggle && sidebar && mainContent) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('hide-sidebar');
                mainContent.classList.toggle('expand-content');
                if(topbar) {
                    topbar.style.width = sidebar.classList.contains('hide-sidebar') ? '100%' : 'calc(100% - 260px)';
                }
            });
        }
    });
</script>
@stack('scripts')
</body>
</html>