<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportError extends Model
{
    protected $fillable = [
        'row',
        'message',
        'raw_data',
    ];

    protected $casts = [
        'row' => 'integer',
    ];
}
