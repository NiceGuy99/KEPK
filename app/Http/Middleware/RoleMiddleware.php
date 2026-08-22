<?php

namespace App\Http\Middleware;

use App\Models\MasterMenu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('pesan_error', 'Silakan masuk terlebih dahulu.');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('pesan_error', 'Akun Anda belum diaktifkan atau sedang dinonaktifkan.');
        }

        $userRole = strtolower($user->role?->nama_role ?? '');
        $userRoleId = (string)$user->role_id;

        $allowedRoles = array_map('strtolower', $roles);

        // 1. Cek apakah role user secara eksplisit diizinkan di parameter route
        if (in_array($userRole, $allowedRoles) || in_array($userRoleId, $roles)) {
            return $next($request);
        }

        // 2. Cek apakah rute yang sedang diakses diizinkan secara dinamis melalui role_menu
        if ($user->role_id) {
            $currentRouteName = $request->route()?->getName();
            
            if ($currentRouteName) {
                $hasDynamicPermission = MasterMenu::where('is_active', true)
                    ->whereHas('roles', function ($q) use ($user) {
                        $q->where('master_role.id', $user->role_id);
                    })
                    ->get()
                    ->contains(function ($menu) use ($request, $currentRouteName) {
                        if ($menu->route_name === $currentRouteName) {
                            return true;
                        }
                        if ($menu->route_pattern) {
                            $patterns = explode('|', $menu->route_pattern);
                            foreach ($patterns as $p) {
                                $p = trim($p);
                                if (!empty($p) && $request->routeIs($p)) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    });

                if ($hasDynamicPermission) {
                    return $next($request);
                }
            }
        }

        return match ($userRole) {
            'sekretaris' => redirect()->route('sekretaris.dashboard')->with('pesan_error', 'Akses ditolak untuk halaman tersebut.'),
            'ketua' => redirect()->route('ketua.dashboard')->with('pesan_error', 'Akses ditolak untuk halaman tersebut.'),
            'penelaah' => redirect()->route('penelaah.dashboard')->with('pesan_error', 'Akses ditolak untuk halaman tersebut.'),
            'peneliti' => redirect()->route('peneliti.dashboard')->with('pesan_error', 'Akses ditolak untuk halaman tersebut.'),
            default => redirect()->route('login')->with('pesan_error', 'Peran pengguna tidak dikenali.'),
        };
    }
}
