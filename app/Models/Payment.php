<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'amount',
        'transaction_id', 
        'status', // pending, completed, failed
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    
}
