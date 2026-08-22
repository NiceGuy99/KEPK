<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', trim($request->email))->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->is_active) {
                return back()->with('pesan_error', 'Akun Anda belum diaktifkan atau sedang dinonaktifkan.');
            }

            Auth::login($user);
            $request->session()->regenerate();

            $roleName = strtolower($user->role?->nama_role ?? '');
            session([
                'id_user' => $user->id,
                'nama' => $user->nama_lengkap,
                'role' => $roleName,
            ]);

            return $this->redirectBasedOnRole($user);
        }

        return back()->with('pesan_error', 'Email atau Kata Sandi yang Anda masukkan salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('pesan_sukses_logout', 'Anda telah berhasil keluar dari sistem.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:5',
            'konfirmasi_password' => 'required|same:password_baru',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('pesan_global', '<div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i> Kata sandi lama yang Anda masukkan salah!</div>');
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return back()->with('pesan_global', '<div class="alert alert-success shadow-sm border-0 rounded-3 mb-4"><i class="bi bi-check-circle-fill me-2"></i> Kata sandi berhasil diperbarui.</div>');
    }

    protected function redirectBasedOnRole(User $user)
    {
        return match (strtolower($user->role?->nama_role ?? '')) {
            'sekretaris' => redirect()->route('sekretaris.dashboard'),
            'ketua' => redirect()->route('ketua.dashboard'),
            'penelaah' => redirect()->route('penelaah.dashboard'),
            'peneliti' => redirect()->route('peneliti.dashboard'),
            default => redirect()->route('login')->with('pesan_error', 'Peran tidak dikenali.'),
        };
    }
}
