<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';
    public $timestamps = false;

    protected $fillable = [
        'proposal_id',
        'nomor_sertifikat',
        'tanggal_terbit',
        'nama_pejabat',
    ];

    public function proposal()
    {
        return $this->belongsTo(PengajuanProposal::class, 'proposal_id');
    }
}
