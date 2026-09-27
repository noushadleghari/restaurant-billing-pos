<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DiningTable extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity', 'status', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // The single open (unbilled) order currently sitting on this table, if any.
    public function activeOrder(): HasOne
    {
        return $this->hasOne(Order::class)->where('status', 'open')->latestOfMany();
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }
        return $query->where('name', 'like', "%{$term}%");
    }
}
