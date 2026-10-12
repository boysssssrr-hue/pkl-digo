<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'name',
        'brand',
        'transmission',
        'capacity',
        'fuel_type',
        'status',
        'status_key',
        'current_driver_id',
        'current_driver_name',
        'current_passengers',
        'current_division',
        'current_destination',
        'color',
        'body_color',
        'trip_started_at',
        'trip_returned_at',
        'description',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'trip_started_at' => 'datetime',
            'trip_returned_at' => 'datetime',
        ];
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'current_driver_id');
    }
}
