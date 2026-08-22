<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSelfAssessmentPenelaah extends Model
{
    protected $table = 'hasil_self_assessment_penelaah';
    public $timestamps = false;

    protected $fillable = [
        'proposal_id',
        'penelaah_id',
        'indikator_key',
        'pilihan',
        'created_at',
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
