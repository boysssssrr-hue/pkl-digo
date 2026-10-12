<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingItem extends Model
{
    protected $fillable = ['borrowing_id', 'product_id', 'quantity'];

    protected function casts(): array
    {
        return ['quantity' => 'integer'];
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}