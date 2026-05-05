@extends('layouts.client')
@section('title', 'Mes réservations')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Mes réservations</h1>
            <p class="text-stone-400 text-sm mt-1">{{ $bookings->total() }} réservation(s) au total</p>
        </div>
        <a href="{{ route('client.architects.index') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-green-700 text-white
                  text-sm font-medium rounded-xl hover:bg-green-800 transition">
            + Nouvelle réservation
        </a>
    </div>

    @if($bookings->count())
        <div class="flex flex-col gap-4 mb-6">
            @foreach($bookings as $booking)
                @php
                    $architect = $booking->timeSlot->availability->architectProfile;
                @endphp

                <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm
                            flex items-start gap-5">

                    {{-- Avatar architecte --}}
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center
                                justify-center text-green-800 font-medium shrink-0">
                        {{ strtoupper(substr($architect->user->name, 0, 1)) }}
                    </div>

                    {{-- Détails --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-medium text-stone-900">{{ $architect->user->name }}</p>
                                <p class="text-sm text-stone-400 mt-0.5">{{ $architect->city }}</p>
                            </div>
                            {{-- Statut --}}
                            @if($booking->status === \App\Enums\BookingStatus::Pending)
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-2.5 py-1 rounded-full shrink-0">
                                    En attente
                                </span>
                            @elseif($booking->status === \App\Enums\BookingStatus::Confirmed)
                                <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full shrink-0">
                                    Confirmée
                                </span>
                            @else
                                <span class="bg-red-100 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full shrink-0">
                                    Annulée
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-5 mt-3 text-sm text-stone-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                                </svg>
                                {{ $booking->timeSlot->start_at->format('d/m/Y') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                </svg>
                                {{ $booking->timeSlot->start_at->format('H:i') }}
                                → {{ $booking->timeSlot->end_at->format('H:i') }}
                            </span>
                            <span class="truncate max-w-[200px]">{{ $booking->subject }}</span>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3 mt-3 pt-3 border-t border-stone-100">
                            <a href="{{ route('client.messages.show', $architect->user) }}"
                               class="text-xs text-green-600 hover:text-green-800 font-medium transition">
                                Envoyer un message
                            </a>
                            @if($booking->quote)
                                <span class="text-stone-200">•</span>
                                <a href="{{ route('client.quotes.index') }}"
                                   class="text-xs text-stone-500 hover:text-stone-700 transition">
                                    Voir le devis
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $bookings->links() }}

    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucune réservation</h3>
            <p class="text-stone-400 text-sm mb-6">
                Trouvez un architecte et réservez votre première consultation.
            </p>
            <a href="{{ route('client.architects.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700
                      text-white text-sm rounded-xl hover:bg-green-800 transition">
                Trouver un architecte
            </a>
        </div>
    @endif

@endsection