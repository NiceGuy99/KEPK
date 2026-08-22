<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanReview extends Model
{
    protected $table = 'penugasan_review';
    public $timestamps = false;

    protected $fillable = [
        'proposal_id',
        'penelaah_id',
        'tanggal_penugasan',
        'batas_waktu',
        'status_review',
        'catatan',
        'tanggal_review',
        'rekomendasi',
    ];

    public function proposal()
    {
        return $this->belongsTo(PengajuanProposal::class, 'proposal_id');
    }

    public function penelaah()
    {
        return $this->belongsTo(User::class, 'penelaah_id');
    }
}
