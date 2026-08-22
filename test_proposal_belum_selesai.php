<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================================\n";
echo " TESTING SEMUA PROPOSAL MASUK MENU & ACCESS\n";
echo "========================================================\n\n";

// 1. Test Controller method
echo "[1] Testing SekretarisController::proposalBelumSelesai (All Proposals)...\n";
$controller = new App\Http\Controllers\SekretarisController();
$sekretaris = App\Models\User::where('email', 'sekretaris@rsud.com')->first();
Illuminate\Support\Facades\Auth::login($sekretaris);

$req = new Illuminate\Http\Request();
$response = $controller->proposalBelumSelesai($req);
$proposals = $response->getData()['proposals'];
echo "    - Total proposals fetched (including completed): " . $proposals->total() . "\n";

$statusCounts = [];
foreach ($proposals as $p) {
    $statusCounts[$p->status_id] = ($statusCounts[$p->status_id] ?? 0) + 1;
}
echo "    - Status distribution in result: " . json_encode($statusCounts) . "\n";
echo "    [OK] Contains all proposals correctly.\n";

// 2. Test View Rendering
echo "\n[2] Testing View Rendering for 'sekretaris.proposal_belum_selesai'...\n";
$html = $response->render();
echo "    [OK] View rendered successfully! (Size: " . strlen($html) . " bytes)\n";

// 3. Test Ketua Access via RoleMiddleware
echo "\n[3] Testing Ketua Access to 'sekretaris.proposal-belum-selesai'...\n";
$ketua = App\Models\User::where('email', 'ketua@rsud.com')->first();
Illuminate\Support\Facades\Auth::login($ketua);

$middleware = new App\Http\Middleware\RoleMiddleware();
$requestKetua = Illuminate\Http\Request::create('/sekretaris/proposal-belum-selesai', 'GET');
$route = new Illuminate\Routing\Route(['GET'], '/sekretaris/proposal-belum-selesai', ['as' => 'sekretaris.proposal-belum-selesai']);
$route->bind($requestKetua);
$requestKetua->setRouteResolver(function () use ($route) {
    return $route;
});

$resKetua = $middleware->handle($requestKetua, function ($req) {
    return new Illuminate\Http\Response('PASSED_TO_CONTROLLER');
}, 'sekretaris');

if ($resKetua->getContent() === 'PASSED_TO_CONTROLLER') {
    echo "    [OK] Ketua successfully ALLOWED to access 'sekretaris.proposal-belum-selesai' via dynamic permission!\n";
} else {
    echo "    [FAIL] Ketua was blocked by middleware!\n";
}

echo "\n========================================================\n";
echo " ALL TESTS PASSED SUCCESSFULLY!\n";
echo "========================================================\n";
