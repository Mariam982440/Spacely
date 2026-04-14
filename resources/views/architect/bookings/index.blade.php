@extends('layouts.architect')
@section('title', 'Réservations')

@section('content')

    {{-- ── En-tête ── --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Réservations</h1>
            <p class="text-stone-400 text-sm mt-1">Gérez les demandes de consultation de vos clients</p>
        </div>
    </div>

    {{-- ── Filtre par statut ── --}}
    <form method="GET" action="{{ route('architect.bookings.index') }}" class="flex items-center gap-2 mb-6">
        @foreach([''=>'Toutes', 'pending'=>'En attente', 'confirmed'=>'Confirmées', 'cancelled'=>'Annulées'] as $value => $label)
            <button type="submit"
                    name="status"
                    value="{{ $value }}"
                    class="px-4 py-1.5 rounded-xl text-sm transition
                           {{ request('status') === $value
                              ? 'bg-green-700 text-white'
                              : 'bg-white border border-stone-200 text-stone-600 hover:bg-stone-50' }}">
                {{ $label }}
            </button>
        @endforeach
    </form>

    {{-- ── Liste des réservations ── --}}
    @if($bookings->count())

        <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-stone-100">
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">
                            Créneau
                        </th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">
                            Sujet
                        </th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr class="border-b border-stone-50 hover:bg-stone-50 transition">

                            {{-- Client --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-stone-100 flex items-center
                                                justify-center text-stone-600 text-sm font-medium shrink-0">
                                        {{ strtoupper(substr($booking->clientProfile->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-stone-800">
                                            {{ $booking->clientProfile->user->name }}
                                        </p>
                                        <p class="text-xs text-stone-400">
                                            {{ $booking->clientProfile->user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Créneau --}}
                            <td class="px-6 py-4">
                                <p class="text-sm text-stone-700">
                                    {{ $booking->timeSlot->start_at->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-stone-400">
                                    {{ $booking->timeSlot->start_at->format('H:i') }}
                                    →
                                    {{ $booking->timeSlot->end_at->format('H:i') }}
                                </p>
                            </td>

                            {{-- Sujet --}}
                            <td class="px-6 py-4">
                                <p class="text-sm text-stone-700 max-w-[180px] truncate">
                                    {{ $booking->subject }}
                                </p>
                                @if($booking->message)
                                    <p class="text-xs text-stone-400 truncate max-w-[180px]">
                                        {{ $booking->message }}
                                    </p>
                                @endif
                            </td>

                            {{-- Statut --}}
                            <td class="px-6 py-4">
                                @if($booking->status->value === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-medium
                                                 px-2.5 py-1 rounded-full">
                                        En attente
                                    </span>
                                @elseif($booking->status->value === 'confirmed')
                                    <span class="bg-green-100 text-green-700 text-xs font-medium
                                                 px-2.5 py-1 rounded-full">
                                        Confirmée
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-600 text-xs font-medium
                                                 px-2.5 py-1 rounded-full">
                                        Annulée
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">

                                    {{-- Confirmer --}}
                                    @if($booking->status->value === 'pending')
                                        <form method="POST"
                                              action="{{ route('architect.bookings.confirm', $booking) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="px-3 py-1.5 bg-green-100 text-green-700 text-xs
                                                           font-medium rounded-lg hover:bg-green-200 transition">
                                                Confirmer
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Annuler --}}
                                    @if($booking->status->value !== 'cancelled')
                                        <form method="POST"
                                              action="{{ route('architect.bookings.cancel', $booking) }}"
                                              onsubmit="return confirm('Annuler cette réservation ?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="px-3 py-1.5 bg-red-50 text-red-500 text-xs
                                                           font-medium rounded-lg hover:bg-red-100 transition">
                                                Annuler
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Créer un devis (si confirmée) --}}
                                    @if($booking->status->value === 'confirmed' && !$booking->quote)
                                        <a href="{{ route('architect.quotes.create', $booking) }}"
                                           class="px-3 py-1.5 bg-stone-100 text-stone-600 text-xs
                                                  font-medium rounded-lg hover:bg-stone-200 transition">
                                            Devis
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        {{ $bookings->links() }}

    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor"
                     stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucune réservation</h3>
            <p class="text-stone-400 text-sm">
                Les demandes de vos clients apparaîtront ici.
            </p>
        </div>
    @endif

@endsection