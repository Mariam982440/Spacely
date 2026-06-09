<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function show(Quote $quote)
    {
        $this->authorizeQuote($quote);
        $quote->load('payment');
        $status = $quote->status instanceof \BackedEnum ? $quote->status->value : $quote->status;

        // Seulement les devis acceptés peuvent être payés
        if ($status !== 'accepted') {
            return redirect()
                ->route('client.quotes.index')
                ->with('error', 'Ce devis doit être accepté avant le paiement.');
        }

        // Déjà payé
        if ($quote->payment && $quote->payment->status === 'completed') {
            return redirect()
                ->route('client.quotes.index')
                ->with('error', 'Ce devis a déjà été payé.');
        }

        if (! config('services.stripe.key') || ! config('services.stripe.secret')) {
            return redirect()
                ->route('client.quotes.index')
                ->with('error', 'La configuration Stripe est manquante.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // Créer un PaymentIntent chez Stripe
        // Le montant est en centimes (MAD * 100)
        $intent = PaymentIntent::create([
            'amount'   => (int) ($quote->total_ttc * 100),
            'currency' => 'mad',
            'metadata' => [
                'quote_reference' => $quote->reference,
                'client_id'       => auth()->id(),
            ],
        ]);

        return view('client.payments.show', [
            'quote'         => $quote,
            'clientSecret'  => $intent->client_secret,
            'stripeKey'     => config('services.stripe.key'),
        ]);
    }

    public function store(Request $request, Quote $quote)
    {
        $this->authorizeQuote($quote);
        $quote->load('payment');

        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        if (! config('services.stripe.secret')) {
            return redirect()
                ->route('client.quotes.index')
                ->with('error', 'La configuration Stripe est manquante.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // Vérifier le statut du PaymentIntent chez Stripe
        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return back()->with('error', 'Le paiement n\'a pas abouti. Veuillez réessayer.');
        }

        // Enregistrer le paiement en base
        Payment::updateOrCreate([
            'quote_id' => $quote->id,
        ], [
            'quote_id'                  => $quote->id,
            'amount'                    => $quote->total_ttc,
            'transaction_id'            => $intent->id,
            'stripe_payment_intent_id'  => $intent->id,
            'stripe_charge_id'          => $intent->latest_charge,
            'currency'                  => 'mad',
            'payment_method'            => 'card',
            'status'                    => 'completed',
            'paid_at'                   => now(),
        ]);

        return redirect()
            ->route('client.payments.success', $quote)
            ->with('success', 'Paiement effectué avec succès !');
    }

    public function success(Quote $quote)
    {
        $this->authorizeQuote($quote);

        $quote->load('booking.timeSlot.availability.architectProfile.user', 'payment');

        return view('client.payments.success', compact('quote'));
    }

    private function authorizeQuote(Quote $quote): void
    {
        if ($quote->booking->client_id !== auth()->user()->clientProfile->id) {
            abort(403);
        }
    }
}
