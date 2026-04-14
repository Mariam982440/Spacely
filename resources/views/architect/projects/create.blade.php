@extends('layouts.architect')
@section('title', isset($project) ? 'Modifier le projet' : 'Nouveau projet')

@section('content')

    {{-- ── En-tête ── --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">
                {{ isset($project) ? 'Modifier le projet' : 'Nouveau projet' }}
            </h1>
            <p class="text-stone-400 text-sm mt-1">
                {{ isset($project) ? $project->title : 'Remplissez les informations de votre réalisation' }}
            </p>
        </div>
        <a href="{{ route('architect.projects.index') }}"
           class="flex items-center gap-2 px-4 py-2 border border-stone-200 text-stone-600
                  text-sm rounded-xl hover:bg-stone-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Retour
        </a>
    </div>

    <form method="POST"
          action="{{ isset($project) ? route('architect.projects.update', $project) : route('architect.projects.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if(isset($project)) @method('PUT') @endif

        <div class="grid grid-cols-3 gap-6 items-start">

            {{-- ── Colonne gauche (2/3) ── --}}
            <div class="col-span-2 flex flex-col gap-5">

                {{-- Informations principales --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">
                        Informations du projet
                    </p>

                    {{-- Titre --}}
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-medium text-stone-700 mb-1.5">
                            Titre du projet
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title', $project->title ?? '') }}"
                               placeholder="ex: Rénovation appartement haussmannien"
                               class="w-full px-4 py-2.5 bg-stone-50 border rounded-xl text-sm
                                      text-stone-800 outline-none transition
                                      focus:ring-2 focus:ring-green-200 focus:border-green-400
                                      {{ $errors->has('title') ? 'border-red-400' : 'border-stone-200' }}">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-stone-700 mb-1.5">
                            Description
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="5"
                                  placeholder="Décrivez le projet, les matériaux utilisés, le style..."
                                  class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm
                                         text-stone-800 outline-none transition leading-relaxed resize-none
                                         focus:ring-2 focus:ring-green-200 focus:border-green-400
                                         {{ $errors->has('description') ? 'border-red-400' : 'border-stone-200' }}">{{ old('description', $project->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Upload photos --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">
                        Photos du projet
                    </p>

                    {{-- Zone de drop --}}
                    <label for="images"
                           class="flex flex-col items-center gap-3 p-8 border-2 border-dashed
                                  border-stone-200 rounded-xl cursor-pointer text-center
                                  hover:border-green-400 hover:bg-green-50 transition mb-4">
                        <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor"
                             stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <div>
                            <p class="text-sm text-stone-600 font-medium">Cliquer pour ajouter des photos</p>
                            <p class="text-xs text-stone-400 mt-0.5">JPG, PNG, WEBP — max 4MB par photo</p>
                        </div>
                        <input type="file"
                               id="images"
                               name="images[]"
                               accept="image/*"
                               multiple
                               class="hidden"
                               onchange="previewImages(this)">
                    </label>

                    @error('images')
                        <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
                    @enderror

                    {{-- Aperçu des nouvelles images --}}
                    <div id="images-preview" class="grid grid-cols-4 gap-3 hidden">
                        {{-- Rempli par JS --}}
                    </div>

                    {{-- Images existantes (mode édition) --}}
                    @if(isset($project) && $project->images->count())
                        <div class="mt-4">
                            <p class="text-xs text-stone-400 uppercase tracking-widest mb-3">
                                Photos actuelles
                            </p>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($project->images as $image)
                                    <div class="relative aspect-square rounded-xl overflow-hidden bg-stone-100">
                                        <img src="{{ Storage::url($image->image_path) }}"
                                             alt="Photo projet"
                                             class="w-full h-full object-cover">
                                        @if($image->is_before)
                                            <span class="absolute top-1.5 left-1.5 bg-blue-500 text-white
                                                         text-xs px-1.5 py-0.5 rounded-md">Avant</span>
                                        @endif
                                        @if($image->is_after)
                                            <span class="absolute top-1.5 left-1.5 bg-green-500 text-white
                                                         text-xs px-1.5 py-0.5 rounded-md">Après</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-green-700 text-white
                                   text-sm font-medium rounded-xl hover:bg-green-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <path d="M17 21v-8H7v8M7 3v5h8"/>
                        </svg>
                        {{ isset($project) ? 'Enregistrer les modifications' : 'Publier le projet' }}
                    </button>
                    <a href="{{ route('architect.projects.index') }}"
                       class="px-5 py-2.5 border border-stone-200 text-stone-600 text-sm
                              rounded-xl hover:bg-stone-50 transition">
                        Annuler
                    </a>
                </div>

            </div>

            {{-- ── Colonne droite (1/3) : Tags ── --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                    Styles / Tags
                </p>
                <p class="text-xs text-stone-400 mb-4">
                    Sélectionnez les styles qui correspondent à ce projet.
                </p>

                <div class="flex flex-col gap-2">
                    @foreach($tags as $tag)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-stone-100
                                      hover:bg-stone-50 cursor-pointer transition">
                            <input type="checkbox"
                                   name="tags[]"
                                   value="{{ $tag->id }}"
                                   class="accent-green-600 w-4 h-4"
                                   {{ isset($projectTags) && in_array($tag->id, $projectTags) ? 'checked' : '' }}
                                   {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'checked' : '' }}>
                            <span class="text-sm text-stone-700">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>

                @error('tags')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

        </div>

    </form>

@endsection

@push('scripts')
<script>
function previewImages(input) {
    const preview = document.getElementById('images-preview');
    preview.innerHTML = '';

    if (!input.files || input.files.length === 0) {
        preview.classList.add('hidden');
        return;
    }

    preview.classList.remove('hidden');

    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative aspect-square rounded-xl overflow-hidden bg-stone-100';
            div.innerHTML = `
                <img src="${e.target.result}"
                     class="w-full h-full object-cover"
                     alt="Aperçu ${index + 1}">
                <div class="absolute bottom-0 left-0 right-0 bg-black/40 px-2 py-1">
                    <p class="text-white text-xs truncate">${file.name}</p>
                </div>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush