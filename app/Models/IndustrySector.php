<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class IndustrySector extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'color', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_industry_sector');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(IndustrialProject::class);
    }
}
