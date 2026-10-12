<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['name', 'member_count', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function limits()
    {
        return $this->hasMany(DivisionItemLimit::class);
    }
}