<?php

namespace App\Models;

use App\Traits\HasSizeScopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    use HasFactory,
        HasSizeScopes;

    const SM_BREAKPOINT = 640;

    const MD_BREAKPOINT = 768;

    const LG_BREAKPOINT = 1024;

    const XL_BREAKPOINT = 1280;

    const XXL_BREAKPOINT = 1536;

    protected $guarded = [];

    protected $casts = [
        'data' => 'json',
        'track_clicks' => 'boolean',
        'track_movements' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
