<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'architect_id',
        'title',
        'description',
    ];

    public function architect(): BelongsTo
    {
        return $this->belongsTo(ArchitectProfile::class, 'architect_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function favoritedBy(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}
