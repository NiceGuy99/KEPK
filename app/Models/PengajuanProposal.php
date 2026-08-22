<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanProposal extends Model
{
    protected $table = 'pengajuan_proposal';
    public $timestamps = false;

    protected $fillable = [
        'kode_proposal',
        'peneliti_id',
        'judul_penelitian',
        'tempat_penelitian',
        'jenis_penelitian_id',
        'status',
        'tanggal_pengajuan',
        'file_proposal',
        'file_self_assessment',
        'file_kuesioner',
        'file_lampiran',
        'catatan_verifikasi',
        'status_id',
        'jenis_persetujuan_id',
        'created_at',
    ];

    public function peneliti()
    {
        return $this->belongsTo(User::class, 'peneliti_id');
    }

    public function jenisPenelitian()
    {
        return $this->belongsTo(MasterJenisPenelitian::class, 'jenis_penelitian_id');
    }

    public function statusProposal()
    {
        return $this->belongsTo(MasterStatusProposal::class, 'status_id');
    }

    public function jenisPersetujuan()
    {
        return $this->belongsTo(MasterJenisPersetujuan::class, 'jenis_persetujuan_id');
    }

    public function penugasanReview()
    {
        return $this->hasMany(PenugasanReview::class, 'proposal_id');
    }

    public function sertifikat()
    {
        return $this->hasOne(Sertifikat::class, 'proposal_id');
    }

    public function hasilSelfAssessment()
    {
        return $this->hasMany(HasilSelfAssessmentPenelaah::class, 'proposal_id');
    }
}
