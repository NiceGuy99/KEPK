<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KetuaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenelaahController;
use App\Http\Controllers\PenelitiController;
use App\Http\Controllers\SekretarisController;
use App\Http\Controllers\SertifikatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::any('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/ganti-password', [AuthController::class, 'changePassword'])->name('password.change');

    // SHARED: Cetak Sertifikat
    Route::get('/sertifikat/cetak/{id}', [SertifikatController::class, 'cetak'])->name('sertifikat.cetak');

    // SHARED: Laporan & Pengaturan Sertifikat (Sekretaris & Ketua)
    Route::middleware('role:sekretaris,ketua')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

        Route::get('/pengaturan-sertifikat', [SekretarisController::class, 'pengaturanSertifikat'])->name('sekretaris.pengaturan-sertifikat');
        Route::post('/pengaturan-sertifikat', [SekretarisController::class, 'updatePengaturanSertifikat'])->name('sekretaris.pengaturan-sertifikat.update');
    });

    // ==========================================
    // 1. AREA SEKRETARIS (Admin KEPK)
    // ==========================================
    Route::middleware('role:sekretaris')->prefix('sekretaris')->name('sekretaris.')->group(function () {
        Route::get('/', [SekretarisController::class, 'dashboard'])->name('dashboard');
        Route::get('/data-pengguna', [SekretarisController::class, 'dataPengguna'])->name('pengguna');
        Route::post('/data-pengguna', [SekretarisController::class, 'storePengguna'])->name('pengguna.store');
        Route::put('/data-pengguna/{id}', [SekretarisController::class, 'updatePengguna'])->name('pengguna.update');
        Route::post('/data-pengguna/{id}/toggle-status', [SekretarisController::class, 'toggleUserStatus'])->name('pengguna.toggle');
        Route::delete('/data-pengguna/{id}', [SekretarisController::class, 'deletePengguna'])->name('pengguna.delete');

        Route::get('/list-peneliti', [SekretarisController::class, 'listPeneliti'])->name('peneliti');
        Route::get('/peneliti/{id}/proposal', [SekretarisController::class, 'proposalPeneliti'])->name('peneliti.proposal');

        Route::get('/proposal-masuk', [SekretarisController::class, 'proposalMasuk'])->name('proposal-masuk');
        Route::get('/proposal-belum-selesai', [SekretarisController::class, 'proposalBelumSelesai'])->name('proposal-belum-selesai');
        Route::get('/verifikasi-berkas/{id}', [SekretarisController::class, 'verifikasiBerkas'])->name('verifikasi');

        Route::post('/verifikasi-berkas/{id}', [SekretarisController::class, 'simpanVerifikasi'])->name('verifikasi.simpan');

        Route::get('/tugas-penelaahan', [SekretarisController::class, 'tugasPenelaahan'])->name('tugas');
        Route::post('/tugas-penelaahan/assign', [SekretarisController::class, 'assignReviewer'])->name('tugas.assign');
        Route::delete('/tugas-penelaahan/{id}', [SekretarisController::class, 'hapusPenugasan'])->name('tugas.delete');
        Route::put('/tugas-penelaahan/{id}/ganti', [SekretarisController::class, 'gantiPenelaah'])->name('tugas.ganti');

        Route::get('/riwayat-telaah', [SekretarisController::class, 'riwayatTelaah'])->name('riwayat');
        Route::get('/detail-hasil-telaah/{id}', [SekretarisController::class, 'detailTelaahSekretaris'])->name('detail-telaah');
        Route::get('/telaah/{id}', [SekretarisController::class, 'telaah'])->name('telaah');
        Route::post('/telaah/{id}', [SekretarisController::class, 'simpanTelaah'])->name('telaah.simpan');
        Route::get('/form-7standar/{id}', [SekretarisController::class, 'form7StandarSekretaris'])->name('form7standar');
        Route::post('/form-7standar/{id}', [SekretarisController::class, 'simpan7StandarSekretaris'])->name('form7standar.simpan');
        Route::get('/detail-7standar/{id}', [SekretarisController::class, 'detail7StandarSekretaris'])->name('detail-7standar');

        Route::get('/daftar-sertifikat', [SekretarisController::class, 'daftarSertifikat'])->name('sertifikat');
        Route::post('/sertifikat/simpan/{id}', [SekretarisController::class, 'simpanSertifikat'])->name('sertifikat.simpan');

        // Hak Akses & Menu Dinamis
        Route::get('/hak-akses', [SekretarisController::class, 'hakAkses'])->name('hak-akses');
        Route::post('/hak-akses', [SekretarisController::class, 'updateHakAkses'])->name('hak-akses.update');

        // WhatsApp Gateway Monitoring & Template Management
        Route::get('/whatsapp-gateway', [SekretarisController::class, 'waGateway'])->name('wa-gateway');
        Route::post('/whatsapp-gateway/test', [SekretarisController::class, 'testKirimWa'])->name('wa-gateway.test');
        Route::post('/whatsapp-gateway/template/{id}', [SekretarisController::class, 'waTemplateUpdate'])->name('wa-gateway.template.update');
        Route::post('/whatsapp-gateway/template/{id}/toggle', [SekretarisController::class, 'waTemplateToggle'])->name('wa-gateway.template.toggle');
        Route::post('/whatsapp-gateway/template', [SekretarisController::class, 'waTemplateStore'])->name('wa-gateway.template.store');
    });

    // ==========================================
    // 2. AREA KETUA
    // ==========================================
    Route::middleware('role:ketua')->prefix('ketua')->name('ketua.')->group(function () {
        Route::get('/', [KetuaController::class, 'dashboard'])->name('dashboard');
        Route::get('/list-peneliti', [KetuaController::class, 'listPeneliti'])->name('peneliti');
        Route::get('/peneliti/{id}/proposal', [KetuaController::class, 'proposalPeneliti'])->name('peneliti.proposal');

        Route::get('/proposal-masuk', [KetuaController::class, 'proposalMasuk'])->name('proposal-masuk');
        Route::get('/verifikasi-berkas/{id}', [KetuaController::class, 'verifikasiBerkas'])->name('verifikasi');
        Route::post('/verifikasi-berkas/{id}', [KetuaController::class, 'simpanVerifikasi'])->name('verifikasi.simpan');

        Route::get('/tugas-penelaahan', [KetuaController::class, 'tugasPenelaahan'])->name('tugas');
        Route::get('/riwayat-telaah', [KetuaController::class, 'riwayatTelaah'])->name('riwayat');
        Route::get('/detail-telaah/{id}', [KetuaController::class, 'detailTelaahSekretaris'])->name('detail-telaah');
        Route::get('/detail-7standar/{id}', [SekretarisController::class, 'detail7StandarSekretaris'])->name('detail-7standar');

        Route::get('/daftar-sertifikat', [KetuaController::class, 'daftarSertifikat'])->name('sertifikat');
        Route::post('/sertifikat/simpan/{id}', [KetuaController::class, 'simpanSertifikat'])->name('sertifikat.simpan');
        Route::get('/proposal-belum-selesai', [KetuaController::class, 'proposalBelumSelesai'])->name('proposal_belum_selesai');
    });


    // ==========================================
    // 3. AREA PENELAAH
    // ==========================================
    Route::middleware('role:penelaah')->prefix('penelaah')->name('penelaah.')->group(function () {
        Route::get('/', [PenelaahController::class, 'dashboard'])->name('dashboard');
        Route::get('/tugas-penelaahan', [PenelaahController::class, 'tugas'])->name('tugas');
        Route::get('/telaah/{id}', [PenelaahController::class, 'telaah'])->name('telaah');
        Route::get('/form-7standar/{id}', [PenelaahController::class, 'form7Standar'])->name('form7standar');
        Route::post('/form-7standar/{id}', [PenelaahController::class, 'simpan7Standar'])->name('form7standar.simpan');
        Route::post('/telaah/{id}', [PenelaahController::class, 'simpanTelaah'])->name('telaah.simpan');
        Route::get('/riwayat-telaah', [PenelaahController::class, 'riwayat'])->name('riwayat');
    });

    // ==========================================
    // 4. AREA PENELITI
    // ==========================================
    Route::middleware('role:peneliti')->prefix('peneliti')->name('peneliti.')->group(function () {
        Route::get('/', [PenelitiController::class, 'dashboard'])->name('dashboard');
        Route::get('/pengajuan-baru', [PenelitiController::class, 'create'])->name('pengajuan');
        Route::post('/pengajuan-baru', [PenelitiController::class, 'store'])->name('pengajuan.store');
        Route::get('/status-proposal', [PenelitiController::class, 'status'])->name('status');
        Route::get('/detail-proposal/{id}', [PenelitiController::class, 'detail'])->name('detail');
        Route::get('/revisi-proposal/{id}', [PenelitiController::class, 'editRevisi'])->name('revisi');
        Route::post('/revisi-proposal/{id}', [PenelitiController::class, 'updateRevisi'])->name('revisi.update');
    });
});