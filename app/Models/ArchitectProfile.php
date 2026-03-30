<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArchitectProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'city',
        'experience_years',
        'is_verified',
        'profile_picture',
    ];
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'architect_id');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class, 'architect_id');
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'architect_id');
    }

}
