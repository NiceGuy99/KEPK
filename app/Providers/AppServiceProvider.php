<?php

namespace App\Providers;

use App\Models\MasterMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer untuk Sidebar Dinamis
        View::composer('layouts.sidebar', function ($view) {
            $sidebarMenus = collect();
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->role_id) {
                    $sidebarMenus = MasterMenu::where('is_active', true)
                        ->whereHas('roles', function ($q) use ($user) {
                            $q->where('master_role.id', $user->role_id);
                        })
                        ->orderBy('urutan', 'asc')
                        ->get();
                }
            }
            $view->with('sidebarMenus', $sidebarMenus);
        });
    }
}
