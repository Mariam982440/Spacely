@extends('layouts.architect')
@section('title', 'Devis')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Devis</h1>
            <p class="text-stone-400 text-sm mt-1">{{ $quotes->total() }} devis au total</p>
        </div>
    </div>

    @if($quotes->count())
        <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-stone-100">
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Référence</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Client</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Total TTC</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Statut</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotes as $quote)
                        <tr class="border-b border-stone-50 hover:bg-stone-50 transition">

                            {{-- Référence --}}
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm text-stone-700">{{ $quote->reference }}</span>
                            </td>

                            {{-- Client --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-stone-800">
                                    {{ $quote->booking->clientProfile->user->name }}
                                </p>
                            </td>

                            {{-- Total TTC --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-stone-800">
                                    {{ number_format($quote->total_ttc, 2) }} MAD
                                </p>
                                <p class="text-xs text-stone-400">
                                    HT: {{ number_format($quote->total_ht, 2) }} — TVA {{ $quote->tva }}%
                                </p>
                            </td>

                            {{-- Statut --}}
                            <td class="px-6 py-4">
                                @php
                                    $status = $quote->status->value;
                                    $badges = [
                                        'draft'    => 'bg-stone-100 text-stone-500',
                                        'sent'     => 'bg-blue-100 text-blue-700',
                                        'accepted' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-600',
                                    ];
                                    $labels = [
                                        'draft'    => 'Brouillon',
                                        'sent'     => 'Envoyé',
                                        'accepted' => 'Accepté',
                                        'rejected' => 'Refusé',
                                    ];
                                @endphp
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                             {{ $badges[$status] ?? 'bg-stone-100 text-stone-500' }}">
                                    {{ $quote->status->label() ?? $labels[$status] ?? $status }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4">
                                <p class="text-sm text-stone-500">
                                    {{ $quote->created_at->format('d/m/Y') }}
                                </p>
                            </td>

                            {{-- PDF --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('architect.quotes.pdf', $quote) }}"
                                   class="flex items-center gap-1.5 text-xs text-green-700
                                          hover:text-green-900 font-medium transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                         stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    Télécharger
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
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
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucun devis pour le moment</h3>
            <p class="text-stone-400 text-sm">
                Confirmez une réservation pour pouvoir créer un devis.
            </p>
        </div>
    @endif

@endsection
