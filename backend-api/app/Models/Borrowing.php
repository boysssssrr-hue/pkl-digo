<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Borrowing
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $division_id
 * @property string $status
 * @property string|null $request_note
 * @property \Illuminate\Support\Carbon|\Carbon\CarbonInterface|string|null $requested_at
 * @property \Illuminate\Support\Carbon|\Carbon\CarbonInterface|string|null $approved_at
 * @property \Illuminate\Support\Carbon|\Carbon\CarbonInterface|string|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Borrowing extends Model
{
    protected $fillable = ['user_id', 'division_id', 'status', 'request_note', 'requested_at', 'approved_at', 'completed_at'];

    protected function casts(): array
    {
        return ['requested_at' => 'datetime', 'approved_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function items()
    {
        return $this->hasMany(BorrowingItem::class);
    }
}