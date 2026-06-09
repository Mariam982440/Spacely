@extends('layouts.client')
@section('title', 'Payer le devis ' . $quote->reference)

@section('content')

    <div class="max-w-xl mx-auto">

        <div class="mb-8">
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Paiement</h1>
            <p class="text-stone-400 text-sm mt-1">Devis {{ $quote->reference }}</p>
        </div>

        {{-- ── Résumé devis ── --}}
        <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm mb-5">
            <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                Récapitulatif
            </p>
            <div class="flex flex-col gap-2 text-sm mb-4">
                <div class="flex justify-between text-stone-500">
                    <span>Total HT</span>
                    <span>{{ number_format($quote->total_ht, 2) }} MAD</span>
                </div>
                <div class="flex justify-between text-stone-500">
                    <span>TVA ({{ $quote->tva }}%)</span>
                    <span>{{ number_format($quote->total_ht * ($quote->tva / 100), 2) }} MAD</span>
                </div>
                <hr class="border-stone-100 my-1">
                <div class="flex justify-between font-semibold text-stone-900">
                    <span>Total TTC</span>
                    <span class="text-green-800 text-lg">{{ number_format($quote->total_ttc, 2) }} MAD</span>
                </div>
            </div>
        </div>

        {{-- ── Formulaire Stripe ── --}}
        <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm mb-5">
            <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">
                Informations de paiement
            </p>

            <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-5
                        flex items-start gap-3 text-sm text-blue-700">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                </svg>
                <span>
                    Mode test — utilisez la carte <strong>4242 4242 4242 4242</strong>,
                    n'importe quelle date future et n'importe quel CVV.
                </span>
            </div>

            {{-- Élément Stripe Card --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-stone-700 mb-2">Carte bancaire</label>
                <div id="card-element"
                     class="px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl
                            focus-within:ring-2 focus-within:ring-green-200 focus-within:border-green-400">
                </div>
                <p id="card-errors" class="text-red-500 text-xs mt-2 hidden"></p>
            </div>

            <button id="pay-btn"
                    onclick="handlePayment()"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3
                           bg-green-700 text-white text-sm font-medium rounded-xl
                           hover:bg-green-800 transition disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="1" y="4" width="22" height="16" rx="2"/>
                    <path d="M1 10h22"/>
                </svg>
                Payer {{ number_format($quote->total_ttc, 2) }} MAD
            </button>
        </div>

        {{-- Sécurité --}}
        <p class="text-center text-xs text-stone-400 flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2"/>
                <path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
            Paiement sécurisé par Stripe
        </p>

    </div>

    {{-- Formulaire caché pour soumettre le PaymentIntent ID --}}
    <form id="payment-form" method="POST"
          action="{{ route('client.payments.store', $quote) }}" class="hidden">
        @csrf
        <input type="hidden" name="payment_intent_id" id="payment-intent-id">
    </form>

@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe  = Stripe('{{ $stripeKey }}');
    const elements = stripe.elements();

    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '14px',
                color: '#1a1d14',
                fontFamily: 'DM Sans, sans-serif',
                '::placeholder': { color: '#9a9c8e' },
            },
            invalid: { color: '#b91c1c' },
        },
        hidePostalCode: true,
    });

    cardElement.mount('#card-element');

    // Afficher les erreurs en temps réel
    cardElement.on('change', function(event) {
        const errors = document.getElementById('card-errors');
        if (event.error) {
            errors.textContent = event.error.message;
            errors.classList.remove('hidden');
        } else {
            errors.classList.add('hidden');
        }
    });

    async function handlePayment() {
        const btn = document.getElementById('pay-btn');
        btn.disabled = true;
        btn.textContent = 'Traitement en cours...';

        const { paymentIntent, error } = await stripe.confirmCardPayment(
            '{{ $clientSecret }}',
            {
                payment_method: { card: cardElement },
            }
        );

        if (error) {
            const errors = document.getElementById('card-errors');
            errors.textContent = error.message;
            errors.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>
                </svg>
                Payer {{ number_format($quote->total_ttc, 2) }} MAD`;
            return;
        }

        // Paiement réussi — soumettre le formulaire avec l'ID
        document.getElementById('payment-intent-id').value = paymentIntent.id;
        document.getElementById('payment-form').submit();
    }
</script>
@endpush