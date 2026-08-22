<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPengguna extends Model
{
    protected $table = 'profil_pengguna';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik',
        'email',
        'alamat',
        'nomor_telepon',
        'status_id',
        'asal_kampus',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function statusPengguna()
    {
        return $this->belongsTo(MasterStatusPengguna::class, 'status_id');
    }
}
