<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['barcode', 'name', 'category', 'unit', 'description', 'stock', 'status', 'image'];

    protected function casts(): array
    {
        return ['stock' => 'integer'];
    }

    public function limits()
    {
        return $this->hasMany(DivisionItemLimit::class);
    }

    public function borrowingItems()
    {
        return $this->hasMany(BorrowingItem::class);
    }
}
