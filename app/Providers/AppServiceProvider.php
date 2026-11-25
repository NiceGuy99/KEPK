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

        // View Composer untuk Topbar Notifikasi Dinamis
        View::composer('layouts.topbar', function ($view) {
            $notifications = collect();
            $unreadCount = 0;
            if (Auth::check()) {
                $user = Auth::user();
                try {
                    if ($user->role_id == 1) { // Sekretaris
                        $proposals = \App\Models\PengajuanProposal::with('peneliti')
                            ->whereIn('status_id', [1, 4])
                            ->orderBy('updated_at', 'desc')
                            ->limit(6)
                            ->get();
                        foreach ($proposals as $p) {
                            $isNew = ($p->status_id == 1);
                            $notifications->push([
                                'title' => $isNew ? 'Usulan Baru Masuk' : 'Usulan Telah Direvisi',
                                'text' => ($p->peneliti?->nama_lengkap ?? 'Peneliti') . ' - ' . \Illuminate\Support\Str::limit($p->judul_penelitian, 36),
                                'time' => $p->updated_at ? $p->updated_at->diffForHumans() : 'Baru saja',
                                'icon' => $isNew ? 'bi-file-earmark-plus text-primary' : 'bi-arrow-repeat text-warning',
                                'bg' => $isNew ? 'rgba(13, 148, 136, 0.12)' : 'rgba(245, 158, 11, 0.12)',
                                'url' => route('sekretaris.verifikasi', $p->id),
                            ]);
                        }
                        $unreadCount = \App\Models\PengajuanProposal::where('status_id', 1)->count();
                    } elseif ($user->role_id == 2) { // Ketua
                        $proposals = \App\Models\PengajuanProposal::with('peneliti')
                            ->whereIn('status_id', [1, 5])
                            ->orderBy('updated_at', 'desc')
                            ->limit(6)
                            ->get();
                        foreach ($proposals as $p) {
                            $isCert = ($p->status_id == 5);
                            $notifications->push([
                                'title' => $isCert ? 'Sertifikat Terbit' : 'Perlu Verifikasi',
                                'text' => ($p->peneliti?->nama_lengkap ?? 'Peneliti') . ' - ' . \Illuminate\Support\Str::limit($p->judul_penelitian, 36),
                                'time' => $p->updated_at ? $p->updated_at->diffForHumans() : 'Baru saja',
                                'icon' => $isCert ? 'bi-award text-success' : 'bi-clock-history text-warning',
                                'bg' => $isCert ? 'rgba(16, 185, 129, 0.12)' : 'rgba(245, 158, 11, 0.12)',
                                'url' => $isCert ? route('ketua.sertifikat') : route('ketua.verifikasi', $p->id),
                            ]);
                        }
                        $unreadCount = \App\Models\PengajuanProposal::where('status_id', 1)->count();
                    } elseif ($user->role_id == 3) { // Penelaah
                        $assignments = \App\Models\PenugasanReview::with(['proposal.peneliti'])
                            ->where('penelaah_id', $user->id)
                            ->where('status', 'menunggu')
                            ->orderBy('id', 'desc')
                            ->limit(6)
                            ->get();
                        foreach ($assignments as $a) {
                            $notifications->push([
                                'title' => 'Tugas Telaah Baru',
                                'text' => \Illuminate\Support\Str::limit($a->proposal?->judul_penelitian ?? 'Proposal Riset', 36),
                                'time' => $a->created_at ? $a->created_at->diffForHumans() : 'Baru saja',
                                'icon' => 'bi-journal-check text-info',
                                'bg' => 'rgba(2, 132, 199, 0.12)',
                                'url' => route('penelaah.tugas'),
                            ]);
                        }
                        $unreadCount = $assignments->count();
                    } elseif ($user->role_id == 4) { // Peneliti
                        $myProposals = \App\Models\PengajuanProposal::where('peneliti_id', $user->id)
                            ->orderBy('updated_at', 'desc')
                            ->limit(6)
                            ->get();
                        foreach ($myProposals as $p) {
                            $statusName = $p->statusProposal?->nama_status ?? 'Status Diperbarui';
                            $isRevisi = ($p->status_id == 3);
                            $isSelesai = ($p->status_id == 5);
                            $notifications->push([
                                'title' => 'Protokol: ' . $statusName,
                                'text' => \Illuminate\Support\Str::limit($p->judul_penelitian, 36),
                                'time' => $p->updated_at ? $p->updated_at->diffForHumans() : 'Baru saja',
                                'icon' => $isSelesai ? 'bi-patch-check-fill text-success' : ($isRevisi ? 'bi-exclamation-octagon-fill text-danger' : 'bi-arrow-repeat text-primary'),
                                'bg' => $isSelesai ? 'rgba(16, 185, 129, 0.12)' : ($isRevisi ? 'rgba(239, 68, 68, 0.12)' : 'rgba(13, 148, 136, 0.12)'),
                                'url' => route('peneliti.status'),
                            ]);
                        }
                        $unreadCount = $myProposals->whereIn('status_id', [3, 5])->count();
                    }
                } catch (\Throwable $e) {
                    // Fallback graceful jika query gagal
                }
            }
            $view->with('topbarNotifications', $notifications)->with('topbarUnreadCount', $unreadCount);
        });
    }
}
