<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Site extends Model
{
    use HasFactory;

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public static function booted()
    {
        static::creating(function (self $site) {
            if (! $site->hash) {
                $site->hash = Str::random(25);
            }
        });
    }
}
