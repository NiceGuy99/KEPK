<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisPenelitian extends Model
{
    protected $table = 'master_jenis_penelitian';
    public $timestamps = false;

    protected $fillable = ['nama_jenis'];
}
