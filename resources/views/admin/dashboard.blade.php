@extends('layouts.admin')
@section('title', 'Tableau de bord')

@section('content')
    <div class="mb-8">
        <h1 class="font-serif text-2xl font-semibold text-stone-900">Tableau de bord</h1>
        <p class="text-stone-400 text-sm mt-1">Vue globale de la plateforme.</p>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-8">
        @foreach([
            'Utilisateurs' => $stats['users'],
            'Clients' => $stats['clients'],
            'Architectes' => $stats['architects'],
            'En attente' => $stats['pendingArchitects'],
            'Projets' => $stats['projects'],
            'Réservations' => $stats['bookings'],
            'Devis' => $stats['quotes'],
            'Devis acceptés' => $stats['acceptedQuotes'],
        ] as $label => $value)
            <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-stone-400 uppercase tracking-widest font-medium">{{ $label }}</p>
                <p class="font-serif text-3xl font-semibold text-stone-900 mt-2">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-stone-100">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase">Architectes récents</p>
                <a href="{{ route('admin.architects.index') }}" class="text-xs text-green-600 hover:text-green-800 font-medium">
                    Voir tout
                </a>
            </div>

            @forelse($latestArchitects as $profile)
                <a href="{{ route('admin.architects.show', $profile) }}" class="flex items-center justify-between px-6 py-4 border-b border-stone-50 last:border-none hover:bg-stone-50 transition">
                    <div>
                        <p class="text-sm font-medium text-stone-800">{{ $profile->user->name }}</p>
                        <p class="text-xs text-stone-400">{{ $profile->city ?: 'Ville non renseignée' }}</p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full {{ $profile->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $profile->is_verified ? 'Validé' : 'En attente' }}
                    </span>
                </a>
            @empty
                <p class="px-6 py-8 text-sm text-stone-400">Aucun architecte.</p>
            @endforelse
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase">Réservations récentes</p>
            </div>

            @forelse($latestBookings as $booking)
                <div class="px-6 py-4 border-b border-stone-50 last:border-none">
                    <p class="text-sm font-medium text-stone-800">{{ $booking->subject }}</p>
                    <p class="text-xs text-stone-400 mt-1">
                        {{ $booking->clientProfile?->user?->name }} avec
                        {{ $booking->timeSlot?->availability?->architectProfile?->user?->name }}
                    </p>
                </div>
            @empty
                <p class="px-6 py-8 text-sm text-stone-400">Aucune réservation.</p>
            @endforelse
        </div>
    </div>
@endsection
