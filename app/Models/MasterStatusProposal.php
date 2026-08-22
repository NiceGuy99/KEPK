<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusProposal extends Model
{
    protected $table = 'master_status_proposal';
    public $timestamps = false;

    protected $fillable = ['kode_status', 'nama_status'];
}
