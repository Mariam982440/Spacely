@extends('layouts.architect')
@section('title', 'Mon Profil')
 
@section('content')
 
    {{-- ── Alerte profil non vérifié ── --}}
    @if(!$profile->is_verified)
        <div class="flex items-center gap-3 bg-yellow-50 border border-yellow-200
                    text-yellow-800 text-sm rounded-xl px-4 py-3 mb-6">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
            </svg>
            Votre profil est en attente de validation par l'administrateur.
        </div>
    @endif
 
    {{-- ── Hero banner ── --}}
    <div class="h-52 rounded-t-2xl bg-gradient-to-r from-green-900 via-green-700 to-green-500"></div>
 
    {{-- ── Header card (avatar + nom + actions) ── --}}
    <div class="bg-white border border-stone-200 rounded-b-2xl px-8 pb-6 mb-6 shadow-sm">
 
        {{-- Avatar + bouton modifier --}}
        <div class="flex items-end justify-between -mt-12 mb-4">
 
            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md
                        bg-green-100 flex items-center justify-center overflow-hidden">
                @if($profile->profile_picture)
                    <img src="{{ Storage::url($profile->profile_picture) }}"
                         alt="Photo de profil"
                         class="w-full h-full object-cover">
                @else
                    <span class="font-serif text-3xl text-green-800">
                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                    </span>
                @endif
            </div>
 
            {{-- Bouton modifier --}}
            <a href="{{ route('architect.profile.edit') }}"
               class="flex items-center gap-2 px-4 py-2 bg-white border border-stone-200
                      text-stone-700 text-sm rounded-xl hover:bg-stone-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Modifier le profil
            </a>
        </div>
 
        {{-- Nom + badge vérifié --}}
        <div class="flex items-center gap-3 mb-2">
            <h1 class="font-serif text-2xl font-semibold text-stone-900">
                {{ $profile->user->name }}
            </h1>
            @if($profile->is_verified)
                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full">
                    ✓ Vérifié
                </span>
            @endif
        </div>
 
        {{-- Ville + expérience + projets --}}
        <div class="flex items-center gap-5 text-sm text-stone-500 mb-4">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $profile->city ?: 'Ville non renseignée' }}
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
                {{ $profile->experience_years }} ans d'expérience
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <path d="M21 15l-5-5L5 21"/>
                </svg>
                {{ $profile->projects->count() }} projets
            </span>
        </div>
 
        {{-- Tags des projets --}}
        @if($profile->projects->count())
            <div class="flex flex-wrap gap-2">
                @foreach($profile->projects->take(3)->flatMap->tags->unique('id') as $tag)
                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif
 
    </div>
 
    {{-- ── Grille principale ── --}}
    <div class="grid grid-cols-3 gap-6">
 
        {{-- ── Colonne gauche (2/3) : Bio + Projets ── --}}
        <div class="col-span-2 flex flex-col gap-6">
 
            {{-- Bio --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                    La philosophie
                </p>
 
                @if($profile->bio)
                    {{-- Citation --}}
                    <blockquote class="border-l-4 border-green-600 bg-green-50 px-5 py-4
                                       text-green-900 font-serif text-base italic rounded-r-xl mb-4">
                        "{{ Str::limit($profile->bio, 120) }}"
                    </blockquote>
                    <p class="text-stone-500 text-sm leading-relaxed font-light">
                        {{ $profile->bio }}
                    </p>
                @else
                    <p class="text-stone-400 text-sm italic">
                        Aucune biographie renseignée.
                        <a href="{{ route('architect.profile.edit') }}" class="text-green-600 underline">
                            Ajouter une description
                        </a>
                    </p>
                @endif
            </div>
 
            {{-- Projets récents --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase">
                        Réalisations récentes
                    </p>
                    <a href="{{ route('architect.projects.index') }}"
                       class="text-sm text-green-600 hover:text-green-800 font-medium transition">
                        Voir tout →
                    </a>
                </div>
 
                @if($profile->projects->count())
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($profile->projects->take(4) as $project)
                            <a href="{{ route('architect.projects.show', $project) }}"
                               class="relative block aspect-video rounded-xl overflow-hidden bg-stone-100 group">
                                @if($project->images->first())
                                    <img src="{{ Storage::url($project->images->first()->image_path) }}"
                                         alt="{{ $project->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-green-50">
                                        <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <path d="M21 15l-5-5L5 21"/>
                                        </svg>
                                    </div>
                                @endif
                                {{-- Label projet --}}
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50
                                            to-transparent px-3 py-2">
                                    <p class="text-white text-xs font-medium truncate">
                                        {{ $project->title }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <p class="text-stone-400 text-sm mb-4">Aucun projet publié pour le moment.</p>
                        <a href="{{ route('architect.projects.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-700 text-white
                                  text-sm rounded-xl hover:bg-green-800 transition">
                            + Ajouter un projet
                        </a>
                    </div>
                @endif
            </div>
 
        </div>
 
        {{-- ── Colonne droite (1/3) : Stats + Actions ── --}}
        <div class="flex flex-col gap-4">
 
            {{-- Stats --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                    Statistiques
                </p>
 
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="bg-stone-50 rounded-xl p-4 text-center">
                        <p class="font-serif text-2xl font-semibold text-green-800">
                            {{ $profile->projects->count() }}
                        </p>
                        <p class="text-xs text-stone-400 mt-1">Projets</p>
                    </div>
                    <div class="bg-stone-50 rounded-xl p-4 text-center">
                        <p class="font-serif text-2xl font-semibold text-green-800">
                            {{ $profile->experience_years }}
                        </p>
                        <p class="text-xs text-stone-400 mt-1">Ans exp.</p>
                    </div>
                    <div class="bg-stone-50 rounded-xl p-4 text-center">
                        <p class="font-serif text-2xl font-semibold text-green-800">
                            {{ $profile->availabilities->count() }}
                        </p>
                        <p class="text-xs text-stone-400 mt-1">Jours dispo.</p>
                    </div>
                    <div class="bg-stone-50 rounded-xl p-4 text-center">
                        <p class="font-serif text-2xl font-semibold text-green-800">
                            {{ $profile->is_verified ? '✓' : '…' }}
                        </p>
                        <p class="text-xs text-stone-400 mt-1">Statut</p>
                    </div>
                </div>
 
                {{-- Actions rapides --}}
                <div class="flex flex-col gap-2">
                    <a href="{{ route('architect.availabilities.index') }}"
                       class="flex items-center justify-center gap-2 px-4 py-2.5 border border-stone-200
                              text-stone-700 text-sm rounded-xl hover:bg-stone-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        Gérer le calendrier
                    </a>
                    <a href="{{ route('architect.bookings.index') }}"
                       class="flex items-center justify-center gap-2 px-4 py-2.5 bg-green-700 text-white
                              text-sm rounded-xl hover:bg-green-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                        </svg>
                        Voir les réservations
                    </a>
                </div>
 
            </div>
 
        </div>
    </div>
 
@endsection