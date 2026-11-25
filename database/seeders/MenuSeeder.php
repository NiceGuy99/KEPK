<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role_menu')->truncate();
        DB::table('master_menu')->truncate();

        $menus = [
            // KATEGORI: ADMIN
            [
                'id' => 1,
                'nama_menu' => 'Dashboard',
                'route_name' => 'sekretaris.dashboard',
                'route_pattern' => 'sekretaris.dashboard*|ketua.dashboard*',
                'icon' => 'bi bi-grid-1x2',
                'urutan' => 1,
                'kategori' => 'Admin',
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
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_menu' => 'List Peneliti',
                'route_name' => 'sekretaris.peneliti',
                'route_pattern' => 'sekretaris.peneliti*|ketua.peneliti*',
                'icon' => 'bi bi-person-lines-fill',
                'urutan' => 3,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama_menu' => 'Proposal Masuk',
                'route_name' => 'sekretaris.proposal-masuk',
                'route_pattern' => 'sekretaris.proposal-masuk*|sekretaris.verifikasi*|ketua.proposal-masuk*|ketua.verifikasi*',
                'icon' => 'bi bi-inboxes',
                'urutan' => 4,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nama_menu' => 'Semua Proposal Masuk',
                'route_name' => 'sekretaris.proposal-belum-selesai',
                'route_pattern' => 'sekretaris.proposal-belum-selesai*|sekretaris.verifikasi*',
                'icon' => 'bi bi-journal-text',
                'urutan' => 5,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 6,
                'nama_menu' => 'Daftar Sertifikat',
                'route_name' => 'sekretaris.sertifikat',
                'route_pattern' => 'sekretaris.sertifikat*|ketua.sertifikat*',
                'icon' => 'bi bi-award',
                'urutan' => 6,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'nama_menu' => 'Tugas Penelaahan',
                'route_name' => 'sekretaris.tugas',
                'route_pattern' => 'sekretaris.tugas*|ketua.tugas*',
                'icon' => 'bi bi-journal-check',
                'urutan' => 7,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'nama_menu' => 'Riwayat Telaah',
                'route_name' => 'sekretaris.riwayat',
                'route_pattern' => 'sekretaris.riwayat*|sekretaris.detail-telaah*|ketua.riwayat*|ketua.detail-telaah*',
                'icon' => 'bi bi-clock-history',
                'urutan' => 8,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'nama_menu' => 'Laporan KEPK',
                'route_name' => 'laporan.index',
                'route_pattern' => 'laporan.*',
                'icon' => 'bi bi-file-earmark-bar-graph',
                'urutan' => 9,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'nama_menu' => 'Hak Akses & Menu',
                'route_name' => 'sekretaris.hak-akses',
                'route_pattern' => 'sekretaris.hak-akses*',
                'icon' => 'bi bi-shield-lock',
                'urutan' => 10,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 17,
                'nama_menu' => 'WhatsApp Gateway',
                'route_name' => 'sekretaris.wa-gateway',
                'route_pattern' => 'sekretaris.wa-gateway*',
                'icon' => 'bi bi-whatsapp',
                'urutan' => 11,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 18,
                'nama_menu' => 'Pengaturan Sertifikat',
                'route_name' => 'sekretaris.pengaturan-sertifikat',
                'route_pattern' => 'sekretaris.pengaturan-sertifikat*',
                'icon' => 'bi bi-sliders',
                'urutan' => 12,
                'kategori' => 'Admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // KATEGORI: PENELAAH
            [
                'id' => 11,
                'nama_menu' => 'Dashboard',
                'route_name' => 'penelaah.dashboard',
                'route_pattern' => 'penelaah.dashboard*',
                'icon' => 'bi bi-grid-1x2',
                'urutan' => 11,
                'kategori' => 'Penelaah',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'nama_menu' => 'Tugas Penelaahan',
                'route_name' => 'penelaah.tugas',
                'route_pattern' => 'penelaah.tugas*|penelaah.telaah*|penelaah.form7standar*',
                'icon' => 'bi bi-journal-check',
                'urutan' => 12,
                'kategori' => 'Penelaah',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'nama_menu' => 'Riwayat Telaah',
                'route_name' => 'penelaah.riwayat',
                'route_pattern' => 'penelaah.riwayat*',
                'icon' => 'bi bi-clock-history',
                'urutan' => 13,
                'kategori' => 'Penelaah',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // KATEGORI: PENELITI
            [
                'id' => 14,
                'nama_menu' => 'Beranda',
                'route_name' => 'peneliti.dashboard',
                'route_pattern' => 'peneliti.dashboard*',
                'icon' => 'bi bi-house-door',
                'urutan' => 14,
                'kategori' => 'Peneliti',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 15,
                'nama_menu' => 'Pengajuan Baru',
                'route_name' => 'peneliti.pengajuan',
                'route_pattern' => 'peneliti.pengajuan*',
                'icon' => 'bi bi-file-earmark-plus',
                'urutan' => 15,
                'kategori' => 'Peneliti',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 16,
                'nama_menu' => 'Status Proposal',
                'route_name' => 'peneliti.status',
                'route_pattern' => 'peneliti.status*|peneliti.detail*|peneliti.revisi*',
                'icon' => 'bi bi-folder2-open',
                'urutan' => 16,
                'kategori' => 'Peneliti',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('master_menu')->insert($menus);

        // Role Menu Mappings
        // Role 1 (Sekretaris): Menu 1..10
        // Role 2 (Ketua): Menu 1, 3, 4, 5, 6, 7, 8, 9
        // Role 3 (Penelaah): Menu 11, 12, 13
        // Role 4 (Peneliti): Menu 14, 15, 16
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
            ['role_id' => 1, 'menu_id' => 10],
            ['role_id' => 1, 'menu_id' => 17],
            ['role_id' => 1, 'menu_id' => 18],

            // Ketua
            ['role_id' => 2, 'menu_id' => 1],
            ['role_id' => 2, 'menu_id' => 3],
            ['role_id' => 2, 'menu_id' => 4],
            ['role_id' => 2, 'menu_id' => 5],
            ['role_id' => 2, 'menu_id' => 6],
            ['role_id' => 2, 'menu_id' => 7],
            ['role_id' => 2, 'menu_id' => 8],
            ['role_id' => 2, 'menu_id' => 9],
            ['role_id' => 2, 'menu_id' => 18],

            // Penelaah
            ['role_id' => 3, 'menu_id' => 11],
            ['role_id' => 3, 'menu_id' => 12],
            ['role_id' => 3, 'menu_id' => 13],

            // Peneliti
            ['role_id' => 4, 'menu_id' => 14],
            ['role_id' => 4, 'menu_id' => 15],
            ['role_id' => 4, 'menu_id' => 16],
        ];

        foreach ($roleMenus as &$rm) {
            $rm['created_at'] = now();
            $rm['updated_at'] = now();
        }

        DB::table('role_menu')->insert($roleMenus);
    }
}
