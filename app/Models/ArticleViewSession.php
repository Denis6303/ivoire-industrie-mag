<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleViewSession extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'article_id', 'visitor_key', 'view_count', 'max_scroll_depth',
        'total_time_seconds', 'first_viewed_at', 'last_viewed_at',
        'referrer', 'source_type', 'utm_source', 'utm_medium', 'utm_campaign',
        'locale', 'device_type', 'browser', 'os', 'country_code', 'is_qualified',
    ];

    protected $casts = [
        'first_viewed_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'is_qualified' => 'boolean',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
