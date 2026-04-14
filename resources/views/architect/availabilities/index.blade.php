@extends('layouts.architect')
@section('title', 'Calendrier')

@section('content')

    {{-- ── En-tête ── --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Calendrier</h1>
            <p class="text-stone-400 text-sm mt-1">Configurez vos jours et horaires de disponibilité</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 items-start">

        {{-- ── Gauche : Formulaire ajout ── --}}
        <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
            <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">
                Ajouter une disponibilité
            </p>

            <form method="POST" action="{{ route('architect.availabilities.store') }}">
                @csrf

                {{-- Jour --}}
                <div class="mb-4">
                    <label for="day_of_week" class="block text-sm font-medium text-stone-700 mb-1.5">
                        Jour de la semaine
                    </label>
                    <select id="day_of_week"
                            name="day_of_week"
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                                   text-sm text-stone-800 outline-none transition cursor-pointer
                                   focus:ring-2 focus:ring-green-200 focus:border-green-400
                                   {{ $errors->has('day_of_week') ? 'border-red-400' : '' }}">
                        <option value="">-- Choisir --</option>
                        <option value="monday"    {{ old('day_of_week') === 'monday'    ? 'selected' : '' }}>Lundi</option>
                        <option value="tuesday"   {{ old('day_of_week') === 'tuesday'   ? 'selected' : '' }}>Mardi</option>
                        <option value="wednesday" {{ old('day_of_week') === 'wednesday' ? 'selected' : '' }}>Mercredi</option>
                        <option value="thursday"  {{ old('day_of_week') === 'thursday'  ? 'selected' : '' }}>Jeudi</option>
                        <option value="friday"    {{ old('day_of_week') === 'friday'    ? 'selected' : '' }}>Vendredi</option>
                        <option value="saturday"  {{ old('day_of_week') === 'saturday'  ? 'selected' : '' }}>Samedi</option>
                        <option value="sunday"    {{ old('day_of_week') === 'sunday'    ? 'selected' : '' }}>Dimanche</option>
                    </select>
                    @error('day_of_week')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Heure début --}}
                <div class="mb-4">
                    <label for="start_time" class="block text-sm font-medium text-stone-700 mb-1.5">
                        Heure de début
                    </label>
                    <input type="time"
                           id="start_time"
                           name="start_time"
                           value="{{ old('start_time', '09:00') }}"
                           class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                                  text-sm text-stone-800 outline-none transition
                                  focus:ring-2 focus:ring-green-200 focus:border-green-400
                                  {{ $errors->has('start_time') ? 'border-red-400' : '' }}">
                    @error('start_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Heure fin --}}
                <div class="mb-4">
                    <label for="end_time" class="block text-sm font-medium text-stone-700 mb-1.5">
                        Heure de fin
                    </label>
                    <input type="time"
                           id="end_time"
                           name="end_time"
                           value="{{ old('end_time', '17:00') }}"
                           class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                                  text-sm text-stone-800 outline-none transition
                                  focus:ring-2 focus:ring-green-200 focus:border-green-400
                                  {{ $errors->has('end_time') ? 'border-red-400' : '' }}">
                    @error('end_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Durée des créneaux --}}
                <div class="mb-6">
                    <label for="slot_duration" class="block text-sm font-medium text-stone-700 mb-1.5">
                        Durée d'un créneau
                    </label>
                    <select id="slot_duration"
                            name="slot_duration"
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                                   text-sm text-stone-800 outline-none transition cursor-pointer
                                   focus:ring-2 focus:ring-green-200 focus:border-green-400">
                        <option value="30"  {{ old('slot_duration') == 30  ? 'selected' : '' }}>30 minutes</option>
                        <option value="60"  {{ old('slot_duration', 60) == 60  ? 'selected' : '' }}>1 heure</option>
                        <option value="90"  {{ old('slot_duration') == 90  ? 'selected' : '' }}>1h30</option>
                        <option value="120" {{ old('slot_duration') == 120 ? 'selected' : '' }}>2 heures</option>
                    </select>
                    @error('slot_duration')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5
                               bg-green-700 text-white text-sm font-medium rounded-xl
                               hover:bg-green-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Ajouter
                </button>
            </form>
        </div>

        {{-- ── Droite : Liste des disponibilités ── --}}
        <div class="col-span-2">

            @if($availabilities->count())

                {{-- Résumé --}}
                <div class="grid grid-cols-3 gap-4 mb-5">
                    <div class="bg-white border border-stone-200 rounded-2xl p-4 text-center shadow-sm">
                        <p class="font-serif text-2xl font-semibold text-green-800">
                            {{ $availabilities->count() }}
                        </p>
                        <p class="text-xs text-stone-400 mt-1">Jours configurés</p>
                    </div>
                    <div class="bg-white border border-stone-200 rounded-2xl p-4 text-center shadow-sm">
                        <p class="font-serif text-2xl font-semibold text-green-800">
                            {{ $availabilities->sum('time_slots_count') }}
                        </p>
                        <p class="text-xs text-stone-400 mt-1">Créneaux générés</p>
                    </div>
                    <div class="bg-white border border-stone-200 rounded-2xl p-4 text-center shadow-sm">
                        <p class="font-serif text-2xl font-semibold text-green-800">4</p>
                        <p class="text-xs text-stone-400 mt-1">Semaines à venir</p>
                    </div>
                </div>

                {{-- Liste --}}
                <div class="flex flex-col gap-3">
                    @foreach($availabilities as $availability)

                        @php
                            $days = [
                                'monday'    => 'Lundi',
                                'tuesday'   => 'Mardi',
                                'wednesday' => 'Mercredi',
                                'thursday'  => 'Jeudi',
                                'friday'    => 'Vendredi',
                                'saturday'  => 'Samedi',
                                'sunday'    => 'Dimanche',
                            ];
                        @endphp

                        <div class="bg-white border border-stone-200 rounded-2xl px-6 py-4 shadow-sm
                                    flex items-center justify-between">

                            <div class="flex items-center gap-5">
                                {{-- Jour --}}
                                <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center
                                            justify-center flex-shrink-0">
                                    <span class="text-green-800 text-sm font-semibold">
                                        {{ substr($days[$availability->day_of_week], 0, 3) }}
                                    </span>
                                </div>

                                {{-- Détails --}}
                                <div>
                                    <p class="font-medium text-stone-800">
                                        {{ $days[$availability->day_of_week] }}
                                    </p>
                                    <p class="text-stone-400 text-sm mt-0.5">
                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}
                                        →
                                        {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                {{-- Compteur créneaux --}}
                                <div class="text-right">
                                    <p class="text-sm font-medium text-stone-700">
                                        {{ $availability->time_slots_count }}
                                    </p>
                                    <p class="text-xs text-stone-400">créneaux</p>
                                </div>

                                {{-- Supprimer --}}
                                <form method="POST"
                                      action="{{ route('architect.availabilities.destroy', $availability) }}"
                                      onsubmit="return confirm('Supprimer cette disponibilité ? Les créneaux non réservés seront supprimés.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-stone-400 hover:text-red-500
                                                   hover:bg-red-50 rounded-xl transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                             stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14H6L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4h6v2"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

            @else
                <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor"
                             stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-lg text-stone-700 mb-2">Aucune disponibilité configurée</h3>
                    <p class="text-stone-400 text-sm">
                        Ajoutez vos jours de travail pour que les clients puissent vous réserver.
                    </p>
                </div>
            @endif

        </div>
    </div>

@endsection