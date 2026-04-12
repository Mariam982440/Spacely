@extends('layouts.architect')
@section('title', 'Modifier mon profil')

@section('content')

    {{-- ── En-tête de page ── --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Modifier mon profil</h1>
            <p class="text-stone-400 text-sm mt-1">Ces informations seront visibles par les clients</p>
        </div>
        <a href="{{ route('architect.profile.show') }}"
           class="flex items-center gap-2 px-4 py-2 border border-stone-200 text-stone-600
                  text-sm rounded-xl hover:bg-stone-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Retour
        </a>
    </div>

    <form method="POST" action="{{ route('architect.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-3 gap-6 items-start">

            {{-- ── Colonne gauche (2/3) : Formulaire ── --}}
            <div class="col-span-2">
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">

                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">
                        Informations principales
                    </p>

                    {{-- Ville + Expérience (2 colonnes) --}}
                    <div class="grid grid-cols-2 gap-4 mb-5">

                        {{-- Ville --}}
                        <div>
                            <label for="city" class="block text-sm font-medium text-stone-700 mb-1.5">
                                Ville
                            </label>
                            <input type="text"
                                   id="city"
                                   name="city"
                                   value="{{ old('city', $profile->city) }}"
                                   placeholder="ex: Marrakech"
                                   class="w-full px-4 py-2.5 bg-stone-50 border rounded-xl text-sm
                                          text-stone-800 outline-none transition
                                          focus:ring-2 focus:ring-green-200 focus:border-green-400
                                          {{ $errors->has('city') ? 'border-red-400' : 'border-stone-200' }}">
                            @error('city')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Années d'expérience --}}
                        <div>
                            <label for="experience_years" class="block text-sm font-medium text-stone-700 mb-1.5">
                                Années d'expérience
                            </label>
                            <input type="number"
                                   id="experience_years"
                                   name="experience_years"
                                   value="{{ old('experience_years', $profile->experience_years) }}"
                                   min="0" max="60"
                                   placeholder="ex: 8"
                                   class="w-full px-4 py-2.5 bg-stone-50 border rounded-xl text-sm
                                          text-stone-800 outline-none transition
                                          focus:ring-2 focus:ring-green-200 focus:border-green-400
                                          {{ $errors->has('experience_years') ? 'border-red-400' : 'border-stone-200' }}">
                            @error('experience_years')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Séparateur --}}
                    <hr class="border-stone-100 mb-5">

                    {{-- Biographie --}}
                    <div>
                        <label for="bio" class="block text-sm font-medium text-stone-700 mb-1.5">
                            Biographie
                        </label>
                        <textarea id="bio"
                                  name="bio"
                                  rows="6"
                                  placeholder="Décrivez votre philosophie, votre style, vos spécialités..."
                                  class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm
                                         text-stone-800 outline-none transition leading-relaxed resize-none
                                         focus:ring-2 focus:ring-green-200 focus:border-green-400
                                         {{ $errors->has('bio') ? 'border-red-400' : 'border-stone-200' }}">{{ old('bio', $profile->bio) }}</textarea>
                        <p class="text-stone-400 text-xs mt-1.5">Maximum 1000 caractères.</p>
                        @error('bio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- ── Boutons d'action ── --}}
                <div class="flex items-center gap-3 mt-5">
                    <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-green-700 text-white
                                   text-sm font-medium rounded-xl hover:bg-green-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <path d="M17 21v-8H7v8M7 3v5h8"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('architect.profile.show') }}"
                       class="px-5 py-2.5 border border-stone-200 text-stone-600 text-sm
                              rounded-xl hover:bg-stone-50 transition">
                        Annuler
                    </a>
                </div>

            </div>

            {{-- ── Colonne droite (1/3) : Photo + Conseils ── --}}
            <div class="flex flex-col gap-4">

                {{-- Upload photo --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                        Photo de profil
                    </p>

                    <label for="profile_picture"
                           class="flex flex-col items-center gap-3 p-6 border-2 border-dashed
                                  border-stone-200 rounded-xl cursor-pointer text-center
                                  hover:border-green-400 hover:bg-green-50 transition">

                        {{-- Aperçu de la photo --}}
                        <div class="w-20 h-20 rounded-full overflow-hidden bg-green-100
                                    flex items-center justify-center" id="avatar-wrap">
                            @if($profile->profile_picture)
                                <img id="avatar-preview"
                                     src="{{ Storage::url($profile->profile_picture) }}"
                                     alt="Photo actuelle"
                                     class="w-full h-full object-cover">
                            @else
                                <span id="avatar-initial"
                                      class="font-serif text-2xl text-green-800">
                                    {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm text-stone-600">Cliquer pour changer la photo</p>
                            <p class="text-xs text-stone-400 mt-0.5">JPG, PNG, WEBP — max 2MB</p>
                        </div>

                        <input type="file"
                               id="profile_picture"
                               name="profile_picture"
                               accept="image/jpeg,image/png,image/webp"
                               class="hidden"
                               onchange="previewPhoto(this)">
                    </label>

                    @error('profile_picture')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Conseils --}}
                <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
                    <p class="text-xs font-medium tracking-widest text-green-600 uppercase mb-3">
                        Conseils
                    </p>
                    <ul class="flex flex-col gap-2.5">
                        <li class="flex items-start gap-2 text-xs text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0 mt-1.5"></span>
                            Une photo professionnelle inspire confiance aux clients
                        </li>
                        <li class="flex items-start gap-2 text-xs text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0 mt-1.5"></span>
                            Mentionnez vos spécialités dans la biographie
                        </li>
                        <li class="flex items-start gap-2 text-xs text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0 mt-1.5"></span>
                            Un profil complet est mis en avant sur la plateforme
                        </li>
                        <li class="flex items-start gap-2 text-xs text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0 mt-1.5"></span>
                            Ajoutez des photos avant/après dans vos projets
                        </li>
                    </ul>
                </div>

            </div>

        </div>

    </form>

@endsection

@push('scripts')
<script>
    function previewPhoto(input) {
        if (!input.files || !input.files[0]) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const wrap = document.getElementById('avatar-wrap');
            wrap.innerHTML = `<img src="${e.target.result}"
                                   class="w-full h-full object-cover"
                                   alt="Aperçu">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endpush