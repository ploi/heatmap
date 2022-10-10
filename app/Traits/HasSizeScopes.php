<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSizeScopes
{
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
