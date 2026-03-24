<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'logo', 'description', 'website', 'email', 'phone', 'city', 'region',
        'address', 'is_featured', 'is_active', 'industry_sector_id',
    ];

    protected $casts = ['is_featured' => 'boolean', 'is_active' => 'boolean'];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(IndustrySector::class, 'industry_sector_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(IndustrialProject::class);
    }
}
