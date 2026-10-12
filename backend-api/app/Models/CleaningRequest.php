<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CleaningRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cleaning_tool_id',
        'service_type',
        'location',
        'requested_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cleaningTool()
    {
        return $this->belongsTo(CleaningTool::class);
    }
}
