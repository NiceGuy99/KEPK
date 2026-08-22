<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ETHOS RSUD Sidoarjo Barat</title>
    <link rel="icon" href="{{ asset('image/logo1.jpg') }}" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px 0; }
        .login-card { border: none; border-radius: 20px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08); overflow: hidden; width: 100%; max-width: 420px; background: #ffffff; }
        .login-header { background: #ffffff; padding: 25px 20px 10px; text-align: center; color: #1e293b; border-bottom: 1px solid #f1f5f9; }
        .login-logo { max-width: 260px; height: auto; object-fit: contain; background-color: transparent; border-radius: 12px; margin-bottom: 10px; }
        .login-header h4 { font-weight: 700; letter-spacing: 0.5px; margin-bottom: 5px; font-size: 1.25rem; }
        .login-body { padding: 35px 30px; background-color: #ffffff; }
        .input-group-text { background-color: #f8fafc; border: 1px solid #e2e8f0; border-right: none; border-radius: 12px 0 0 12px; color: #64748b; }
        .form-control-modern { border-radius: 0 12px 12px 0; padding: 12px 15px; border: 1px solid #e2e8f0; border-left: none; background-color: #f8fafc; font-size: 0.95rem; }
        .form-control-modern:focus { box-shadow: none; background-color: #ffffff; border-color: #0d9488; }
        .input-group:focus-within .input-group-text, .input-group:focus-within .form-control-modern { border-color: #0d9488; background-color: #ffffff; color: #0d9488; }
        .btn-modern { background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none; border-radius: 50px; font-weight: 600; padding: 12px; letter-spacing: 0.5px; transition: all 0.3s; color: white; width: 100%; margin-top: 15px; }
        .btn-modern:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(13, 148, 136, 0.3); color: white; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('image/logo.png') }}" alt="Logo e-KEPK" class="login-logo">
            <h4>RSUD Sidoarjo Barat</h4>
        </div>

        <div class="login-body">
            <h5 class="fw-bold mb-4 text-center" style="color: #1e293b;">Masuk ke Akun Anda</h5>
            
            @if(session('pesan_error'))
                <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4" style="font-size: 0.85rem;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('pesan_error') }}
                </div>
            @endif

            @if(session('pesan_sukses_logout'))
                <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4" style="font-size: 0.85rem;">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('pesan_sukses_logout') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted" style="font-size: 0.85rem; font-weight: 500;">Alamat Email</label>
                    <div class="input-group shadow-sm" style="border-radius: 12px;">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control form-control-modern" required placeholder="Masukkan email..." value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted" style="font-size: 0.85rem; font-weight: 500;">Kata Sandi</label>
                    <div class="input-group shadow-sm" style="border-radius: 12px;">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control form-control-modern" required placeholder="Masukkan sandi...">
                    </div>
                </div>

                <button type="submit" class="btn btn-modern">Masuk <i class="bi bi-arrow-right-short ms-1 fs-5 align-middle"></i></button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
