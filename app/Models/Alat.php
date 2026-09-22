<?php

namespace App\Models;

use Sakuci\Database\Model;

class Alat extends Model
{
    protected static ?string $table = 'alat';

    protected string $primaryKey = 'id_alat';

    protected array $fillable = [
        'nama_alat',
        'kode_alat'
    ];
}
