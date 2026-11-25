<?php

namespace App\Services;

use App\Models\WaTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $url;
    protected bool $enabled;

    public function __construct()
    {
        $this->url = rtrim(config('services.wa_gateway.url', 'http://127.0.0.1:8088'), '/');
        $this->enabled = (bool) config('services.wa_gateway.enabled', true);
    }

    /**
     * Kirim pesan teks WhatsApp ke nomor tujuan
     */
    public function send(?string $phone, string $message): array
    {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'WhatsApp Gateway dinonaktifkan di konfigurasi.'];
        }

        if (empty($phone)) {
            return ['success' => false, 'message' => 'Nomor WhatsApp tidak tersedia.'];
        }

        try {
            $response = Http::timeout(5)->post("{$this->url}/api/send", [
                'phone' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("[WA Gateway] Pesan berhasil dikirim ke {$phone}");
                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'Pesan berhasil terkirim.',
                    'data' => $data
                ];
            }

            $errorMsg = $response->json('message') ?? 'Gagal mengirim pesan (Server response ' . $response->status() . ')';
            Log::warning("[WA Gateway] Gagal kirim ke {$phone}: {$errorMsg}");
            return ['success' => false, 'message' => $errorMsg];

        } catch (\Throwable $e) {
            Log::error("[WA Gateway] Koneksi gagal ke {$this->url}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server WhatsApp Gateway (Periksa apakah gateway aktif di ' . $this->url . ').'
            ];
        }
    }

    /**
     * Cek status koneksi WhatsApp Gateway
     */
    public function getStatus(): array
    {
        try {
            $response = Http::timeout(2)->get("{$this->url}/status");
            if ($response->successful()) {
                return $response->json();
            }
            return [
                'connected' => false,
                'status' => 'error',
                'message' => 'Gateway merespons dengan kode ' . $response->status()
            ];
        } catch (\Throwable $e) {
            return [
                'connected' => false,
                'status' => 'offline',
                'message' => 'WhatsApp Gateway belum dijalankan di port 8088.'
            ];
        }
    }

    /**
     * Render template pesan dinamis dari database (wa_templates)
     * Mengembalikan null jika template dinonaktifkan (is_active = false)
     */
    public function renderTemplate(string $slug, array $variables, string $defaultFallback = ''): ?string
    {
        try {
            $tpl = WaTemplate::where('slug', $slug)->first();

            // Jika ada di database
            if ($tpl) {
                // Jika pemicu ini dinonaktifkan oleh Sekretariat (OFF)
                if (!$tpl->is_active) {
                    Log::info("[WA Gateway] Notifikasi '{$slug}' dilewati (status: nonaktif di pengaturan).");
                    return null;
                }
                $rawPesan = $tpl->template_pesan;
            } else {
                $rawPesan = $defaultFallback;
            }

            if (empty($rawPesan)) {
                return null;
            }

            // Ganti variabel seperti {nama_lengkap}, {judul_penelitian}, dll
            foreach ($variables as $key => $val) {
                $valStr = (string) ($val ?? '-');
                $rawPesan = str_replace([
                    '{' . $key . '}',
                    '{ ' . $key . ' }',
                    '{{' . $key . '}}',
                    '{{ ' . $key . ' }}'
                ], $valStr, $rawPesan);
            }

            return $rawPesan;
        } catch (\Throwable $e) {
            Log::error("[WA Gateway] Gagal render template '{$slug}': " . $e->getMessage());
            return !empty($defaultFallback) ? $defaultFallback : null;
        }
    }

    // =========================================================================
    // TEMPLATE NOTIFIKASI OTOMATIS e-KEPK ETHOS (DYNAMIC FROM DATABASE)
    // =========================================================================

    /**
     * Notifikasi saat Peneliti berhasil mengajukan protokol baru
     */
    public function notifProposalBaru($peneliti, $proposal): array
    {
        $phone = $peneliti->profil?->nomor_telepon;
        $nama = $peneliti->nama_lengkap ?? 'Peneliti';
        $judul = $proposal->judul_penelitian ?? '-';
        $kode = $proposal->kode_proposal ?? '-';
        $tgl = date('d/m/Y H:i');

        $variables = [
            'nama_lengkap' => $nama,
            'judul_penelitian' => $judul,
            'kode_proposal' => $kode,
            'tgl' => $tgl,
        ];

        $fallback = "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n"
                  . "--------------------------------------------------\n"
                  . "Halo, *{$nama}* 👋\n\n"
                  . "Usulan protokol penelitian Anda telah *berhasil diajukan* ke Komite Etik Penelitian Kesehatan:\n\n"
                  . "📄 *Judul:* {$judul}\n"
                  . "🔢 *Kode Protokol:* #{$kode}\n"
                  . "📅 *Waktu Pengajuan:* {$tgl} WIB\n\n"
                  . "Berkas Anda saat ini berada dalam antrean *Verifikasi Administrasi* oleh Sekretariat KEPK.\n\n"
                  . "_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._";

        $pesan = $this->renderTemplate('proposal_baru', $variables, $fallback);

        if ($pesan === null) {
            return ['success' => true, 'message' => 'Notifikasi proposal baru dinonaktifkan di pengaturan.'];
        }

        return $this->send($phone, $pesan);
    }

    /**
     * Notifikasi hasil verifikasi administrasi (Lolos / Perlu Revisi)
     */
    public function notifVerifikasi($peneliti, $proposal, bool $isLolos, ?string $catatan = null): array
    {
        $phone = $peneliti->profil?->nomor_telepon;
        $nama = $peneliti->nama_lengkap ?? 'Peneliti';
        $kode = $proposal->kode_proposal ?? '-';
        $judul = $proposal->judul_penelitian ?? '-';
        $tgl = date('d/m/Y H:i');

        if ($isLolos) {
            $variables = [
                'nama_lengkap' => $nama,
                'judul_penelitian' => $judul,
                'kode_proposal' => $kode,
                'tgl' => $tgl,
            ];

            $fallback = "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n"
                      . "--------------------------------------------------\n"
                      . "Halo, *{$nama}* 👋\n\n"
                      . "Kabar baik! Berkas protokol penelitian Anda dengan kode *#{$kode}* telah dinyatakan:\n\n"
                      . "✅ *LOLOS VERIFIKASI ADMINISTRASI*\n"
                      . "📄 *Judul:* {$judul}\n\n"
                      . "Selanjutnya berkas Anda diteruskan kepada *Penelaah Etik* untuk kajian kelaikan 7 Standar Etik.\n\n"
                      . "_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._";

            $pesan = $this->renderTemplate('verifikasi_lolos', $variables, $fallback);
        } else {
            $variables = [
                'nama_lengkap' => $nama,
                'judul_penelitian' => $judul,
                'kode_proposal' => $kode,
                'catatan_revisi' => $catatan ?? '-',
                'tgl' => $tgl,
            ];

            $fallback = "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n"
                      . "--------------------------------------------------\n"
                      . "Halo, *{$nama}* 👋\n\n"
                      . "Pemberitahuan berkas protokol penelitian Anda dengan kode *#{$kode}*:\n\n"
                      . "⚠️ *MEMERLUKAN PERBAIKAN / REVISI ADMINISTRASI*\n"
                      . "📄 *Judul:* {$judul}\n\n"
                      . "📌 *Catatan Perbaikan dari Sekretariat:*\n"
                      . "\"{$catatan}\"\n\n"
                      . "Harap login ke sistem e-KEPK ETHOS untuk mengunggah dokumen perbaikan.\n\n"
                      . "_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._";

            $pesan = $this->renderTemplate('verifikasi_revisi', $variables, $fallback);
        }

        if ($pesan === null) {
            return ['success' => true, 'message' => 'Notifikasi verifikasi dinonaktifkan di pengaturan.'];
        }

        return $this->send($phone, $pesan);
    }

    /**
     * Notifikasi penugasan review ke Penelaah Etik
     */
    public function notifPenugasanReviewer($penelaah, $proposal): array
    {
        $phone = $penelaah->profil?->nomor_telepon;
        $nama = $penelaah->nama_lengkap ?? 'Penelaah';
        $kode = $proposal->kode_proposal ?? '-';
        $judul = $proposal->judul_penelitian ?? '-';
        $penelitiNama = $proposal->peneliti?->nama_lengkap ?? 'Peneliti';
        $asalKampus = $proposal->peneliti?->profil?->asal_kampus ?? '-';
        $tgl = date('d/m/Y H:i');

        $variables = [
            'nama_lengkap' => $nama,
            'judul_penelitian' => $judul,
            'kode_proposal' => $kode,
            'nama_peneliti' => $penelitiNama,
            'asal_kampus' => $asalKampus,
            'tgl' => $tgl,
        ];

        $fallback = "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n"
                  . "--------------------------------------------------\n"
                  . "Yth. Bapak/Ibu Penelaah Etik,\n"
                  . "*{$nama}*\n\n"
                  . "Anda telah ditugaskan oleh Sekretariat KEPK untuk melakukan kajian kelaikan etik terhadap usulan protokol riset berikut:\n\n"
                  . "🔢 *Kode Protokol:* #{$kode}\n"
                  . "📄 *Judul Riset:* {$judul}\n"
                  . "👤 *Peneliti:* {$penelitiNama} ({$asalKampus})\n\n"
                  . "Mohon kesediaannya untuk login ke sistem e-KEPK ETHOS guna meninjau naskah proposal dan mengisi *Formulir 7 Standar Etik*.\n\n"
                  . "_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._";

        $pesan = $this->renderTemplate('penugasan_reviewer', $variables, $fallback);

        if ($pesan === null) {
            return ['success' => true, 'message' => 'Notifikasi penugasan reviewer dinonaktifkan di pengaturan.'];
        }

        return $this->send($phone, $pesan);
    }

    /**
     * Notifikasi penerbitan Sertifikat Ethical Clearance resmi ke Peneliti
     */
    public function notifSertifikatTerbit($peneliti, $proposal, string $noSertifikat): array
    {
        $phone = $peneliti->profil?->nomor_telepon;
        $nama = $peneliti->nama_lengkap ?? 'Peneliti';
        $kode = $proposal->kode_proposal ?? '-';
        $judul = $proposal->judul_penelitian ?? '-';
        $tgl = date('d/m/Y H:i');

        $variables = [
            'nama_lengkap' => $nama,
            'judul_penelitian' => $judul,
            'kode_proposal' => $kode,
            'nomor_sertifikat' => $noSertifikat,
            'tgl' => $tgl,
        ];

        $fallback = "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n"
                  . "--------------------------------------------------\n"
                  . "Selamat, *{$nama}*! 🎓✨\n\n"
                  . "Protokol penelitian Anda telah dinyatakan *LOLOS KAJI ETIK (ETHICAL CLEARANCE)*.\n\n"
                  . "📄 *Judul:* {$judul}\n"
                  . "🔢 *Kode Protokol:* #{$kode}\n"
                  . "📜 *Nomor Sertifikat:* {$noSertifikat}\n\n"
                  . "Surat Keterangan Kelaikan Etik resmi dari RSUD Sidoarjo Barat telah diterbitkan dan dapat langsung diunduh melalui sistem e-KEPK ETHOS.\n\n"
                  . "_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._";

        $pesan = $this->renderTemplate('sertifikat_terbit', $variables, $fallback);

        if ($pesan === null) {
            return ['success' => true, 'message' => 'Notifikasi sertifikat terbit dinonaktifkan di pengaturan.'];
        }

        return $this->send($phone, $pesan);
    }

    /**
     * Notifikasi pembuatan akun baru berisi email dan password ke Peneliti / Pengguna Baru
     */
    public function notifAkunPenggunaBaru($user, $profil, string $plainPassword): array
    {
        // Jika role adalah Penelaah, arahkan ke template khusus Penelaah
        if (($user->role_id ?? null) == 3 || str_contains(strtolower($user->role?->nama_role ?? ''), 'penelaah')) {
            return $this->notifAkunPenelaahBaru($user, $profil, $plainPassword);
        }

        $phone = $profil?->nomor_telepon;
        $nama = $profil?->nama_lengkap ?? $user->nama_lengkap ?? 'Pengguna';
        $email = $user->email ?? $profil?->email ?? '-';
        $roleName = $user->role?->nama_role ?? 'Pengguna';
        $loginUrl = url('/login');
        $tgl = date('d/m/Y H:i');

        $variables = [
            'nama_lengkap' => $nama,
            'email' => $email,
            'password' => $plainPassword,
            'role' => $roleName,
            'login_url' => $loginUrl,
            'tgl' => $tgl,
        ];

        $fallback = "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n"
                  . "--------------------------------------------------\n"
                  . "Halo, *{$nama}* 👋\n\n"
                  . "Akun Anda untuk Sistem Informasi e-KEPK ETHOS RSUD Sidoarjo Barat telah *berhasil dibuat*.\n\n"
                  . "Berikut adalah rincian informasi login Anda:\n\n"
                  . "👤 *Nama:* {$nama}\n"
                  . "📧 *Email / Username:* {$email}\n"
                  . "🔑 *Password:* {$plainPassword}\n"
                  . "🏷️ *Hak Akses:* {$roleName}\n"
                  . "🌐 *Tautan Login:* {$loginUrl}\n\n"
                  . "Silakan login ke sistem untuk melengkapi profil penelitian atau mengajukan usulan protokol kaji etik baru. Demi keamanan akun, Anda dapat memperbarui password setelah berhasil masuk pertama kali.\n\n"
                  . "_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._";

        $pesan = $this->renderTemplate('akun_pengguna_baru', $variables, $fallback);

        if ($pesan === null) {
            return ['success' => true, 'message' => 'Notifikasi akun pengguna baru dinonaktifkan di pengaturan template.'];
        }

        return $this->send($phone, $pesan);
    }

    /**
     * Notifikasi pembuatan akun baru berisi email dan password ke Penelaah / Reviewer
     */
    public function notifAkunPenelaahBaru($user, $profil, string $plainPassword): array
    {
        $phone = $profil?->nomor_telepon;
        $nama = $profil?->nama_lengkap ?? $user->nama_lengkap ?? 'Bapak/Ibu Penelaah';
        $email = $user->email ?? $profil?->email ?? '-';
        $roleName = $user->role?->nama_role ?? 'Penelaah';
        $loginUrl = url('/login');
        $tgl = date('d/m/Y H:i');

        $variables = [
            'nama_lengkap' => $nama,
            'email' => $email,
            'password' => $plainPassword,
            'role' => $roleName,
            'login_url' => $loginUrl,
            'tgl' => $tgl,
        ];

        $fallback = "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n"
                  . "--------------------------------------------------\n"
                  . "Yth. Bapak/Ibu Penelaah Etik,\n"
                  . "*{$nama}*\n\n"
                  . "Akun Anda sebagai *Penelaah / Reviewer Etik* pada Komite Etik Penelitian Kesehatan (e-KEPK ETHOS) RSUD Sidoarjo Barat telah *berhasil dibuat*.\n\n"
                  . "Berikut adalah rincian kredensial login Anda:\n\n"
                  . "👤 *Nama:* {$nama}\n"
                  . "📧 *Email / Username:* {$email}\n"
                  . "🔑 *Password:* {$plainPassword}\n"
                  . "🏷️ *Hak Akses:* {$roleName}\n"
                  . "🌐 *Tautan Login:* {$loginUrl}\n\n"
                  . "Bapak/Ibu dapat segera login ke sistem untuk meninjau usulan protokol penelitian dan melakukan kajian kelaikan 7 Standar Etik. Demi keamanan akun, Anda dapat memperbarui password setelah berhasil masuk pertama kali.\n\n"
                  . "Terima kasih atas dedikasi dan kontribusi Bapak/Ibu dalam menjaga keluhuran etika penelitian kesehatan.\n\n"
                  . "_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._";

        $pesan = $this->renderTemplate('akun_penelaah_baru', $variables, $fallback);

        if ($pesan === null) {
            return ['success' => true, 'message' => 'Notifikasi akun penelaah baru dinonaktifkan di pengaturan template.'];
        }

        return $this->send($phone, $pesan);
    }
}
