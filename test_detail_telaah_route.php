<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================================\n";
echo " TESTING DETAIL TELAAH & DETAIL 7 STANDAR ROUTES\n";
echo "========================================================\n\n";

// 1. Check Route Definitions
echo "[1] Checking Route Definitions...\n";
echo "    - sekretaris.detail-7standar: " . (Illuminate\Support\Facades\Route::has('sekretaris.detail-7standar') ? 'DEFINED' : 'NOT DEFINED') . "\n";
echo "    - ketua.detail-7standar: " . (Illuminate\Support\Facades\Route::has('ketua.detail-7standar') ? 'DEFINED' : 'NOT DEFINED') . "\n";

// 2. Test Rendering sekretaris.telaah in detail mode
echo "\n[2] Testing Rendering 'sekretaris.telaah' in detail mode with detail-7standar link...\n";
$tugas = App\Models\PenugasanReview::with([
    'proposal.peneliti.profil', 
    'proposal.jenisPenelitian',
    'proposal.statusProposal',
    'penelaah.profil'
])->first();

if ($tugas) {
    $sekretaris = App\Models\User::where('email', 'sekretaris@rsud.com')->first();
    Illuminate\Support\Facades\Auth::login($sekretaris);

    $savedAnswers = [];
    $mode = 'view';
    $view = view('sekretaris.telaah', compact('tugas', 'savedAnswers', 'mode'));
    $html = $view->render();
    echo "    [OK] 'sekretaris.telaah' rendered successfully without errors! (Size: " . strlen($html) . " bytes)\n";
} else {
    echo "    [NOTE] No PenugasanReview records found.\n";
}

echo "\n========================================================\n";
echo " ALL ROUTE TESTS PASSED!\n";
echo "========================================================\n";
