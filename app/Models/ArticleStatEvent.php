<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleStatEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'article_id', 'visitor_key', 'event_type', 'payload',
        'referrer', 'source_type', 'utm_source', 'utm_medium', 'utm_campaign',
        'locale', 'device_type', 'browser', 'os', 'country_code', 'created_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
