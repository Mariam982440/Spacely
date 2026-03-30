<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id',
        'time_slot_id',
        'subject',
        'message',
        'status',
    ];

    public function clientProfile()
    {
        return $this->belongsTo(ClientProfile::class, 'client_id');
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }
}
