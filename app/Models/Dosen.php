<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Karena ada login
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'dosen';

    protected $fillable = [
        'nidn',
        'username',
        'nama',
        'email',
        'password',
        'foto',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Caster untuk kolom 'role' jika perlu penanganan enum sebagai string.
     */
    protected $casts = [
        'role' => 'string',
    ];

    public function getRoleAttribute($value)
    {
        return $value === 'admin' ? 'admin' : 'dosen pembimbing';
    }
}
