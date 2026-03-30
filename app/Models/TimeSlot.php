<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'availability_id',
        'start_at',
        'end_at',
        'is_booked',
    ];

    protected function casts(): array
    {
        return [
            'start_at'  => 'datetime',
            'end_at'    => 'datetime',
            'is_booked' => 'boolean',
        ];
    }

    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function isFree(): bool
    {
        return $this->is_booked === false;
    }
}