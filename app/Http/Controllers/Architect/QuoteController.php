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
    public function store(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);
 
        $request->validate([
            'items'             => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'tva'               => 'required|numeric|min:0|max:100',
        ]);
 
        // calculer les totaux
        $totalHt = collect($request->items)->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
 
        $tva      = $request->tva;
        $totalTtc = $totalHt * (1 + $tva / 100);
 
        // créer le devis
        $quote = Quote::create([
            'booking_id' => $booking->id,
            'reference'  => 'QUO-' . strtoupper(Str::random(8)),
            'total_ht'   => $totalHt,
            'tva'        => $tva,
            'total_ttc'  => $totalTtc,
            'status'     => 'sent', // envoyé directement au client
        ]);
 
        // créer les lignes du devis
        foreach ($request->items as $item) {
            $quote->items()->create([
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
            ]);
        }
    }
}
