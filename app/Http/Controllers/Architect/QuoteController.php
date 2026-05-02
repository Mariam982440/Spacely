<?php

namespace App\Http\Controllers\Architect;
 
use App\Http\Controllers\Controller;
use App\Http\Requests\Architect\StoreQuoteRequest;
use App\Models\Booking;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
 
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
    public function store(StoreQuoteRequest $request, Booking $booking)
    {
        $this->authorizeBooking($booking);
 
        $validated = $request->validated();
 
        // calculer les totaux
        $totalHt = collect($validated['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
 
        $tva      = $validated['tva'];
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
        foreach ($validated['items'] as $item) {
            $quote->items()->create([
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
            ]);
        }

        // générer le PDF
        $pdf  = Pdf::loadView('architect.quotes.pdf', compact('quote'));
        $path = 'quotes/' . $quote->reference . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());
 
        $quote->update(['pdf_path' => $path]);
 
        return redirect()
            ->route('architect.quotes.index')
            ->with('success', 'Devis envoyé au client avec succès.');
    }

    public function pdf(Quote $quote)
    {
        $this->authorizeQuote($quote);
 
        $pdf = Pdf::loadView('architect.quotes.pdf', compact('quote'));
 
        return $pdf->download('devis-' . $quote->reference . '.pdf');
    }
 
    // sécurité 
 
    private function authorizeBooking(Booking $booking): void
    {
        $profileId = auth()->user()->architectProfile->id;
 
        $belongs = $booking->timeSlot->availability->architect_id === $profileId;
 
        if (!$belongs) abort(403);
    }
 
    private function authorizeQuote(Quote $quote): void
    {
        $profileId = auth()->user()->architectProfile->id;
 
        $belongs = $quote->booking->timeSlot->availability->architect_id === $profileId;
 
        if (!$belongs) abort(403);
    }
}
