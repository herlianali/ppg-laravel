<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'simpkb_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user is admin - dengan pengecekan null
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is verifikator - dengan pengecekan null
     */
    public function isVerifikator()
    {
        return $this->role === 'verifikator';
    }

    /**
     * Check if user is regular mahasiswa - dengan pengecekan null
     */
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa' || $this->role === null;
    }

    /**
     * Get role dengan default value
     */
    public function getRole()
    {
        return $this->role ?? 'mahasiswa';
    }
}
