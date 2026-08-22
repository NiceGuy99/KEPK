<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRole extends Model
{
    protected $table = 'master_role';
    public $timestamps = false;

    protected $fillable = ['nama_role'];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function menus()
    {
        return $this->belongsToMany(MasterMenu::class, 'role_menu', 'role_id', 'menu_id')
            ->withTimestamps()
            ->orderBy('urutan', 'asc');
    }
}

