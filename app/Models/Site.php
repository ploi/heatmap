<?php

namespace App\Models;

use AshAllenDesign\FaviconFetcher\Facades\Favicon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Site extends Model
{
    use HasFactory;

    public $guarded = [];

    protected function favIcon(): Attribute
    {
        return Attribute::make(
            get: function () {
                try {
                    $favicon = Favicon::fetch($this->attributes['domain'])
                        ->cache(now()->addWeek());

                    return $favicon->getFaviconUrl();
                } catch (\Throwable $exception) {
                    return asset('images/empty.png');
                }
            }
        );
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public function getTrackerCodeAsHtml(): string
    {
        return '<script src="'.route('heatmap.js', $this->hash).'" defer></script>';
    }

    public static function booted()
    {
        static::creating(function (self $site) {
            if (! $site->hash) {
                $site->hash = Str::random(25);
            }
        });

        static::updating(function (self $site) {
            cache()->forget('site-'.$site->hash);
        });
    }
}
