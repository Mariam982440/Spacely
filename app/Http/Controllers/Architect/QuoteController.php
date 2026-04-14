<?php

namespace App\Http\Controllers\Architect;
 
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Quote;
use Illuminate\Http\Request;
 
class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::whereHas('booking.timeSlot.availability', function ($q) {
                $q->where('architect_id', auth()->user()->architectProfile->id);
            })
            ->with('booking.clientProfile.user', 'items')
            ->latest()
            ->paginate(10);
 
        return view('architect.quotes.index', compact('quotes'));
    }
    
}
