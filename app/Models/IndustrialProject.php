<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndustrialProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'investment', 'jobs_created', 'location',
        'start_date', 'end_date', 'status', 'industry_sector_id', 'company_id',
    ];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(IndustrySector::class, 'industry_sector_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
