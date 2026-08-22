<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusPengguna extends Model
{
    protected $table = 'master_status_pengguna';
    public $timestamps = false;

    protected $fillable = ['nama_status'];
}
