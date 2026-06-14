<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleStatDaily extends Model
{
    public $timestamps = false;

    protected $table = 'article_stat_daily';

    protected $fillable = [
        'article_id', 'date', 'views', 'unique_visitors', 'shares',
        'qualified_reads', 'time_on_page_total_seconds', 'time_on_page_samples',
        'views_fr', 'views_en',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
