<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'architect_id',
        'title',
        'slug',
        'content',
        'cover_image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function architectProfile()
    {
        return $this->belongsTo(ArchitectProfile::class, 'architect_id');
    }

    public function favoritedBy()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}