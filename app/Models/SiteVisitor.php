<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisitor extends Model
{
    protected $fillable = [
        'visitor_key',
        'first_seen_at',
        'last_seen_at',
        'page_views',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'page_views' => 'integer',
    ];
}
