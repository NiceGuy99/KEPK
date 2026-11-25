<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSertifikat extends Model
{
    protected $table = 'pengaturan_sertifikat';

    protected $fillable = [
        'nama_penandatangan',
        'jabatan_penandatangan',
        'pangkat_golongan',
        'nip_penandatangan',
        'kota_penerbitan',
        'nama_lembaga',
        'nama_lembaga_en',
        'nama_instansi',
        'masa_berlaku_teks',
        'masa_berlaku_teks_en',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Ambil pengaturan sertifikat aktif atau nilai bawaan jika belum ada di database
     */
    public static function getActive(): self
    {
        $setting = static::where('is_active', true)->first();

        if (!$setting) {
            $setting = static::first();
        }

        if (!$setting) {
            $setting = new static([
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
            ]);
        }

        return $setting;
    }
}
