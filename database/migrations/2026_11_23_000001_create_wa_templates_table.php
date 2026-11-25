<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('wa_templates')) {
            Schema::create('wa_templates', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 50)->unique();
                $table->string('nama_pemicu', 100);
                $table->string('target_role', 50);
                $table->string('deskripsi', 255)->nullable();
                $table->text('template_pesan');
                $table->string('variabel_tersedia', 255)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Seed initial templates if empty
        if (DB::table('wa_templates')->count() === 0) {
            DB::table('wa_templates')->insert([
                [
                    'slug' => 'proposal_baru',
                    'nama_pemicu' => 'Usulan Baru Masuk',
                    'target_role' => 'Peneliti',
                    'deskripsi' => 'Konfirmasi kepada peneliti bahwa usulan telah berhasil diajukan dan masuk ke antrean verifikasi.',
                    'variabel_tersedia' => '{nama_lengkap}, {judul_penelitian}, {kode_proposal}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nHalo, *{nama_lengkap}* 👋\n\nUsulan protokol penelitian Anda telah *berhasil diajukan* ke Komite Etik Penelitian Kesehatan:\n\n📄 *Judul:* {judul_penelitian}\n🔢 *Kode Protokol:* #{kode_proposal}\n📅 *Waktu Pengajuan:* {tgl} WIB\n\nBerkas Anda saat ini berada dalam antrean *Verifikasi Administrasi* oleh Sekretariat KEPK. Anda dapat memantau perkembangan proses telaah melalui website resmi e-KEPK ETHOS.\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'verifikasi_lolos',
                    'nama_pemicu' => 'Verifikasi Lolos Administrasi',
                    'target_role' => 'Peneliti',
                    'deskripsi' => 'Pemberitahuan bahwa berkas usulan lolos verifikasi berkas dan diteruskan ke reviewer/penelaah.',
                    'variabel_tersedia' => '{nama_lengkap}, {judul_penelitian}, {kode_proposal}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nHalo, *{nama_lengkap}* 👋\n\nKabar baik! Berkas protokol penelitian Anda dengan kode *#{kode_proposal}* telah dinyatakan:\n\n✅ *LOLOS VERIFIKASI ADMINISTRASI*\n📄 *Judul:* {judul_penelitian}\n\nSelanjutnya berkas Anda diteruskan kepada *Penelaah Etik* untuk kajian kelaikan 7 Standar Etik. Mohon menunggu hasil telaah berikutnya.\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'verifikasi_revisi',
                    'nama_pemicu' => 'Perlu Perbaikan (Revisi)',
                    'target_role' => 'Peneliti',
                    'deskripsi' => 'Pemberitahuan kepada peneliti bahwa berkas memerlukan perbaikan/kelengkapan dokumen.',
                    'variabel_tersedia' => '{nama_lengkap}, {judul_penelitian}, {kode_proposal}, {catatan_revisi}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nHalo, *{nama_lengkap}* 👋\n\nPemberitahuan berkas protokol penelitian Anda dengan kode *#{kode_proposal}*:\n\n⚠️ *MEMERLUKAN PERBAIKAN / REVISI ADMINISTRASI*\n📄 *Judul:* {judul_penelitian}\n\n📌 *Catatan Perbaikan dari Sekretariat:*\n\"{catatan_revisi}\"\n\nHarap segera login ke sistem e-KEPK ETHOS untuk mengunggah dokumen perbaikan sesuai catatan di atas.\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'penugasan_reviewer',
                    'nama_pemicu' => 'Penugasan Reviewer',
                    'target_role' => 'Penelaah',
                    'deskripsi' => 'Pemberitahuan penugasan telaah protokol riset baru kepada penelaah / reviewer.',
                    'variabel_tersedia' => '{nama_lengkap}, {judul_penelitian}, {kode_proposal}, {nama_peneliti}, {asal_kampus}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nYth. Bapak/Ibu Penelaah Etik,\n*{nama_lengkap}*\n\nAnda telah ditugaskan oleh Sekretariat KEPK untuk melakukan kajian kelaikan etik terhadap usulan protokol riset berikut:\n\n🔢 *Kode Protokol:* #{kode_proposal}\n📄 *Judul Riset:* {judul_penelitian}\n👤 *Peneliti:* {nama_peneliti} ({asal_kampus})\n\nMohon kesediaannya untuk login ke sistem e-KEPK ETHOS guna meninjau naskah proposal dan mengisi *Formulir 7 Standar Etik*.\n\nTerima kasih atas dedikasi dan kerjasamanya.\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'sertifikat_terbit',
                    'nama_pemicu' => 'Sertifikat Terbit',
                    'target_role' => 'Peneliti',
                    'deskripsi' => 'Pemberitahuan kelulusan kaji etik (Ethical Clearance) dan nomor sertifikat resmi.',
                    'variabel_tersedia' => '{nama_lengkap}, {judul_penelitian}, {kode_proposal}, {nomor_sertifikat}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nSelamat, *{nama_lengkap}*! 🎓✨\n\nProtokol penelitian Anda telah dinyatakan *LOLOS KAJI ETIK (ETHICAL CLEARANCE)*.\n\n📄 *Judul:* {judul_penelitian}\n🔢 *Kode Protokol:* #{kode_proposal}\n📜 *Nomor Sertifikat:* {nomor_sertifikat}\n\nSurat Keterangan Kelaikan Etik resmi dari RSUD Sidoarjo Barat telah diterbitkan dan dapat langsung Anda unduh/cetak melalui menu *Status Proposal* pada sistem e-KEPK ETHOS.\n\nSemoga penelitian Anda berjalan dengan lancar dan memberikan manfaat bagi pelayanan kesehatan!\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'revisi_masuk',
                    'nama_pemicu' => 'Peneliti Kirim Revisi',
                    'target_role' => 'Sekretariat',
                    'deskripsi' => 'Pemberitahuan kepada sekretariat bahwa peneliti telah mengunggah perbaikan dokumen.',
                    'variabel_tersedia' => '{nama_peneliti}, {judul_penelitian}, {kode_proposal}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nPemberitahuan Sekretariat KEPK 📋\n\nPeneliti *{nama_peneliti}* telah mengunggah berkas perbaikan (revisi) untuk protokol:\n\n🔢 *Kode Protokol:* #{kode_proposal}\n📄 *Judul:* {judul_penelitian}\n📅 *Waktu Unggah:* {tgl} WIB\n\nMohon memeriksa kelengkapan berkas revisi pada menu *Pemeriksaan Berkas*.\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'telaah_selesai',
                    'nama_pemicu' => 'Penelaah Selesai Telaah',
                    'target_role' => 'Sekretariat',
                    'deskripsi' => 'Pemberitahuan kepada sekretariat bahwa penelaah telah menyelesaikan telaah 7 standar etik.',
                    'variabel_tersedia' => '{nama_penelaah}, {judul_penelitian}, {kode_proposal}, {keputusan}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nPemberitahuan Sekretariat KEPK ⚖️\n\nPenelaah *{nama_penelaah}* telah menyelesaikan kajian etik untuk protokol:\n\n🔢 *Kode Protokol:* #{kode_proposal}\n📄 *Judul:* {judul_penelitian}\n📌 *Rekomendasi:* {keputusan}\n📅 *Waktu Selesai:* {tgl} WIB\n\nSilakan melanjutkan ke tahapan rekapitulasi / keputusan Ketua KEPK.\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'akun_pengguna_baru',
                    'nama_pemicu' => 'Akun Peneliti Baru Dibuat',
                    'target_role' => 'Peneliti',
                    'deskripsi' => 'Kirim pesan sambutan resmi dan informasi login (email & password) saat sekretariat mendaftarkan akun Peneliti baru.',
                    'variabel_tersedia' => '{nama_lengkap}, {email}, {password}, {role}, {login_url}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nHalo, *{nama_lengkap}* 👋\n\nAkun Anda untuk Sistem Informasi e-KEPK ETHOS RSUD Sidoarjo Barat telah *berhasil dibuat*.\n\nBerikut adalah rincian informasi login Anda:\n\n👤 *Nama:* {nama_lengkap}\n📧 *Email / Username:* {email}\n🔑 *Password:* {password}\n🏷️ *Hak Akses:* {role}\n🌐 *Tautan Login:* {login_url}\n\nSilakan login ke sistem untuk melengkapi profil penelitian atau mengajukan usulan protokol kaji etik baru. Demi keamanan akun, Anda dapat memperbarui password setelah berhasil masuk pertama kali.\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'akun_penelaah_baru',
                    'nama_pemicu' => 'Akun Penelaah Baru Dibuat',
                    'target_role' => 'Penelaah',
                    'deskripsi' => 'Kirim pesan sambutan resmi dan kredensial login (email & password) saat sekretariat mendaftarkan akun Penelaah / Reviewer baru.',
                    'variabel_tersedia' => '{nama_lengkap}, {email}, {password}, {role}, {login_url}, {tgl}',
                    'is_active' => true,
                    'template_pesan' => "🏛️ *e-KEPK ETHOS RSUD SIDOARJO BARAT*\n--------------------------------------------------\nYth. Bapak/Ibu Penelaah Etik,\n*{nama_lengkap}*\n\nAkun Anda sebagai *Penelaah / Reviewer Etik* pada Komite Etik Penelitian Kesehatan (e-KEPK ETHOS) RSUD Sidoarjo Barat telah *berhasil dibuat*.\n\nBerikut adalah rincian kredensial login Anda:\n\n👤 *Nama:* {nama_lengkap}\n📧 *Email / Username:* {email}\n🔑 *Password:* {password}\n🏷️ *Hak Akses:* {role}\n🌐 *Tautan Login:* {login_url}\n\nBapak/Ibu dapat segera login ke sistem untuk meninjau usulan protokol penelitian dan melakukan kajian kelaikan 7 Standar Etik. Demi keamanan akun, Anda dapat memperbarui password setelah berhasil masuk pertama kali.\n\nTerima kasih atas dedikasi dan kontribusi Bapak/Ibu dalam menjaga keluhuran etika penelitian kesehatan.\n\n_Pesan otomatis ini dikirim oleh Sistem e-KEPK ETHOS RSUD Sidoarjo Barat._",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_templates');
    }
};
