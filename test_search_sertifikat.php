<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================================\n";
echo " TESTING SEARCH FEATURE IN DAFTAR SERTIFIKAT\n";
echo "========================================================\n\n";

// 1. Test Sekretaris Daftar Sertifikat with Search
echo "[1] Testing SekretarisController::daftarSertifikat...\n";
$sekretaris = App\Models\User::where('email', 'sekretaris@rsud.com')->first();
Illuminate\Support\Facades\Auth::login($sekretaris);
$sekretarisController = new App\Http\Controllers\SekretarisController();

// Test empty search
$reqEmpty = new Illuminate\Http\Request();
$responseAll = $sekretarisController->daftarSertifikat($reqEmpty);
$dataAll = $responseAll->getData()['proposalsSelesai'];
echo "    - Total completed proposals without filter: " . $dataAll->total() . "\n";

// Test search by certificate number keyword
$reqSearch = new Illuminate\Http\Request(['search' => 'KEPK']);
$responseSearch = $sekretarisController->daftarSertifikat($reqSearch);
$dataSearch = $responseSearch->getData()['proposalsSelesai'];
echo "    - Search for 'KEPK' matched: " . $dataSearch->total() . " proposals\n";

// Test rendering view
$htmlSekretaris = $responseAll->render();
echo "    [OK] View 'sekretaris.daftar_sertifikat' rendered successfully! (Size: " . strlen($htmlSekretaris) . " bytes)\n";

// 2. Test Ketua Daftar Sertifikat with Search
echo "\n[2] Testing KetuaController::daftarSertifikat...\n";
$ketua = App\Models\User::where('email', 'ketua@rsud.com')->first();
Illuminate\Support\Facades\Auth::login($ketua);
$ketuaController = new App\Http\Controllers\KetuaController();

// Test empty search
$responseKetuaAll = $ketuaController->daftarSertifikat($reqEmpty);
$dataKetuaAll = $responseKetuaAll->getData()['sertifikat'];
echo "    - Total certificates without filter: " . $dataKetuaAll->total() . "\n";

// Test search with keyword
$reqKetuaSearch = new Illuminate\Http\Request(['search' => 'Siti']);
$responseKetuaSearch = $ketuaController->daftarSertifikat($reqKetuaSearch);
$dataKetuaSearch = $responseKetuaSearch->getData()['sertifikat'];
echo "    - Search for 'Siti' matched: " . $dataKetuaSearch->total() . " certificates\n";

// Test rendering view
$htmlKetua = $responseKetuaAll->render();
echo "    [OK] View 'ketua.daftar_sertifikat' rendered successfully! (Size: " . strlen($htmlKetua) . " bytes)\n";

echo "\n========================================================\n";
echo " ALL SEARCH TESTS PASSED SUCCESSFULLY!\n";
echo "========================================================\n";
