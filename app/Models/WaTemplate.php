<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaTemplate extends Model
{
    protected $table = 'wa_templates';

    protected $fillable = [
        'slug',
        'nama_pemicu',
        'target_role',
        'deskripsi',
        'template_pesan',
        'variabel_tersedia',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
