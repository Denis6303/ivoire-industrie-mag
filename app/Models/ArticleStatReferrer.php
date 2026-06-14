<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleStatReferrer extends Model
{
    public $timestamps = false;

    protected $fillable = ['article_id', 'referrer_host', 'hit_count'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
