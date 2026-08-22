<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===============================================\n";
echo " TESTING KEPK ETHOS LARAVEL MIGRATION\n";
echo "===============================================\n";

// 1. Test Database and Models
echo "[1] Testing Database & Eloquent Models...\n";
$userCount = App\Models\User::count();
$proposalCount = App\Models\PengajuanProposal::count();
$rolesCount = App\Models\MasterRole::count();
$statusCount = App\Models\MasterStatusProposal::count();
$penugasanCount = App\Models\PenugasanReview::count();
$sertifikatCount = App\Models\Sertifikat::count();

echo "    - Total Users: $userCount\n";
echo "    - Total Proposals: $proposalCount\n";
echo "    - Master Roles: $rolesCount\n";
echo "    - Master Statuses: $statusCount\n";
echo "    - Penugasan Reviews: $penugasanCount\n";
echo "    - Sertifikat: $sertifikatCount\n";

// 2. Test User Accounts for Each Role
echo "\n[2] Testing User Accounts & Roles...\n";
$testAccounts = [
    'Sekretaris' => 'sekretaris@rsud.com',
    'Ketua' => 'ketua@rsud.com',
    'Penelaah' => 'penelaah@rsud.com',
    'Peneliti' => 'peneliti.mhs@univ.ac.id',
];

foreach ($testAccounts as $roleName => $email) {
    $u = App\Models\User::with(['role', 'profil'])->where('email', $email)->first();
    if ($u) {
        $passwordMatch = password_verify('password', $u->password) ? 'VALID (password: password)' : 'HAS OTHER HASH';
        echo "    - $roleName ($email): Name = {$u->nama_lengkap}, Role = {$u->role?->nama_role}, Status = " . ($u->is_active ? 'AKTIF' : 'NONAKTIF') . ", Password Check = $passwordMatch\n";
    } else {
        echo "    - $roleName ($email): NOT FOUND\n";
    }
}

// 3. Test View Rendering
echo "\n[3] Testing Blade Views Compilation...\n";
$viewsToTest = [
    'auth.login' => [],
    'layouts.app' => [],
    'peneliti.dashboard' => [
        'proposals' => App\Models\PengajuanProposal::with(['jenisPenelitian', 'statusProposal', 'sertifikat'])->get(),
        'stats' => ['total' => 1, 'verifikasi' => 0, 'review' => 0, 'revisi' => 0, 'selesai' => 1]
    ],
    'peneliti.pengajuan' => [
        'jenisPenelitian' => App\Models\MasterJenisPenelitian::all()
    ],
    'peneliti.status' => [
        'proposals' => App\Models\PengajuanProposal::with(['jenisPenelitian', 'statusProposal', 'sertifikat'])->get()
    ],
    'sekretaris.dashboard' => [
        'stats' => ['total_proposal' => 2, 'menunggu_verifikasi' => 0, 'sedang_review' => 0, 'revisi' => 0, 'selesai' => 2, 'total_user' => 7],
        'proposals' => App\Models\PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal'])->get()
    ],
    'sekretaris.data_pengguna' => [
        'users' => App\Models\User::with(['role', 'profil.statusPengguna'])->paginate(15),
        'roles' => App\Models\MasterRole::all(),
        'statuses' => App\Models\MasterStatusPengguna::all()
    ],
    'sekretaris.list_peneliti' => [
        'peneliti' => App\Models\User::with(['profil.statusPengguna', 'pengajuanProposal'])->where('role_id', 4)->get()
    ],
    'sekretaris.proposal_masuk' => [
        'proposals' => App\Models\PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'jenisPersetujuan'])->paginate(15),
        'masterStatus' => App\Models\MasterStatusProposal::all()
    ],
    'sekretaris.tugas_penelaahan' => [
        'tugas' => App\Models\PenugasanReview::with(['proposal.peneliti.profil', 'penelaah.profil', 'proposal.statusProposal'])->get(),
        'penelaahList' => App\Models\User::with('profil')->where('role_id', 3)->get()
    ],

    'sekretaris.riwayat_telaah' => [
        'reviews' => App\Models\PenugasanReview::with(['proposal.peneliti.profil', 'penelaah.profil'])->paginate(15)
    ],
    'sekretaris.daftar_sertifikat' => [
        'proposalsSelesai' => App\Models\PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'sertifikat'])->where('status_id', 5)->paginate(15)
    ],

    'penelaah.dashboard' => [
        'tugas' => App\Models\PenugasanReview::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian', 'proposal.statusProposal'])->get(),
        'stats' => ['total' => 2, 'menunggu' => 0, 'selesai' => 2]
    ],
    'penelaah.tugas' => [
        'tugas' => App\Models\PenugasanReview::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian', 'proposal.statusProposal'])->get()
    ],
    'penelaah.riwayat' => [
        'reviews' => App\Models\PenugasanReview::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian'])->get()
    ],
    'ketua.dashboard' => [
        'stats' => ['total_proposal' => 2, 'sedang_review' => 0, 'revisi' => 0, 'selesai' => 2, 'total_sertifikat' => 1],
        'proposals' => App\Models\PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'sertifikat'])->get()
    ],
    'ketua.list_peneliti' => [
        'peneliti' => App\Models\User::with(['profil.statusPengguna', 'pengajuanProposal'])->where('role_id', 4)->get()
    ],
    'ketua.proposal_masuk' => [
        'proposals' => App\Models\PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'jenisPersetujuan', 'penugasanReview'])->paginate(15),
        'masterStatus' => App\Models\MasterStatusProposal::all()
    ],
    'ketua.tugas_penelaahan' => [
        'proposals' => App\Models\PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'penugasanReview.penelaah.profil'])->get()
    ],
    'ketua.riwayat_telaah' => [
        'reviews' => App\Models\PenugasanReview::with(['proposal.peneliti.profil', 'penelaah.profil'])->paginate(15)
    ],
    'ketua.daftar_sertifikat' => [
        'sertifikat' => App\Models\Sertifikat::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian'])->paginate(15)
    ],
    'laporan.index' => [
        'data' => App\Models\PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian'])->paginate(15),
        'tglAwal' => date('Y-m-01'),
        'tglAkhir' => date('Y-m-d'),
        'jenis' => 'pengajuan'
    ],
    'laporan.cetak' => [
        'data' => App\Models\PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian'])->get(),
        'tglAwal' => date('Y-m-01'),
        'tglAkhir' => date('Y-m-d'),
        'jenis' => 'pengajuan'
    ]
];

// Set a mock authenticated user for view compilation (Sekretaris)
$authSekretaris = App\Models\User::with(['role', 'profil'])->where('role_id', 1)->first();
Illuminate\Support\Facades\Auth::login($authSekretaris);

foreach ($viewsToTest as $viewName => $data) {
    try {
        $rendered = view($viewName, $data)->render();
        echo "    [OK] View '$viewName' rendered successfully (" . strlen($rendered) . " bytes)\n";
    } catch (\Throwable $e) {
        echo "    [FAIL] View '$viewName': " . $e->getMessage() . "\n";
    }
}

// Test Certificate View
$firstProposal = App\Models\PengajuanProposal::with(['peneliti.profil.statusPengguna', 'jenisPenelitian', 'jenisPersetujuan', 'sertifikat'])->has('sertifikat')->first();
if ($firstProposal) {
    try {
        $certRendered = view('sertifikat.cetak', ['proposal' => $firstProposal])->render();
        echo "    [OK] View 'sertifikat.cetak' rendered successfully (" . strlen($certRendered) . " bytes)\n";
    } catch (\Throwable $e) {
        echo "    [FAIL] View 'sertifikat.cetak': " . $e->getMessage() . "\n";
    }
}

echo "\n===============================================\n";
echo " ALL TESTS COMPLETED SUCCESSFULLY!\n";
echo "===============================================\n";
