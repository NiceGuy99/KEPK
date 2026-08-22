<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class KepkWorkflowTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('ETHOS');
        $response->assertSee('RSUD Sidoarjo Barat');
    }

    public function test_sekretaris_can_access_dashboard_and_modules(): void
    {
        $sekretaris = User::where('email', 'sekretaris@rsud.com')->first();
        if ($sekretaris) {
            $response = $this->actingAs($sekretaris)->get('/sekretaris');
            $response->assertStatus(200);
            $response->assertSee('Dashboard Sekretariat KEPK');

            $resPengguna = $this->actingAs($sekretaris)->get('/sekretaris/data-pengguna');
            $resPengguna->assertStatus(200);

            $resProposal = $this->actingAs($sekretaris)->get('/sekretaris/proposal-masuk');
            $resProposal->assertStatus(200);
        }
    }

    public function test_ketua_can_access_dashboard_and_modules(): void
    {
        $ketua = User::where('email', 'ketua@rsud.com')->first();
        if ($ketua) {
            $response = $this->actingAs($ketua)->get('/ketua');
            $response->assertStatus(200);
            $response->assertSee('Dashboard Ketua KEPK');

            $resSertifikat = $this->actingAs($ketua)->get('/ketua/daftar-sertifikat');
            $resSertifikat->assertStatus(200);
        }
    }

    public function test_penelaah_can_access_dashboard_and_modules(): void
    {
        $penelaah = User::where('email', 'penelaah@rsud.com')->first();
        if ($penelaah) {
            $response = $this->actingAs($penelaah)->get('/penelaah');
            $response->assertStatus(200);
            $response->assertSee('Lembar kerja penelaahan kelaikan etik');

            $resTugas = $this->actingAs($penelaah)->get('/penelaah/tugas-penelaahan');
            $resTugas->assertStatus(200);
        }
    }

    public function test_peneliti_can_access_dashboard_and_modules(): void
    {
        $peneliti = User::where('email', 'peneliti.mhs@univ.ac.id')->first();
        if ($peneliti) {
            $response = $this->actingAs($peneliti)->get('/peneliti');
            $response->assertStatus(200);
            $response->assertSee('Dashboard Peneliti');

            $resPengajuan = $this->actingAs($peneliti)->get('/peneliti/pengajuan-baru');
            $resPengajuan->assertStatus(200);
            $resPengajuan->assertSee('Pengajuan Proposal Baru');

            $resStatus = $this->actingAs($peneliti)->get('/peneliti/status-proposal');
            $resStatus->assertStatus(200);
        }
    }

    public function test_role_protection_blocks_cross_role_access(): void
    {
        $peneliti = User::where('email', 'peneliti.mhs@univ.ac.id')->first();
        if ($peneliti) {
            // Peneliti should not be able to access sekretaris area
            $response = $this->actingAs($peneliti)->get('/sekretaris');
            $response->assertRedirect('/peneliti');
        }
    }
}
