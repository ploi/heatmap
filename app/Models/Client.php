<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Emoji\Emoji;

class Client extends Model
{
    use HasFactory;

    public $guarded = [];

    protected function countryFlag(): Attribute
    {
        return Attribute::make(
            get: fn() => Emoji::countryFlag($this->attributes['country'] ?? 'us'),
        );
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public static function booted()
    {
        static::deleting(function (self $client) {
            $client->clicks()->delete();
        });
    }
}
