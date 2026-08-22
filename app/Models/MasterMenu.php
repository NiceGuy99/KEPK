<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMenu extends Model
{
    protected $table = 'master_menu';

    protected $fillable = [
        'nama_menu',
        'route_name',
        'route_pattern',
        'url',
        'icon',
        'urutan',
        'kategori',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function roles()
    {
        return $this->belongsToMany(MasterRole::class, 'role_menu', 'menu_id', 'role_id')
            ->withTimestamps();
    }
}
