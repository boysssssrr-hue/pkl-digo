<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionItemLimit extends Model
{
    protected $fillable = ['division_id', 'product_id', 'quantity_limit', 'period'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}