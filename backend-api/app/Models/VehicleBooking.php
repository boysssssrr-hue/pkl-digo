<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'borrower_name',
        'borrower_nip',
        'borrower_division',
        'driver_id',
        'driver_name',
        'vehicle_id',
        'destination',
        'pickup_location',
        'pickup_lat',
        'pickup_lng',
        'destination_lat',
        'destination_lng',
        'distance_km',
        'estimated_duration_minutes',
        'trip_mode',
        'purpose',
        'start_date',
        'end_date',
        'passengers_count',
        'passengers',
        'status',
        'booking_type',
        'is_routine',
        'routine_schedule',
        'recurring_frequency',
        'recurring_days',
        'google_maps_url',
        'notes',
    ];

    protected $appends = [
        'requester_name',
        'requester_division',
        'requester_nip',
        'google_maps_nav_url',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date'   => 'datetime',
            'passengers_count' => 'integer',
            'is_routine' => 'boolean',
            'pickup_lat' => 'float',
            'pickup_lng' => 'float',
            'destination_lat' => 'float',
            'destination_lng' => 'float',
            'distance_km' => 'float',
            'estimated_duration_minutes' => 'integer',
        ];
    }

    public function getGoogleMapsNavUrlAttribute(): ?string
    {
        if (!empty($this->attributes['google_maps_url'])) {
            return $this->attributes['google_maps_url'];
        }
        if ($this->destination_lat && $this->destination_lng) {
            $origin = ($this->pickup_lat && $this->pickup_lng)
                ? "origin={$this->pickup_lat},{$this->pickup_lng}&"
                : ($this->pickup_location ? "origin=" . urlencode($this->pickup_location) . "&" : "");
            return "https://www.google.com/maps/dir/?api=1&{$origin}destination={$this->destination_lat},{$this->destination_lng}";
        }
        if ($this->destination) {
            $rawStops = str_contains($this->destination, '➔')
                ? explode('➔', $this->destination)
                : (str_contains($this->destination, '->') ? explode('->', $this->destination) : [$this->destination]);
            $stops = array_values(array_filter(array_map('trim', $rawStops)));

            if (count($stops) > 1) {
                $origin = $this->pickup_location ?: $stops[0];
                $dest = end($stops);
                $waypoints = $this->pickup_location
                    ? array_slice($stops, 0, count($stops) - 1)
                    : array_slice($stops, 1, count($stops) - 2);
                $wpParam = !empty($waypoints) ? "&waypoints=" . urlencode(implode('|', $waypoints)) : "";
                return "https://www.google.com/maps/dir/?api=1&origin=" . urlencode($origin) . "&destination=" . urlencode($dest) . $wpParam;
            }

            $originParam = $this->pickup_location ? "origin=" . urlencode($this->pickup_location) . "&" : "";
            return "https://www.google.com/maps/dir/?api=1&{$originParam}destination=" . urlencode($stops[0]);
        }
        return null;
    }

    public function getRequesterNameAttribute(): string
    {
        if (!empty($this->borrower_name)) {
            return $this->borrower_name;
        }
        if ($this->user && $this->user->name !== 'admin' && $this->user->role !== 'admin') {
            return $this->user->name;
        }
        return $this->passengers ?: 'Pegawai Pemesan';
    }

    public function getRequesterDivisionAttribute(): string
    {
        if (!empty($this->borrower_division)) {
            return $this->borrower_division;
        }
        return $this->user?->division?->name ?? 'Operasional Kantor';
    }

    public function getRequesterNipAttribute(): string
    {
        if (!empty($this->borrower_nip)) {
            return $this->borrower_nip;
        }
        return $this->user?->nip ?? '-';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
