<?php

namespace App\Models;

use Sakuci\Database\Model;
use App\Traits\LogsActivity;

class Alat extends Model
{
    use LogsActivity;

    protected static ?string $table = 'alat';

    protected string $primaryKey = 'id_alat';

    protected array $fillable = [
        'nama_alat',
        'kode_alat'
    ];
}