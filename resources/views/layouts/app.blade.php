<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem e-KEPK ETHOS') - RSUD Sidoarjo Barat</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Brand Identity Colors */
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #14b8a6;
            --secondary: #0284c7;
            --primary-gradient: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);
            --primary-gradient-soft: linear-gradient(135deg, rgba(13, 148, 136, 0.1) 0%, rgba(2, 132, 199, 0.1) 100%);
            
            /* Light Theme Tokens */
            --sidebar-width: 270px;
            --body-bg: #f8fafc;
            --body-bg-gradient: radial-gradient(at 0% 0%, rgba(13, 148, 136, 0.035) 0px, transparent 50%),
                                 radial-gradient(at 100% 100%, rgba(2, 132, 199, 0.03) 0px, transparent 50%);
            --card-bg: #ffffff;
            --surface-bg: #f1f5f9;
            --sidebar-bg: #ffffff;
            --sidebar-header-bg: #ffffff;
            --sidebar-footer-bg: #fafafa;
            --sidebar-border: rgba(226, 232, 240, 0.8);
            --sidebar-link-color: #475569;
            --sidebar-link-hover-bg: rgba(13, 148, 136, 0.08);

            --topbar-bg: rgba(255, 255, 255, 0.92);
            --topbar-border: rgba(226, 232, 240, 0.8);

            --heading-color: #0f172a;
            --text-color: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --input-bg: #f8fafc;
            --input-border: #cbd5e1;
            --input-focus-bg: #ffffff;

            --card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
            --card-hover-shadow: 0 16px 32px -4px rgba(13, 148, 136, 0.12), 0 8px 16px -4px rgba(13, 148, 136, 0.06);
            --dropdown-bg: #ffffff;
            --dropdown-border: #e2e8f0;
            --dropdown-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Dark Theme Design System Tokens */
        [data-theme="dark"], [data-bs-theme="dark"] {
            --body-bg: #0b1120;
            --body-bg-gradient: radial-gradient(at 0% 0%, rgba(13, 148, 136, 0.12) 0px, transparent 50%),
                                 radial-gradient(at 100% 100%, rgba(2, 132, 199, 0.09) 0px, transparent 50%);
            --card-bg: #1e293b;
            --surface-bg: #151f30;
            --sidebar-bg: #131d2e;
            --sidebar-header-bg: #131d2e;
            --sidebar-footer-bg: #0f172a;
            --sidebar-border: #243044;
            --sidebar-link-color: #94a3b8;
            --sidebar-link-hover-bg: rgba(13, 148, 136, 0.18);

            --topbar-bg: rgba(19, 29, 46, 0.92);
            --topbar-border: #243044;

            --heading-color: #f8fafc;
            --text-color: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #2b3950;
            --input-bg: #0f172a;
            --input-border: #334155;
            --input-focus-bg: #151f30;

            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.35), 0 2px 6px -1px rgba(0, 0, 0, 0.25);
            --card-hover-shadow: 0 16px 32px -4px rgba(13, 148, 136, 0.25), 0 8px 16px -4px rgba(0, 0, 0, 0.4);
            --dropdown-bg: #1e293b;
            --dropdown-border: #334155;
            --dropdown-shadow: 0 14px 40px rgba(0, 0, 0, 0.5);
        }

        * {
            box-sizing: border-box;
        }

        /* Dynamic NProgress Bar */
        #nprogress .bar {
            background: var(--primary) !important;
            height: 3px !important;
            z-index: 99999 !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px var(--primary), 0 0 5px var(--primary) !important;
        }
        #nprogress .spinner {
            top: 16px !important;
            right: 18px !important;
            z-index: 99999 !important;
        }
        #nprogress .spinner-icon {
            border-top-color: var(--primary) !important;
            border-left-color: var(--primary) !important;
            width: 16px !important;
            height: 16px !important;
        }

        /* Page Smooth Entrance Animation */
        .page-animate {
            animation: pageFadeIn 0.25s ease-out;
        }
        @keyframes pageFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Modals & Backdrop Layering Fixes */
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal {
            z-index: 1055 !important;
        }
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
        }
        body.modal-open .topbar {
            right: 0 !important;
            padding-right: 28px !important;
        }

        /* Notification Pulse */
        .notification-pulse {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            pointer-events: none;
            animation: pulse-ring 1.8s infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* Stat Numbers Counter */
        .stat-counter {
            display: inline-block;
            font-variant-numeric: tabular-nums;
            transition: all 0.3s ease;
        }

        /* Table Instant Search & Live Highlight */
        .table-row-hidden {
            display: none !important;
        }
        .highlight-match {
            background-color: rgba(254, 240, 138, 0.5);
            border-radius: 3px;
            padding: 0 2px;
        }
        [data-theme="dark"] .highlight-match {
            background-color: rgba(234, 179, 8, 0.3);
            color: #fef08a;
        }

        /* Filter Pills Interactive */
        .filter-pill {
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }
        .filter-pill:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }
        .filter-pill.active {
            box-shadow: 0 0 0 2px var(--primary);
        }

        /* Modern Dropzone */
        .dropzone-box {
            border: 2px dashed var(--border-color);
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            background: var(--surface-bg);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .dropzone-box:hover, .dropzone-box.is-dragover {
            border-color: var(--primary);
            background: rgba(13, 148, 136, 0.04);
            transform: translateY(-2px);
        }

        body { 
            font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; 
            background-color: var(--body-bg); 
            background-image: var(--body-bg-gradient);
            background-attachment: fixed;
            color: var(--text-color);
            overflow-x: hidden;
            margin: 0;
            min-height: 100vh;
            transition: background-color 0.25s ease, color 0.2s ease;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: var(--body-bg);
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
        [data-theme="dark"] ::-webkit-scrollbar-thumb {
            background: #334155;
        }
        [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            box-shadow: 4px 0 30px rgba(15, 23, 42, 0.04);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #sidebar.hide-sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .sidebar-header {
            padding: 20px 22px;
            border-bottom: 1px solid var(--sidebar-border);
            background: var(--sidebar-header-bg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.25s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-brand img {
            height: 44px;
            width: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.15);
            object-fit: contain;
        }

        .sidebar-brand-title {
            font-size: 1.2rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
            letter-spacing: 0.5px;
        }

        .sidebar-brand-sub {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .sidebar-menu-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 16px 14px;
        }

        .nav-sidebar {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-sidebar .nav-item {
            margin-bottom: 4px;
        }

        .nav-sidebar .nav-link {
            color: var(--sidebar-link-color);
            padding: 11px 16px;
            font-weight: 500;
            font-size: 0.92rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        .nav-sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
            color: var(--text-muted);
            transition: transform 0.2s, color 0.2s;
        }

        .nav-sidebar .nav-link:hover {
            color: var(--primary-light);
            background: var(--sidebar-link-hover-bg);
            transform: translateX(3px);
        }

        .nav-sidebar .nav-link:hover i {
            color: var(--primary-light);
            transform: scale(1.1);
        }

        .nav-sidebar .nav-link.active {
            background: var(--primary-gradient);
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(13, 148, 136, 0.28);
        }

        .nav-sidebar .nav-link.active i {
            color: #ffffff !important;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--sidebar-border);
            background: var(--sidebar-footer-bg);
            transition: background-color 0.25s ease;
        }

        .sidebar-user-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }
        
        /* Main Content Layout */
        #main-content {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #main-content.expand-content {
            width: 100%;
            margin-left: 0;
        }

        /* Topbar Fixed */
        .topbar {
            background-color: var(--topbar-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
            padding: 12px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--topbar-border);
            position: fixed;
            top: 0;
            right: 0;
            width: calc(100% - var(--sidebar-width));
            z-index: 1030; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content-wrapper {
            margin-top: 76px;
            flex: 1;
        }

        .btn-toggle-sidebar {
            background: var(--surface-bg);
            border: 1px solid var(--border-color);
            color: var(--primary);
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-toggle-sidebar:hover {
            background: var(--primary);
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }

        /* Modern Components */
        .card-modern, .card { 
            border: 1px solid var(--border-color); 
            border-radius: 18px; 
            box-shadow: var(--card-shadow); 
            background: var(--card-bg); 
            transition: all 0.25s ease;
        }

        .card-modern.card-hover:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-hover-shadow);
            border-color: rgba(13, 148, 136, 0.3);
        }

        .stat-card-gradient {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .stat-card-gradient:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-hover-shadow);
        }

        .stat-icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: transform 0.3s;
        }

        .stat-card-gradient:hover .stat-icon-wrapper {
            transform: scale(1.1);
        }

        .form-control-modern, .form-select-modern, .form-control, .form-select { 
            border-radius: 12px; 
            border: 1px solid var(--input-border); 
            background-color: var(--input-bg); 
            color: var(--heading-color);
            padding: 10px 16px; 
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control-modern:focus, .form-select-modern:focus, .form-control:focus, .form-select:focus { 
            border-color: var(--primary); 
            box-shadow: 0 0 0 0.25rem rgba(13, 148, 136, 0.15); 
            background-color: var(--input-focus-bg); 
            color: var(--heading-color);
        }

        .btn-modern { 
            border-radius: 12px; 
            font-weight: 600; 
            padding: 8px 18px;
            transition: all 0.25s ease; 
        }

        .btn-modern:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1); 
        }

        .btn-primary-gradient {
            background: var(--primary-gradient);
            border: none;
            color: #ffffff;
        }

        .btn-primary-gradient:hover {
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.3);
        }

        /* Table Styling */
        .table {
            color: var(--text-color);
            --bs-table-color: var(--text-color);
            --bs-table-bg: transparent;
            border-color: var(--border-color);
        }

        .table > :not(caption) > * > * {
            padding: 14px 16px;
            border-bottom-color: var(--border-color);
        }

        .table thead, .table-light, thead.table-light {
            background-color: var(--surface-bg) !important;
            --bs-table-bg: var(--surface-bg) !important;
            color: var(--heading-color) !important;
            --bs-table-color: var(--heading-color) !important;
            border-color: var(--border-color);
        }

        .table-hover tbody tr {
            transition: background-color 0.15s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 148, 136, 0.04);
            --bs-table-hover-bg: rgba(13, 148, 136, 0.04);
        }

        /* Pulse Status Animation */
        .pulse-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            display: inline-block;
            position: relative;
        }
        .pulse-indicator::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #10b981;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            70% { transform: scale(2.4); opacity: 0; }
            100% { transform: scale(2.4); opacity: 0; }
        }

        /* Theme Toggle Button Styling */
        .btn-theme-toggle {
            background: var(--surface-bg);
            border: 1px solid var(--border-color);
            color: var(--heading-color);
            padding: 8px 14px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-theme-toggle:hover {
            background: var(--card-bg);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.15);
        }

        .theme-icon-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            transition: transform 0.3s ease;
        }

        .btn-theme-toggle:hover .theme-icon-container {
            transform: rotate(20deg) scale(1.12);
        }

        .theme-icon-moon {
            color: #38bdf8;
        }

        .theme-icon-sun {
            color: #f59e0b;
        }

        /* Mobile Backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1035;
            transition: opacity 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.show-mobile {
                margin-left: 0;
            }
            #main-content {
                width: 100% !important;
                margin-left: 0 !important;
            }
            .topbar {
                width: 100% !important;
                padding: 10px 16px;
            }
            .sidebar-backdrop.show-backdrop {
                display: block;
            }
        }

        /* ========================================================
           GLOBAL DARK MODE DIRECTIVE OVERRIDES
           ======================================================== */
        [data-theme="dark"], [data-bs-theme="dark"] {
            /* Text & Headings */
            h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .text-dark, strong, b {
                color: var(--heading-color) !important;
            }
            .text-muted {
                color: var(--text-muted) !important;
            }

            /* Card and Backgrounds */
            .bg-white {
                background-color: var(--card-bg) !important;
            }
            .bg-light {
                background-color: var(--surface-bg) !important;
                color: var(--text-color) !important;
            }
            .card-header, .card-footer {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
            }

            /* Universal Inline Style Overrides for Dark Mode */
            [style*="background: #ffffff"],
            [style*="background:#ffffff"],
            [style*="background: white"],
            [style*="background-color: #ffffff"],
            [style*="background-color:#ffffff"],
            [style*="background-color: white"] {
                background-color: var(--card-bg) !important;
            }

            [style*="background: #f8fafc"],
            [style*="background:#f8fafc"],
            [style*="background-color: #f8fafc"],
            [style*="background-color:#f8fafc"],
            [style*="background: #f1f5f9"],
            [style*="background:#f1f5f9"] {
                background-color: var(--surface-bg) !important;
            }

            [style*="color: #1e293b"],
            [style*="color:#1e293b"],
            [style*="color: #0f172a"],
            [style*="color:#0f172a"],
            [style*="color: #334155"] {
                color: var(--heading-color) !important;
            }

            [style*="border: 1px dashed #cbd5e1"],
            [style*="border: 1.5px dashed #cbd5e1"] {
                border-color: #334155 !important;
                background-color: var(--surface-bg) !important;
            }

            /* Borders */
            .border, .border-bottom, .border-top, .border-start, .border-end {
                border-color: var(--border-color) !important;
            }

            /* Tables */
            .table-hover tbody tr:hover {
                background-color: rgba(13, 148, 136, 0.12) !important;
                --bs-table-hover-bg: rgba(13, 148, 136, 0.12) !important;
            }

            /* Inputs, Selects, and Group Addons */
            .input-group-text {
                background-color: var(--surface-bg) !important;
                border-color: var(--input-border) !important;
                color: var(--text-muted) !important;
            }

            /* Badges */
            .badge.bg-light.text-dark {
                background-color: var(--surface-bg) !important;
                color: var(--heading-color) !important;
                border-color: var(--border-color) !important;
            }

            /* Buttons */
            .btn-light {
                background-color: var(--surface-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--heading-color) !important;
            }
            .btn-light:hover {
                background-color: var(--card-bg) !important;
                color: var(--primary-light) !important;
                border-color: var(--primary) !important;
            }

            /* Modals */
            .modal-content {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--text-color) !important;
            }
            .modal-header, .modal-footer {
                border-color: var(--border-color) !important;
            }
            .btn-close {
                filter: invert(1) grayscale(100%) brightness(200%);
            }

            /* Dropdowns */
            .dropdown-menu {
                background-color: var(--dropdown-bg) !important;
                border-color: var(--dropdown-border) !important;
                color: var(--text-color) !important;
            }
            .dropdown-item {
                color: var(--text-color) !important;
            }
            .dropdown-item:hover, .dropdown-item:focus {
                background-color: rgba(13, 148, 136, 0.18) !important;
                color: var(--primary-light) !important;
            }
            .dropdown-divider {
                border-color: var(--border-color) !important;
            }

            /* List Groups */
            .list-group-item {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--text-color) !important;
            }
            .list-group-item-action:hover {
                background-color: var(--surface-bg) !important;
                color: var(--heading-color) !important;
            }

            /* Pagination */
            .pagination .page-link {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--text-color) !important;
            }
            .pagination .page-link:hover {
                background-color: var(--surface-bg) !important;
                color: var(--primary-light) !important;
                border-color: var(--primary) !important;
            }
            .pagination .page-item.active .page-link {
                background: var(--primary-gradient) !important;
                border-color: var(--primary) !important;
                color: #ffffff !important;
            }
            .pagination .page-item.disabled .page-link {
                background-color: var(--surface-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--text-muted) !important;
                opacity: 0.6;
            }

            /* Nav Tabs & Pills */
            .nav-tabs {
                border-bottom-color: var(--border-color) !important;
            }
            .nav-tabs .nav-link {
                color: var(--text-muted) !important;
                border-color: transparent !important;
            }
            .nav-tabs .nav-link:hover {
                color: var(--heading-color) !important;
                border-color: var(--border-color) var(--border-color) transparent !important;
            }
            .nav-tabs .nav-link.active {
                color: var(--primary-light) !important;
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) var(--border-color) var(--card-bg) !important;
                font-weight: 600;
            }

            /* Subtle Badges in Dark Mode */
            .badge.bg-light {
                background-color: var(--surface-bg) !important;
                color: var(--heading-color) !important;
                border-color: var(--border-color) !important;
            }
            .badge.bg-secondary-subtle {
                background-color: rgba(148, 163, 184, 0.15) !important;
                color: #cbd5e1 !important;
            }

            /* Alerts in Dark Mode */
            .alert {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--heading-color) !important;
            }

            /* Footer */
            footer, .footer {
                border-top-color: var(--border-color) !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>  

    <!-- Mobile Backdrop Overlay -->
    <div id="sidebarBackdrop" class="sidebar-backdrop"></div>

    @include('layouts.sidebar')   

    <div id="main-content">
        @include('layouts.topbar')  

        <div class="content-wrapper container-fluid px-3 px-md-4 pb-5">
            @if(session('pesan_sukses'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center" role="alert" style="border-left: 5px solid #10b981 !important;">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                    <div class="flex-grow-1">
                        <strong class="text-success">Berhasil!</strong> {!! session('pesan_sukses') !!}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('pesan_error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center" role="alert" style="border-left: 5px solid #ef4444 !important;">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                    <div class="flex-grow-1">
                        <strong class="text-danger">Peringatan:</strong> {!! session('pesan_error') !!}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('pesan_global'))
                {!! session('pesan_global') !!}
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4" role="alert" style="border-left: 5px solid #ef4444 !important;">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-exclamation-octagon-fill text-danger fs-4 me-2"></i>
                        <strong class="text-danger">Terdapat beberapa kesalahan input:</strong>
                    </div>
                    <ul class="mb-0 mt-1 ps-4 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="page-animate">
                @yield('content')
            </div>
        </div>

        @include('layouts.footer')
    </div>

@include('layouts.modals.profil')
@include('layouts.modals.ganti_password')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // --- Global SweetAlert2 & Toast Utilities ---
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    window.showToast = function(icon, title) {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        Toast.fire({
            icon: icon,
            title: title,
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#0f172a'
        });
    };

    @if(session('success'))
        showToast('success', "{{ session('success') }}");
    @endif
    @if(session('error'))
        showToast('error', "{{ session('error') }}");
    @endif
    @if(session('warning'))
        showToast('warning', "{{ session('warning') }}");
    @endif
    @if(session('info'))
        showToast('info', "{{ session('info') }}");
    @endif

    window.confirmAction = function(options) {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        return Swal.fire({
            title: options.title || 'Apakah Anda yakin?',
            text: options.text || 'Tindakan ini tidak dapat dibatalkan.',
            icon: options.icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: options.confirmBtnColor || '#0d9488',
            cancelButtonColor: '#64748b',
            confirmButtonText: options.confirmBtnText || 'Ya, Lanjutkan',
            cancelButtonText: options.cancelBtnText || 'Batal',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'rounded-4 border shadow-lg'
            }
        });
    };

    document.addEventListener("DOMContentLoaded", function() {
        // --- 0. Bootstrap Modal Global Auto-Escape Handler ---
        // Ensures any modal, even if rendered inside nested cards, tables, or animation containers,
        // is moved directly to document.body when opened so it is never trapped under backdrops.
        document.addEventListener('show.bs.modal', function(event) {
            const modal = event.target;
            if (modal && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
        });

        // Ensure backdrops and body locks are completely cleaned up when modals close
        document.addEventListener('hidden.bs.modal', function() {
            if (!document.querySelector('.modal.show')) {
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            }
        });
        // --- 1. Responsive Sidebar Logic ---
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const topbar = document.querySelector('.topbar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function isMobile() {
            return window.innerWidth < 992;
        }

        if (sidebarToggle && sidebar && mainContent) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                if (isMobile()) {
                    sidebar.classList.toggle('show-mobile');
                    if (backdrop) {
                        backdrop.classList.toggle('show-backdrop');
                    }
                } else {
                    sidebar.classList.toggle('hide-sidebar');
                    mainContent.classList.toggle('expand-content');
                    if (topbar) {
                        topbar.style.width = sidebar.classList.contains('hide-sidebar') ? '100%' : 'calc(100% - var(--sidebar-width))';
                    }
                }
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', function() {
                sidebar.classList.remove('show-mobile');
                backdrop.classList.remove('show-backdrop');
            });
        }

        window.addEventListener('resize', function() {
            if (!isMobile() && backdrop) {
                sidebar.classList.remove('show-mobile');
                backdrop.classList.remove('show-backdrop');
            }
        });

        // --- 2. Theme Mode Manager (Light & Dark Mode) ---
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const dropdownThemeToggle = document.getElementById('dropdownThemeToggle');

        function getStoredTheme() {
            return localStorage.getItem('kepk_theme') || 
                   (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        }

        function syncThemeUI(theme) {
            const isDark = (theme === 'dark');

            // Update topbar button
            if (themeToggleBtn) {
                const moonIcon = themeToggleBtn.querySelector('.theme-icon-moon');
                const sunIcon = themeToggleBtn.querySelector('.theme-icon-sun');
                const textLabel = themeToggleBtn.querySelector('.theme-text');

                if (isDark) {
                    if (moonIcon) moonIcon.classList.add('d-none');
                    if (sunIcon) sunIcon.classList.remove('d-none');
                    if (textLabel) textLabel.textContent = 'Terang';
                    themeToggleBtn.setAttribute('title', 'Beralih ke Mode Terang (Light Mode)');
                } else {
                    if (moonIcon) moonIcon.classList.remove('d-none');
                    if (sunIcon) sunIcon.classList.add('d-none');
                    if (textLabel) textLabel.textContent = 'Gelap';
                    themeToggleBtn.setAttribute('title', 'Beralih ke Mode Gelap (Dark Mode)');
                }
            }

            // Update dropdown item badge
            if (dropdownThemeToggle) {
                const statusBadge = dropdownThemeToggle.querySelector('.theme-badge-status');
                if (statusBadge) {
                    statusBadge.textContent = isDark ? 'Mode Gelap' : 'Mode Terang';
                    if (isDark) {
                        statusBadge.className = 'badge bg-teal text-white border-0 theme-badge-status';
                        statusBadge.style.background = '#0d9488';
                    } else {
                        statusBadge.className = 'badge bg-light text-dark border theme-badge-status';
                        statusBadge.style.background = '';
                    }
                }
            }
        }

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('kepk_theme', theme);
            syncThemeUI(theme);

            // Dispatch global event for any chart/widget reactivity
            window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: theme } }));
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = (currentTheme === 'dark') ? 'light' : 'dark';
            applyTheme(newTheme);
        }

        // Initialize UI with active theme
        syncThemeUI(getStoredTheme());

        // Event Listeners for Theme Toggles
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleTheme();
            });
        }

        if (dropdownThemeToggle) {
            dropdownThemeToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleTheme();
            });
        }

        // Listen for OS Theme Changes if user hasn't set custom preference
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem('kepk_theme')) {
                    applyTheme(e.matches ? 'dark' : 'light');
                }
            });
        }

        // --- 3. Logout Confirmation Hook ---
        const btnLogout = document.getElementById('btnLogoutTrigger');
        if (btnLogout) {
            btnLogout.addEventListener('click', function(e) {
                e.preventDefault();
                confirmAction({
                    title: 'Konfirmasi Keluar',
                    text: 'Apakah Anda yakin ingin keluar dari sistem e-KEPK ETHOS?',
                    icon: 'question',
                    confirmBtnText: 'Ya, Keluar',
                    confirmBtnColor: '#ef4444'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logoutForm').submit();
                    }
                });
            });
        }

        // --- 4. NProgress Navigation Progress Bar ---
        if (typeof NProgress !== 'undefined') {
            NProgress.configure({ showSpinner: false, speed: 400 });
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('javascript') && !this.hasAttribute('data-bs-toggle') && !this.hasAttribute('download')) {
                        NProgress.start();
                    }
                });
            });
            window.addEventListener('beforeunload', function() {
                NProgress.start();
            });
        }

        // --- 5. Dynamic Live Greeting & Clock in Topbar ---
        function updateLiveGreetingClock() {
            const greetingEl = document.getElementById('dynamicGreeting');
            const clockEl = document.getElementById('liveTimeClock');
            const now = new Date();
            
            if (greetingEl) {
                const hour = now.getHours();
                let greeting = 'Selamat Malam 🌙';
                if (hour >= 4 && hour < 11) greeting = 'Selamat Pagi 🌅';
                else if (hour >= 11 && hour < 15) greeting = 'Selamat Siang ☀️';
                else if (hour >= 15 && hour < 18) greeting = 'Selamat Sore 🌇';
                greetingEl.textContent = greeting;
            }
            if (clockEl) {
                clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            }
        }
        setInterval(updateLiveGreetingClock, 1000);
        updateLiveGreetingClock();

        // --- 6. Number Counter-Up Animation for Stat Cards ---
        window.animateStatCounters = function() {
            document.querySelectorAll('.stat-counter').forEach(el => {
                const target = parseInt(el.getAttribute('data-target') || el.innerText.trim(), 10);
                if (isNaN(target)) return;
                let start = 0;
                const duration = 1200;
                const startTime = performance.now();
                function step(curr) {
                    const elapsed = curr - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const ease = 1 - Math.pow(1 - progress, 3);
                    el.innerText = Math.floor(ease * target);
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.innerText = target;
                    }
                }
                requestAnimationFrame(step);
            });
        };
        animateStatCounters();

        // --- 8. Client-side Live Table Search Helper ---
        window.initLiveTableSearch = function(inputId, tableSelector, infoCountId = null) {
            const input = document.getElementById(inputId);
            const table = document.querySelector(tableSelector);
            if (!input || !table) return;

            input.addEventListener('input', function() {
                const filter = this.value.toLowerCase().trim();
                const rows = table.querySelectorAll('tbody tr');
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.classList.contains('no-records-row')) return;
                    const text = row.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        row.classList.remove('table-row-hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('table-row-hidden');
                    }
                });

                if (infoCountId) {
                    const infoEl = document.getElementById(infoCountId);
                    if (infoEl) infoEl.textContent = visibleCount;
                }
            });
        };
    });
</script>
@stack('scripts')
</body>
</html>