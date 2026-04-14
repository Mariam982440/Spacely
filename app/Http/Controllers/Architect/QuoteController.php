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
    public function create(Booking $booking)
    {
        // vérifier que la réservation appartient à l'architecte
        $this->authorizeBooking($booking);
 
        // vérifier qu'il n'y a pas déjà un devis
        if ($booking->quote) {
            return redirect()
                ->route('architect.quotes.index')
                ->with('error', 'Un devis existe déjà pour cette réservation.');
        }
 
        return view('architect.quotes.create', compact('booking'));
    }
}
