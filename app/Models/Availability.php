<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'architect_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function architectProfile()
    {
        return $this->belongsTo(ArchitectProfile::class, 'architect_id');
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
