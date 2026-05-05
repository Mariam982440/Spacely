@extends('layouts.client')
@section('title', 'Mon espace')

@section('content')

    <div class="mb-8">
        <h1 class="font-serif text-2xl font-semibold text-stone-900">
            Bonjour, {{ auth()->user()->name }}
        </h1>
        <p class="text-stone-400 text-sm mt-1">
            Trouvez l'architecte idéal et transformez votre espace.
        </p>
    </div>

    {{-- ── Stats ── --}}
    <div class="grid grid-cols-3 gap-4 mb-8">

        <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-stone-400 uppercase tracking-widest font-medium">Réservations</p>
                <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl font-semibold text-stone-900">
                {{ auth()->user()->clientProfile->bookings()->count() }}
            </p>
            <a href="{{ route('client.bookings.index') }}"
               class="text-xs text-green-600 hover:text-green-800 transition mt-1 inline-block">
                Voir tout →
            </a>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-stone-400 uppercase tracking-widest font-medium">Devis en attente</p>
                <div class="w-8 h-8 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl font-semibold text-stone-900">{{ $pendingQuotes }}</p>
            <a href="{{ route('client.quotes.index') }}"
               class="text-xs text-green-600 hover:text-green-800 transition mt-1 inline-block">
                Voir tout →
            </a>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-stone-400 uppercase tracking-widest font-medium">Messages non lus</p>
                <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl font-semibold text-stone-900">{{ $unreadMessages }}</p>
            <a href="{{ route('client.messages.index') }}"
               class="text-xs text-green-600 hover:text-green-800 transition mt-1 inline-block">
                Voir tout →
            </a>
        </div>

    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- ── Gauche ── --}}
        <div class="col-span-2 flex flex-col gap-6">

            {{-- Réservations récentes --}}
            <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-stone-100">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase">
                        Réservations récentes
                    </p>
                    <a href="{{ route('client.bookings.index') }}"
                       class="text-xs text-green-600 hover:text-green-800 font-medium transition">
                        Voir tout →
                    </a>
                </div>

                @if($bookings->count())
                    @foreach($bookings as $booking)
                        @php
                            $architect = $booking->timeSlot->availability->architectProfile;
                            $status    = $booking->status instanceof \BackedEnum
                                            ? $booking->status->value
                                            : $booking->status;
                        @endphp
                        <div class="flex items-center justify-between px-6 py-4
                                    border-b border-stone-50 last:border-none hover:bg-stone-50 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center
                                            justify-center text-green-800 text-sm font-medium shrink-0">
                                    {{ strtoupper(substr($architect->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-stone-800">
                                        {{ $architect->user->name }}
                                    </p>
                                    <p class="text-xs text-stone-400">
                                        {{ $booking->timeSlot->start_at->format('d/m/Y à H:i') }}
                                        — {{ $booking->subject }}
                                    </p>
                                </div>
                            </div>
                            @if($status === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                    En attente
                                </span>
                            @elseif($status === 'confirmed')
                                <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                    Confirmée
                                </span>
                            @else
                                <span class="bg-red-100 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                    Annulée
                                </span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="px-6 py-10 text-center">
                        <p class="text-stone-400 text-sm mb-4">Aucune réservation pour le moment.</p>
                        <a href="{{ route('client.architects.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-700 text-white
                                  text-sm rounded-xl hover:bg-green-800 transition">
                            Trouver un architecte
                        </a>
                    </div>
                @endif
            </div>

            {{-- Architectes mis en avant --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase">
                        Architectes à découvrir
                    </p>
                    <a href="{{ route('client.architects.index') }}"
                       class="text-xs text-green-600 hover:text-green-800 font-medium transition">
                        Voir tout →
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @foreach($featuredArchitects as $profile)
                        <a href="{{ route('client.architects.show', $profile) }}" class="group block">
                            <div class="aspect-video rounded-xl overflow-hidden bg-stone-100 mb-3">
                                @if($profile->projects->first()?->images->first())
                                    <img src="{{ Storage::url($profile->projects->first()->images->first()->image_path) }}"
                                         alt="{{ $profile->user->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-green-50">
                                        <span class="font-serif text-2xl text-green-300">
                                            {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <p class="text-sm font-medium text-stone-800 group-hover:text-green-700 transition">
                                {{ $profile->user->name }}
                            </p>
                            <p class="text-xs text-stone-400 mt-0.5">
                                {{ $profile->city }} — {{ $profile->experience_years }} ans
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ── Droite ── --}}
        <div class="flex flex-col gap-5">

            <div class="bg-green-700 rounded-2xl p-6 text-white">
                <p class="font-serif text-lg font-semibold mb-2">Trouvez votre architecte</p>
                <p class="text-green-200 text-sm mb-5 font-light leading-relaxed">
                    Recherchez par style, ville ou budget.
                </p>
                <a href="{{ route('client.architects.index') }}"
                   class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white
                          text-green-800 text-sm font-medium rounded-xl hover:bg-green-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    Rechercher
                </a>
            </div>

            <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase">Blog</p>
                    <a href="{{ route('client.blog.index') }}"
                       class="text-xs text-green-600 hover:text-green-800 font-medium transition">
                        Voir tout →
                    </a>
                </div>
                <div class="flex flex-col gap-4">
                    @foreach($recentPosts as $post)
                        <a href="{{ route('client.blog.show', $post) }}" class="group block">
                            <p class="text-sm font-medium text-stone-800 group-hover:text-green-700
                                      transition leading-snug line-clamp-2">
                                {{ $post->title }}
                            </p>
                            <p class="text-xs text-stone-400 mt-1">
                                {{ $post->architectProfile->user->name }}
                                · {{ $post->created_at->diffForHumans() }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

@endsection