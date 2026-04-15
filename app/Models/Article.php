<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'title_en', 'signature', 'slug', 'slug_en', 'excerpt', 'excerpt_en', 'content', 'content_en',
        'cover_image', 'cover_alt', 'secondary_image', 'secondary_alt', 'tertiary_image', 'tertiary_alt',
        'status', 'type',
        'is_featured', 'is_breaking', 'is_premium', 'view_count', 'reading_time',
        'published_at', 'scheduled_at', 'meta_title', 'meta_title_en', 'meta_description', 'meta_description_en', 'author_id', 'category_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'is_premium' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(IndustrySector::class, 'article_industry_sector');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function related(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'related_articles', 'article_id', 'related_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
