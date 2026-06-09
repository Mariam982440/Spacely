@extends('layouts.client')
@section('title', 'Paiement confirmé')

@section('content')

    <div class="max-w-lg mx-auto text-center py-10">

        {{-- Icône succès --}}
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center
                    justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor"
                 stroke-width="2" viewBox="0 0 24 24">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
        </div>

        <h1 class="font-serif text-2xl font-semibold text-stone-900 mb-2">
            Paiement confirmé !
        </h1>
        <p class="text-stone-400 text-sm mb-8">
            Votre paiement a été traité avec succès.
        </p>

        {{-- Récapitulatif --}}
        <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm text-left mb-6">
            <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                Détails du paiement
            </p>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-400">Référence devis</span>
                    <span class="font-mono font-medium text-stone-700">{{ $quote->reference }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Architecte</span>
                    <span class="font-medium text-stone-700">
                        {{ $quote->booking->timeSlot->availability->architectProfile->user->name }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Montant payé</span>
                    <span class="font-semibold text-green-800">
                        {{ number_format($quote->total_ttc, 2) }} MAD
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Date</span>
                    <span class="text-stone-700">{{ $quote->payment->paid_at->format('d/m/Y à H:i') }}</span>
                </div>
                @if($quote->payment->stripe_payment_intent_id)
                    <div class="flex justify-between">
                        <span class="text-stone-400">ID transaction</span>
                        <span class="font-mono text-xs text-stone-500 truncate max-w-[180px]">
                            {{ $quote->payment->stripe_payment_intent_id }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('client.bookings.index') }}"
               class="px-5 py-2.5 bg-green-700 text-white text-sm font-medium
                      rounded-xl hover:bg-green-800 transition">
                Voir mes réservations
            </a>
            <a href="{{ route('client.dashboard') }}"
               class="px-5 py-2.5 border border-stone-200 text-stone-600 text-sm
                      rounded-xl hover:bg-stone-50 transition">
                Retour au dashboard
            </a>
        </div>

    </div>

@endsection