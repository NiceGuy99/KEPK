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
        Schema::create('pengaturan_sertifikat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penandatangan')->default('Dr. dr. Arif Rahman Nurdianto, M.Imun., S.H., M.H.');
            $table->string('jabatan_penandatangan')->default('Ketua KEPK RSUD Sidoarjo Barat');
            $table->string('pangkat_golongan')->nullable()->default('Pembina Tingkat I / IVb');
            $table->string('nip_penandatangan')->nullable()->default('198704162011011007');
            $table->string('kota_penerbitan')->default('Sidoarjo');
            $table->string('nama_lembaga')->default('Komite Etik Penelitian Kesehatan');
            $table->string('nama_lembaga_en')->default('Health Research Ethics Committee');
            $table->string('nama_instansi')->default('Rumah Sakit Umum Daerah Sidoarjo Barat');
            $table->string('masa_berlaku_teks')->default('*Masa berlaku 1 tahun');
            $table->string('masa_berlaku_teks_en')->default('1 year validity period');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert initial default configuration
        DB::table('pengaturan_sertifikat')->insert([
            'id' => 1,
            'nama_penandatangan' => 'Dr. dr. Arif Rahman Nurdianto, M.Imun., S.H., M.H.',
            'jabatan_penandatangan' => 'Ketua KEPK RSUD Sidoarjo Barat',
            'pangkat_golongan' => 'Pembina Tingkat I / IVb',
            'nip_penandatangan' => '198704162011011007',
            'kota_penerbitan' => 'Sidoarjo',
            'nama_lembaga' => 'Komite Etik Penelitian Kesehatan',
            'nama_lembaga_en' => 'Health Research Ethics Committee',
            'nama_instansi' => 'Rumah Sakit Umum Daerah Sidoarjo Barat',
            'masa_berlaku_teks' => '*Masa berlaku 1 tahun',
            'masa_berlaku_teks_en' => '1 year validity period',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sertifikat');
    }
};
