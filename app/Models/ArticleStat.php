<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleStat extends Model
{
    protected $primaryKey = 'article_id';

    public $incrementing = false;

    protected $fillable = [
        'article_id', 'views_total', 'views_unique', 'views_returning',
        'views_organic', 'views_social', 'views_direct', 'views_referral',
        'views_campaign', 'views_homepage', 'views_category', 'views_internal',
        'views_newsletter', 'views_fr', 'views_en', 'views_mobile', 'views_tablet',
        'views_desktop', 'og_crawler_hits', 'bounces', 'shares_total',
        'shares_facebook', 'shares_linkedin', 'shares_twitter', 'shares_whatsapp',
        'shares_copy', 'scroll_25', 'scroll_50', 'scroll_75', 'scroll_100',
        'qualified_reads', 'time_on_page_total_seconds', 'time_on_page_samples',
        'clicks_internal_links', 'clicks_external_links', 'clicks_related',
        'clicks_cover_image', 'clicks_secondary_image', 'clicks_newsletter',
        'clicks_jobs', 'clicks_companies', 'newsletter_signups',
        'peak_views_count', 'peak_views_date', 'first_view_at', 'last_view_at',
    ];

    protected $casts = [
        'peak_views_date' => 'date',
        'first_view_at' => 'datetime',
        'last_view_at' => 'datetime',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
