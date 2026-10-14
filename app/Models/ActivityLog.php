<?php

namespace App\Models;

use Sakuci\Database\Model;

class ActivityLog extends Model
{
    protected static ?string $table = 'activity_logs';

    protected array $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
    ];
}