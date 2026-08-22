<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('master_menu')) {
            Schema::create('master_menu', function (Blueprint $table) {
                $table->id();
                $table->string('nama_menu');
                $table->string('route_name');
                $table->string('route_pattern')->nullable();
                $table->string('icon')->default('bi bi-circle');
                $table->integer('urutan')->default(0);
                $table->string('kategori')->default('Utama');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('role_menu')) {
            Schema::create('role_menu', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('menu_id');
                $table->timestamps();

                $table->foreign('role_id')->references('id')->on('master_role')->onDelete('cascade');
                $table->foreign('menu_id')->references('id')->on('master_menu')->onDelete('cascade');
                $table->unique(['role_id', 'menu_id']);
            });
        }

        // Seed initial menus & role assignments if table is empty
        if (DB::table('master_menu')->count() === 0) {
            $menus = [
                // MODUL SEKRETARIS
                [
                    'id' => 1,
                    'nama_menu' => 'Dashboard Sekretaris',
                    'route_name' => 'sekretaris.dashboard',
                    'route_pattern' => 'sekretaris.dashboard',
                    'icon' => 'bi bi-grid-1x2',
                    'urutan' => 1,
                    'kategori' => 'Sekretaris',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'nama_menu' => 'Data Pengguna',
                    'route_name' => 'sekretaris.pengguna',
                    'route_pattern' => 'sekretaris.pengguna*',
                    'icon' => 'bi bi-people',
                    'urutan' => 2,
                    'kategori' => 'Sekretaris',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'nama_menu' => 'List Peneliti (Sekretaris)',
                    'route_name' => 'sekretaris.peneliti',
                    'route_pattern' => 'sekretaris.peneliti*',
                    'icon' => 'bi bi-person-lines-fill',
                    'urutan' => 3,
                    'kategori' => 'Sekretaris',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 4,
                    'nama_menu' => 'Proposal Masuk (Sekretaris)',
                    'route_name' => 'sekretaris.proposal-masuk',
                    'route_pattern' => 'sekretaris.proposal-masuk*|sekretaris.verifikasi*',
                    'icon' => 'bi bi-inboxes',
                    'urutan' => 4,
                    'kategori' => 'Sekretaris',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 5,
                    'nama_menu' => 'Daftar Sertifikat (Sekretaris)',
                    'route_name' => 'sekretaris.sertifikat',
                    'route_pattern' => 'sekretaris.sertifikat*',
                    'icon' => 'bi bi-award',
                    'urutan' => 5,
                    'kategori' => 'Sekretaris',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 6,
                    'nama_menu' => 'Tugas Penelaahan (Sekretaris)',
                    'route_name' => 'sekretaris.tugas',
                    'route_pattern' => 'sekretaris.tugas*',
                    'icon' => 'bi bi-journal-check',
                    'urutan' => 6,
                    'kategori' => 'Sekretaris',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 7,
                    'nama_menu' => 'Riwayat Telaah (Sekretaris)',
                    'route_name' => 'sekretaris.riwayat',
                    'route_pattern' => 'sekretaris.riwayat*|sekretaris.detail-telaah*',
                    'icon' => 'bi bi-clock-history',
                    'urutan' => 7,
                    'kategori' => 'Sekretaris',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 8,
                    'nama_menu' => 'Laporan KEPK',
                    'route_name' => 'laporan.index',
                    'route_pattern' => 'laporan.*',
                    'icon' => 'bi bi-file-earmark-bar-graph',
                    'urutan' => 8,
                    'kategori' => 'Laporan',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 9,
                    'nama_menu' => 'Manajemen Hak Akses',
                    'route_name' => 'sekretaris.hak-akses',
                    'route_pattern' => 'sekretaris.hak-akses*',
                    'icon' => 'bi bi-shield-lock',
                    'urutan' => 9,
                    'kategori' => 'Pengaturan',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

                // MODUL KETUA
                [
                    'id' => 10,
                    'nama_menu' => 'Dashboard Ketua',
                    'route_name' => 'ketua.dashboard',
                    'route_pattern' => 'ketua.dashboard',
                    'icon' => 'bi bi-grid-1x2',
                    'urutan' => 10,
                    'kategori' => 'Ketua',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 11,
                    'nama_menu' => 'List Peneliti (Ketua)',
                    'route_name' => 'ketua.peneliti',
                    'route_pattern' => 'ketua.peneliti*',
                    'icon' => 'bi bi-person-lines-fill',
                    'urutan' => 11,
                    'kategori' => 'Ketua',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 12,
                    'nama_menu' => 'Proposal Masuk (Ketua)',
                    'route_name' => 'ketua.proposal-masuk',
                    'route_pattern' => 'ketua.proposal-masuk*|ketua.verifikasi*',
                    'icon' => 'bi bi-inboxes',
                    'urutan' => 12,
                    'kategori' => 'Ketua',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 13,
                    'nama_menu' => 'Daftar Sertifikat (Ketua)',
                    'route_name' => 'ketua.sertifikat',
                    'route_pattern' => 'ketua.sertifikat*',
                    'icon' => 'bi bi-award',
                    'urutan' => 13,
                    'kategori' => 'Ketua',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 14,
                    'nama_menu' => 'Tugas Penelaahan (Ketua)',
                    'route_name' => 'ketua.tugas',
                    'route_pattern' => 'ketua.tugas*',
                    'icon' => 'bi bi-journal-check',
                    'urutan' => 14,
                    'kategori' => 'Ketua',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 15,
                    'nama_menu' => 'Riwayat Telaah (Ketua)',
                    'route_name' => 'ketua.riwayat',
                    'route_pattern' => 'ketua.riwayat*',
                    'icon' => 'bi bi-clock-history',
                    'urutan' => 15,
                    'kategori' => 'Ketua',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

                // MODUL PENELAAH
                [
                    'id' => 16,
                    'nama_menu' => 'Dashboard Penelaah',
                    'route_name' => 'penelaah.dashboard',
                    'route_pattern' => 'penelaah.dashboard',
                    'icon' => 'bi bi-grid-1x2',
                    'urutan' => 16,
                    'kategori' => 'Penelaah',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 17,
                    'nama_menu' => 'Tugas Penelaahan (Penelaah)',
                    'route_name' => 'penelaah.tugas',
                    'route_pattern' => 'penelaah.tugas*|penelaah.telaah*|penelaah.form7standar*',
                    'icon' => 'bi bi-journal-check',
                    'urutan' => 17,
                    'kategori' => 'Penelaah',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 18,
                    'nama_menu' => 'Riwayat Telaah (Penelaah)',
                    'route_name' => 'penelaah.riwayat',
                    'route_pattern' => 'penelaah.riwayat*',
                    'icon' => 'bi bi-clock-history',
                    'urutan' => 18,
                    'kategori' => 'Penelaah',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

                // MODUL PENELITI
                [
                    'id' => 19,
                    'nama_menu' => 'Beranda Peneliti',
                    'route_name' => 'peneliti.dashboard',
                    'route_pattern' => 'peneliti.dashboard',
                    'icon' => 'bi bi-house-door',
                    'urutan' => 19,
                    'kategori' => 'Peneliti',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 20,
                    'nama_menu' => 'Pengajuan Baru',
                    'route_name' => 'peneliti.pengajuan',
                    'route_pattern' => 'peneliti.pengajuan*',
                    'icon' => 'bi bi-file-earmark-plus',
                    'urutan' => 20,
                    'kategori' => 'Peneliti',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 21,
                    'nama_menu' => 'Status Proposal',
                    'route_name' => 'peneliti.status',
                    'route_pattern' => 'peneliti.status*|peneliti.detail*|peneliti.revisi*',
                    'icon' => 'bi bi-folder2-open',
                    'urutan' => 21,
                    'kategori' => 'Peneliti',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('master_menu')->insert($menus);

            // Default Role Mappings:
            // Role 1 (Sekretaris): Menu 1..9
            // Role 2 (Ketua): Menu 10..15 + 8 (Laporan)
            // Role 3 (Penelaah): Menu 16..18
            // Role 4 (Peneliti): Menu 19..21
            $roleMenus = [
                // Sekretaris
                ['role_id' => 1, 'menu_id' => 1],
                ['role_id' => 1, 'menu_id' => 2],
                ['role_id' => 1, 'menu_id' => 3],
                ['role_id' => 1, 'menu_id' => 4],
                ['role_id' => 1, 'menu_id' => 5],
                ['role_id' => 1, 'menu_id' => 6],
                ['role_id' => 1, 'menu_id' => 7],
                ['role_id' => 1, 'menu_id' => 8],
                ['role_id' => 1, 'menu_id' => 9],

                // Ketua
                ['role_id' => 2, 'menu_id' => 10],
                ['role_id' => 2, 'menu_id' => 11],
                ['role_id' => 2, 'menu_id' => 12],
                ['role_id' => 2, 'menu_id' => 13],
                ['role_id' => 2, 'menu_id' => 14],
                ['role_id' => 2, 'menu_id' => 15],
                ['role_id' => 2, 'menu_id' => 8],

                // Penelaah
                ['role_id' => 3, 'menu_id' => 16],
                ['role_id' => 3, 'menu_id' => 17],
                ['role_id' => 3, 'menu_id' => 18],

                // Peneliti
                ['role_id' => 4, 'menu_id' => 19],
                ['role_id' => 4, 'menu_id' => 20],
                ['role_id' => 4, 'menu_id' => 21],
            ];

            foreach ($roleMenus as &$rm) {
                $rm['created_at'] = now();
                $rm['updated_at'] = now();
            }

            DB::table('role_menu')->insert($roleMenus);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_menu');
        Schema::dropIfExists('master_menu');
    }
};
