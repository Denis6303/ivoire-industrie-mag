<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'color', 'icon', 'parent_id', 'order'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Liste sidebar : toutes les catégories avec nombre d’articles publiés.
     *
     * @return Collection<int, static>
     */
    public static function sidebarListWithPublishedCounts(): Collection
    {
        return static::query()
            ->withCount(['articles as published_articles_count' => fn (Builder $q) => $q->published()])
            ->orderBy('name')
            ->get();
    }
}
