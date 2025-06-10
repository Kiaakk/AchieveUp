<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPrestasiAdminNote extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_prestasi_admin_notes';

    protected $fillable = [
        'prestasi_id',
        'dosen_id',
        'is_accepted',
    ];

    /**
     * Relasi ke model Prestasi
     */
    public function prestasi()
    {
        return $this->belongsTo(Prestasi::class, 'prestasi_id');
    }

    /**
     * Relasi ke model Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
