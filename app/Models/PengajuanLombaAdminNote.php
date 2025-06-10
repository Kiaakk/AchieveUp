<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanLombaAdminNote extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_lomba_admin_notes';

    protected $fillable = [
        'pengajuan_lomba_mahasiswa_id',
        'dosen_id',
        'is_accepted',
    ];

    /**
     * Relasi ke model PengajuanLombaMahasiswa
     */
    public function pengajuanLombaMahasiswa()
    {
        return $this->belongsTo(PengajuanLombaMahasiswa::class, 'pengajuan_lomba_mahasiswa_id');
    }

    /**
     * Relasi ke model Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
