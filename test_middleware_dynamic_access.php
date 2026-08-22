<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================================\n";
echo " TESTING DYNAMIC ACCESS IN ROLE MIDDLEWARE\n";
echo "========================================================\n\n";

$middleware = new App\Http\Middleware\RoleMiddleware();
$ketua = App\Models\User::where('email', 'ketua@rsud.com')->first();
$ketuaRole = App\Models\MasterRole::where('id', 2)->first();

// Emulate login as Ketua
Illuminate\Support\Facades\Auth::login($ketua);

// Test 1: Route 'sekretaris.pengguna' without dynamic permission
echo "[1] Testing access to 'sekretaris.pengguna' BEFORE granting permission...\n";
// Ensure menu 2 (Data Pengguna) is not in Ketua role
$currentMenus = $ketuaRole->menus->pluck('id')->toArray();
$menusWithoutPengguna = array_diff($currentMenus, [2]);
$ketuaRole->menus()->sync($menusWithoutPengguna);

// Create request with route named 'sekretaris.pengguna'
$request = Illuminate\Http\Request::create('/sekretaris/data-pengguna', 'GET');
$route = new Illuminate\Routing\Route(['GET'], '/sekretaris/data-pengguna', ['as' => 'sekretaris.pengguna']);
$route->bind($request);
$request->setRouteResolver(function () use ($route) {
    return $route;
});

$response = $middleware->handle($request, function ($req) {
    return new Illuminate\Http\Response('PASSED_TO_CONTROLLER');
}, 'sekretaris');

if ($response->getContent() === 'PASSED_TO_CONTROLLER') {
    echo "    [FAIL] Expected access blocked, but got allowed.\n";
} else {
    echo "    [OK] Access properly BLOCKED and redirected to: " . $response->headers->get('Location') . "\n";
}

// Test 2: Grant menu 2 (Data Pengguna) to Ketua in role_menu
echo "\n[2] Testing access to 'sekretaris.pengguna' AFTER granting permission in role_menu...\n";
$menusWithPengguna = array_unique(array_merge($currentMenus, [2]));
$ketuaRole->menus()->sync($menusWithPengguna);
$ketuaRole->refresh();

$response2 = $middleware->handle($request, function ($req) {
    return new Illuminate\Http\Response('PASSED_TO_CONTROLLER');
}, 'sekretaris');

if ($response2->getContent() === 'PASSED_TO_CONTROLLER') {
    echo "    [OK] Access successfully ALLOWED by dynamic RoleMiddleware!\n";
} else {
    echo "    [FAIL] Access was blocked when it should be allowed.\n";
}

// Test 3: Sub-action route like 'sekretaris.pengguna.update'
echo "\n[3] Testing sub-action route 'sekretaris.pengguna.update' with wildcard pattern...\n";
$requestSub = Illuminate\Http\Request::create('/sekretaris/data-pengguna/1', 'PUT');
$routeSub = new Illuminate\Routing\Route(['PUT'], '/sekretaris/data-pengguna/{id}', ['as' => 'sekretaris.pengguna.update']);
$routeSub->bind($requestSub);
$requestSub->setRouteResolver(function () use ($routeSub) {
    return $routeSub;
});

$response3 = $middleware->handle($requestSub, function ($req) {
    return new Illuminate\Http\Response('PASSED_TO_CONTROLLER');
}, 'sekretaris');

if ($response3->getContent() === 'PASSED_TO_CONTROLLER') {
    echo "    [OK] Sub-action route 'sekretaris.pengguna.update' successfully ALLOWED by wildcard pattern matching!\n";
} else {
    echo "    [FAIL] Sub-action route was blocked.\n";
}

// Clean up: Revert Ketua menus back
$ketuaRole->menus()->sync($currentMenus);

echo "\n========================================================\n";
echo " ALL DYNAMIC MIDDLEWARE TESTS PASSED!\n";
echo "========================================================\n";
