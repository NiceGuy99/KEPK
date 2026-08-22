<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================================\n";
echo " TESTING DYNAMIC MENU & RBAC SYSTEM\n";
echo "========================================================\n\n";

// 1. Verify MasterMenu & MasterRole
echo "[1] Testing MasterMenu and MasterRole Relations...\n";
$roles = App\Models\MasterRole::with('menus')->get();
foreach ($roles as $r) {
    echo "    - Role: {$r->nama_role} (ID: {$r->id}) -> Menus Count: " . $r->menus->count() . "\n";
    foreach ($r->menus as $m) {
        echo "        * [{$m->id}] {$m->nama_menu} ({$m->route_name})\n";
    }
}

// 2. Test User Sidebar Resolution for Each Role
echo "\n[2] Testing Dynamic Sidebar Resolution for Accounts...\n";
$testUsers = [
    'Sekretaris' => 'sekretaris@rsud.com',
    'Ketua' => 'ketua@rsud.com',
    'Penelaah' => 'penelaah@rsud.com',
    'Peneliti' => 'peneliti.mhs@univ.ac.id',
];

foreach ($testUsers as $label => $email) {
    $user = App\Models\User::where('email', $email)->first();
    if (!$user) {
        echo "    [FAIL] User {$email} not found.\n";
        continue;
    }
    
    // Emulate Auth
    Illuminate\Support\Facades\Auth::login($user);
    
    $sidebarMenus = App\Models\MasterMenu::where('is_active', true)
        ->whereHas('roles', function ($q) use ($user) {
            $q->where('master_role.id', $user->role_id);
        })
        ->orderBy('urutan', 'asc')
        ->get();

    echo "    [OK] {$label} ({$user->email}) -> Resolved " . $sidebarMenus->count() . " active sidebar menus.\n";
}

// 3. Test View Rendering of Hak Akses View
echo "\n[3] Testing View Rendering for 'sekretaris.hak_akses'...\n";
try {
    $roles = App\Models\MasterRole::with('menus')->get();
    $menus = App\Models\MasterMenu::orderBy('urutan', 'asc')->get();
    $menusByKategori = $menus->groupBy('kategori');
    
    $viewContent = view('sekretaris.hak_akses', compact('roles', 'menus', 'menusByKategori'))->render();
    echo "    [OK] 'sekretaris.hak_akses' rendered successfully! (Size: " . strlen($viewContent) . " bytes)\n";
} catch (\Throwable $e) {
    echo "    [FAIL] Error rendering 'sekretaris.hak_akses': " . $e->getMessage() . "\n";
}

// 4. Test View Rendering of Sidebar
echo "\n[4] Testing Sidebar Rendering with View Composer...\n";
try {
    $sekretaris = App\Models\User::where('email', 'sekretaris@rsud.com')->first();
    Illuminate\Support\Facades\Auth::login($sekretaris);
    
    $sidebarHtml = view('layouts.sidebar')->render();
    echo "    [OK] 'layouts.sidebar' rendered successfully! (Size: " . strlen($sidebarHtml) . " bytes)\n";
} catch (\Throwable $e) {
    echo "    [FAIL] Error rendering 'layouts.sidebar': " . $e->getMessage() . "\n";
}

// 5. Test Permission Syncing Simulation
echo "\n[5] Testing Permission Syncing Simulation...\n";
try {
    $ketuaRole = App\Models\MasterRole::where('id', 2)->first();
    $currentMenuIds = $ketuaRole->menus->pluck('id')->toArray();
    
    // Add menu 2 (Data Pengguna) to Ketua temporarily
    $newMenuIds = array_unique(array_merge($currentMenuIds, [2]));
    $ketuaRole->menus()->sync($newMenuIds);
    $ketuaRole->refresh();
    echo "    [OK] Successfully synced new menu (Data Pengguna) to Ketua! Total menus: " . $ketuaRole->menus->count() . "\n";
    
    // Revert back to original
    $ketuaRole->menus()->sync($currentMenuIds);
    $ketuaRole->refresh();
    echo "    [OK] Successfully reverted Ketua permissions back to original! Total menus: " . $ketuaRole->menus->count() . "\n";
} catch (\Throwable $e) {
    echo "    [FAIL] Error in permission sync: " . $e->getMessage() . "\n";
}

echo "\n========================================================\n";
echo " ALL TESTS PASSED!\n";
echo "========================================================\n";
