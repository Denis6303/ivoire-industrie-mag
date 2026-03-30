<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_url',
        'link_url',
        'position',
        'is_active',
        'start_date',
        'end_date',
        'click_count',
        'view_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'click_count' => 'integer',
        'view_count' => 'integer',
    ];
}
