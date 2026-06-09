@extends('layouts.client')
@section('title', 'Mes devis')

@section('content')

    <div class="mb-8">
        <h1 class="font-serif text-2xl font-semibold text-stone-900">Mes devis</h1>
        <p class="text-stone-400 text-sm mt-1">{{ $quotes->total() }} devis au total</p>
    </div>

    @if($quotes->count())
        <div class="flex flex-col gap-5 mb-6">
            @foreach($quotes as $quote)
                @php
                    $architect  = $quote->booking->timeSlot->availability->architectProfile;
                    // ->value convertit l'Enum en string pour l'utiliser comme clé de tableau
                    $status     = $quote->status instanceof \BackedEnum ? $quote->status->value : $quote->status;
                    $badges     = [
                        'draft'    => 'bg-stone-100 text-stone-500',
                        'sent'     => 'bg-blue-100 text-blue-700',
                        'accepted' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-600',
                    ];
                    $labels     = [
                        'draft'    => 'Brouillon',
                        'sent'     => 'En attente de réponse',
                        'accepted' => 'Accepté',
                        'rejected' => 'Refusé',
                    ];
                    $isPaid = $quote->payment && $quote->payment->status === 'completed';
                @endphp

                <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">

                    {{-- En-tête --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-stone-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center
                                        justify-center text-green-800 text-sm font-medium">
                                {{ strtoupper(substr($architect->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-stone-800 text-sm">{{ $architect->user->name }}</p>
                                <p class="text-xs text-stone-400 font-mono">{{ $quote->reference }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                         {{ $badges[$status] ?? 'bg-stone-100 text-stone-500' }}">
                                {{ $labels[$status] ?? $status }}
                            </span>
                            <span class="text-xs text-stone-400">
                                {{ $quote->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Lignes du devis --}}
                    <div class="px-6 py-4">
                        <table class="w-full mb-4">
                            <thead>
                                <tr class="border-b border-stone-100">
                                    <th class="text-left text-xs text-stone-400 font-medium pb-2 uppercase tracking-wide">Description</th>
                                    <th class="text-right text-xs text-stone-400 font-medium pb-2 uppercase tracking-wide">Qté</th>
                                    <th class="text-right text-xs text-stone-400 font-medium pb-2 uppercase tracking-wide">Prix unit.</th>
                                    <th class="text-right text-xs text-stone-400 font-medium pb-2 uppercase tracking-wide">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quote->items as $item)
                                    <tr class="border-b border-stone-50">
                                        <td class="py-2.5 text-sm text-stone-700">{{ $item->description }}</td>
                                        <td class="py-2.5 text-sm text-stone-500 text-right">{{ $item->quantity }}</td>
                                        <td class="py-2.5 text-sm text-stone-500 text-right">
                                            {{ number_format($item->unit_price, 2) }} MAD
                                        </td>
                                        <td class="py-2.5 text-sm font-medium text-stone-700 text-right">
                                            {{ number_format($item->quantity * $item->unit_price, 2) }} MAD
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Totaux --}}
                        <div class="flex justify-end">
                            <div class="w-56">
                                <div class="flex justify-between text-sm text-stone-500 mb-1">
                                    <span>Total HT</span>
                                    <span>{{ number_format($quote->total_ht, 2) }} MAD</span>
                                </div>
                                <div class="flex justify-between text-sm text-stone-500 mb-2">
                                    <span>TVA ({{ $quote->tva }}%)</span>
                                    <span>{{ number_format($quote->total_ht * ($quote->tva / 100), 2) }} MAD</span>
                                </div>
                                <div class="flex justify-between font-semibold text-stone-900
                                            border-t border-stone-200 pt-2">
                                    <span>Total TTC</span>
                                    <span class="text-green-800">{{ number_format($quote->total_ttc, 2) }} MAD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions accepter/refuser --}}
                    @if($status === 'sent')
                        <div class="flex items-center gap-3 px-6 py-4 bg-stone-50 border-t border-stone-100">
                            <p class="text-sm text-stone-500 flex-1">Souhaitez-vous accepter ce devis ?</p>
                            <form method="POST" action="{{ route('client.quotes.accept', $quote) }}">
                                @csrf @method('PUT')
                                <button type="submit"
                                        class="px-4 py-2 bg-green-700 text-white text-sm
                                               font-medium rounded-xl hover:bg-green-800 transition">
                                    Accepter
                                </button>
                            </form>
                            <form method="POST" action="{{ route('client.quotes.reject', $quote) }}"
                                  onsubmit="return confirm('Refuser ce devis ?')">
                                @csrf @method('PUT')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-50 text-red-600 border border-red-200
                                               text-sm font-medium rounded-xl hover:bg-red-100 transition">
                                    Refuser
                                </button>
                            </form>
                        </div>
                    @elseif($status === 'accepted')
                        <div class="flex items-center gap-3 px-6 py-4 bg-stone-50 border-t border-stone-100">
                            @if($isPaid)
                                <p class="text-sm text-green-700 flex-1 font-medium">Paiement effectue.</p>
                                <a href="{{ route('client.payments.success', $quote) }}"
                                   class="px-4 py-2 border border-stone-200 text-stone-600 text-sm font-medium rounded-xl hover:bg-white transition">
                                    Voir le recu
                                </a>
                            @else
                                <p class="text-sm text-stone-500 flex-1">Devis accepte. Vous pouvez maintenant proceder au paiement.</p>
                                <a href="{{ route('client.payments.show', $quote) }}"
                                   class="px-4 py-2 bg-green-700 text-white text-sm font-medium rounded-xl hover:bg-green-800 transition">
                                    Payer
                                </a>
                            @endif
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{ $quotes->links() }}

    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor"
                     stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucun devis reçu</h3>
            <p class="text-stone-400 text-sm">
                Les devis apparaîtront ici après confirmation de votre réservation.
            </p>
        </div>
    @endif

@endsection
