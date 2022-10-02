<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;

    const SM_BREAKPOINT = 640;

    const MD_BREAKPOINT = 768;

    const LG_BREAKPOINT = 1024;

    const XL_BREAKPOINT = 1280;

    const XXL_BREAKPOINT = 1536;

    protected $guarded = [];

    protected $casts = [
        'data' => 'json',
    ];

    public function scopeSmAndLower(Builder $query): Builder
    {
        return $query
            ->where('width', '<', self::SM_BREAKPOINT);
    }

    public function scopeSmAndMd(Builder $query): Builder
    {
        return $query
            ->where('width', '>', self::SM_BREAKPOINT)
            ->where('width', '<', self::MD_BREAKPOINT);
    }

    public function scopeMdAndLg(Builder $query): Builder
    {
        return $query
            ->where('width', '>', self::MD_BREAKPOINT)
            ->where('width', '<', self::LG_BREAKPOINT);
    }

    public function scopeLgAndXl(Builder $query): Builder
    {
        return $query
            ->where('width', '>', self::LG_BREAKPOINT)
            ->where('width', '<', self::XL_BREAKPOINT);
    }

    public function scopeXlAndXxl(Builder $query): Builder
    {
        return $query
            ->where('width', '>', self::XL_BREAKPOINT)
            ->where('width', '<', self::XXL_BREAKPOINT);
    }

    public function scopeXxlAndHigher(Builder $query): Builder
    {
        return $query
            ->where('width', '>', self::XXL_BREAKPOINT);
    }
}
