<?php

namespace App\Models;

use Sakuci\Database\Model; 

class Peminjaman extends Model
{
    protected static ?string $table= 'peminjaman';
    protected string $primaryKey = 'id_peminjaman';
    
    protected array $fillable = [
        'id_user',
        'id_alat',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'denda'
    ];
}