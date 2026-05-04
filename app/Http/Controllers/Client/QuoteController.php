<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Quote;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::whereHas('booking', function ($q) {
                $q->where('client_id', auth()->user()->clientProfile->id);
            })
            ->with([
                'booking.timeSlot.availability.architectProfile.user',
                'items',
            ])
            ->latest()
            ->paginate(10);

        return view('client.quotes.index', compact('quotes'));
    }

    public function accept(Quote $quote)
    {
        $this->authorizeQuote($quote);

        if ($quote->status !== 'sent') {
            return back()->with('error', 'Ce devis ne peut pas être accepté.');
        }

        $quote->update(['status' => 'accepted']);

        return back()->with('success', 'Devis accepté. L\'architecte a été notifié.');
    }

    public function reject(Quote $quote)
    {
        $this->authorizeQuote($quote);

        if ($quote->status !== 'sent') {
            return back()->with('error', 'Ce devis ne peut pas être refusé.');
        }

        $quote->update(['status' => 'rejected']);

        return back()->with('success', 'Devis refusé.');
    }

    // sécurité 

    private function authorizeQuote(Quote $quote): void
    {
        $clientProfileId = auth()->user()->clientProfile->id;

        if ($quote->booking->client_id !== $clientProfileId) {
            abort(403);
        }
    }
}
