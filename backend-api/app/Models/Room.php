<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'capacity',
        'floor',
        'facilities',
        'status',
        'description',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
        ];
    }
}
