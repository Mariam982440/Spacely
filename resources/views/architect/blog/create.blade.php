@extends('layouts.architect')
@section('title', isset($post) ? 'Modifier l\'article' : 'Nouvel article')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">
                {{ isset($post) ? 'Modifier l\'article' : 'Nouvel article' }}
            </h1>
        </div>
        <a href="{{ route('architect.blog.index') }}"
           class="flex items-center gap-2 px-4 py-2 border border-stone-200
                  text-stone-600 text-sm rounded-xl hover:bg-stone-50 transition">
            ← Retour
        </a>
    </div>

    <form method="POST"
          action="{{ isset($post) ? route('architect.blog.update', $post) : route('architect.blog.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if(isset($post)) @method('PUT') @endif

        <div class="grid grid-cols-3 gap-6 items-start">

            {{-- ── Gauche : contenu ── --}}
            <div class="col-span-2 flex flex-col gap-5">

                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">
                        Contenu
                    </p>

                    {{-- Titre --}}
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-medium text-stone-700 mb-1.5">
                            Titre de l'article
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title', $post->title ?? '') }}"
                               placeholder="ex: 5 tendances décoration 2025"
                               class="w-full px-4 py-2.5 bg-stone-50 border rounded-xl text-sm
                                      text-stone-800 outline-none transition
                                      focus:ring-2 focus:ring-green-200 focus:border-green-400
                                      {{ $errors->has('title') ? 'border-red-400' : 'border-stone-200' }}">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contenu --}}
                    <div>
                        <label for="content" class="block text-sm font-medium text-stone-700 mb-1.5">
                            Contenu
                        </label>
                        <textarea id="content"
                                  name="content"
                                  rows="14"
                                  placeholder="Rédigez votre article ici..."
                                  class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm
                                         text-stone-800 outline-none transition leading-relaxed resize-none
                                         focus:ring-2 focus:ring-green-200 focus:border-green-400
                                         {{ $errors->has('content') ? 'border-red-400' : 'border-stone-200' }}">{{ old('content', $post->content ?? '') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-green-700
                                   text-white text-sm font-medium rounded-xl
                                   hover:bg-green-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <path d="M17 21v-8H7v8M7 3v5h8"/>
                        </svg>
                        {{ isset($post) ? 'Enregistrer' : 'Publier l\'article' }}
                    </button>
                    <a href="{{ route('architect.blog.index') }}"
                       class="px-5 py-2.5 border border-stone-200 text-stone-600
                              text-sm rounded-xl hover:bg-stone-50 transition">
                        Annuler
                    </a>
                </div>

            </div>

            {{-- ── Droite : image + statut ── --}}
            <div class="flex flex-col gap-4">

                {{-- Statut --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                        Statut
                    </p>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-stone-100
                                      hover:bg-stone-50 cursor-pointer transition">
                            <input type="radio" name="status" value="draft"
                                   class="accent-green-600"
                                   {{ old('status', $post->status ?? 'draft') === 'draft' ? 'checked' : '' }}>
                            <div>
                                <p class="text-sm font-medium text-stone-700">Brouillon</p>
                                <p class="text-xs text-stone-400">Non visible par les clients</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-stone-100
                                      hover:bg-stone-50 cursor-pointer transition">
                            <input type="radio" name="status" value="published"
                                   class="accent-green-600"
                                   {{ old('status', $post->status ?? '') === 'published' ? 'checked' : '' }}>
                            <div>
                                <p class="text-sm font-medium text-stone-700">Publié</p>
                                <p class="text-xs text-stone-400">Visible sur le blog public</p>
                            </div>
                        </label>
                    </div>
                    @error('status')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image de couverture --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                        Image de couverture
                    </p>

                    <label for="cover_image"
                           class="flex flex-col items-center gap-3 p-5 border-2 border-dashed
                                  border-stone-200 rounded-xl cursor-pointer text-center
                                  hover:border-green-400 hover:bg-green-50 transition">

                        <div id="cover-preview">
                            @if(isset($post) && $post->cover_image)
                                <img src="{{ Storage::url($post->cover_image) }}"
                                     alt="Couverture actuelle"
                                     class="w-full h-28 object-cover rounded-lg">
                            @else
                                <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm text-stone-500">Cliquer pour ajouter une image</p>
                            <p class="text-xs text-stone-400 mt-0.5">JPG, PNG — max 3MB</p>
                        </div>

                        <input type="file" id="cover_image" name="cover_image"
                               accept="image/*" class="hidden"
                               onchange="previewCover(this)">
                    </label>

                    @error('cover_image')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script>
function previewCover(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('cover-preview').innerHTML =
            `<img src="${e.target.result}" class="w-full h-28 object-cover rounded-lg" alt="Aperçu">`;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush