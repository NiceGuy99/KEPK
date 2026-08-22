<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'password',
        'role_id',
        'is_active',
        'created_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(MasterRole::class, 'role_id');
    }

    public function profil()
    {
        return $this->hasOne(ProfilPengguna::class, 'user_id');
    }

    public function pengajuanProposal()
    {
        return $this->hasMany(PengajuanProposal::class, 'peneliti_id');
    }

    public function penugasanReview()
    {
        return $this->hasMany(PenugasanReview::class, 'penelaah_id');
    }

    public function isSekretaris(): bool
    {
        return (int)$this->role_id === 1 || strtolower($this->role?->nama_role ?? '') === 'sekretaris';
    }

    public function isKetua(): bool
    {
        return (int)$this->role_id === 2 || strtolower($this->role?->nama_role ?? '') === 'ketua';
    }

    public function isPenelaah(): bool
    {
        return (int)$this->role_id === 3 || strtolower($this->role?->nama_role ?? '') === 'penelaah';
    }

    public function isPeneliti(): bool
    {
        return (int)$this->role_id === 4 || strtolower($this->role?->nama_role ?? '') === 'peneliti';
    }

    public function getRoleNameAttribute(): string
    {
        return strtolower($this->role?->nama_role ?? '');
    }

    public function getNamaLengkapAttribute(): string
    {
        return $this->profil?->nama_lengkap ?? $this->email;
    }
}
