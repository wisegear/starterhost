<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use HasFactory;
    protected $table = 'timeline';

    // Default format for dates in Laravel is Y-m-d, this transforms it always to d-m-Y

    protected $casts = [
        'date' => 'datetime:d-m-Y',
    ];

}
