@extends('layouts.architect')
@section('title', $project->title)

@section('content')

    {{-- ── En-tête ── --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">{{ $project->title }}</h1>
            <p class="text-stone-400 text-sm mt-1">
                Publié le {{ $project->created_at->format('d/m/Y') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('architect.projects.edit', $project) }}"
               class="flex items-center gap-2 px-4 py-2 border border-stone-200 text-stone-600
                      text-sm rounded-xl hover:bg-stone-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Modifier
            </a>
            <a href="{{ route('architect.projects.index') }}"
               class="flex items-center gap-2 px-4 py-2 border border-stone-200 text-stone-600
                      text-sm rounded-xl hover:bg-stone-50 transition">
                ← Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 items-start">

        {{-- ── Gauche : images + description ── --}}
        <div class="col-span-2 flex flex-col gap-5">

            {{-- Image principale --}}
            @if($project->images->first())
                <div class="rounded-2xl overflow-hidden aspect-video bg-stone-100">
                    <img src="{{ Storage::url($project->images->first()->image_path) }}"
                         alt="{{ $project->title }}"
                         class="w-full h-full object-cover"
                         id="main-image">
                </div>
            @endif

            {{-- Galerie miniatures --}}
            @if($project->images->count() > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($project->images as $image)
                        <button onclick="changeMain('{{ Storage::url($image->image_path) }}')"
                                class="relative aspect-square rounded-xl overflow-hidden bg-stone-100
                                       border-2 border-transparent hover:border-green-400 transition">
                            <img src="{{ Storage::url($image->image_path) }}"
                                 alt="Photo"
                                 class="w-full h-full object-cover">
                            @if($image->is_before)
                                <span class="absolute top-1 left-1 bg-blue-500 text-white
                                             text-xs px-1 py-0.5 rounded text-[10px]">Avant</span>
                            @endif
                            @if($image->is_after)
                                <span class="absolute top-1 left-1 bg-green-500 text-white
                                             text-xs px-1 py-0.5 rounded text-[10px]">Après</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Description --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                    Description
                </p>
                <p class="text-stone-600 text-sm leading-relaxed font-light">
                    {{ $project->description }}
                </p>
            </div>

        </div>

        {{-- ── Droite : infos ── --}}
        <div class="flex flex-col gap-4">

            {{-- Tags --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-3">
                    Styles
                </p>
                @if($project->tags->count())
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->tags as $tag)
                            <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-stone-400 text-sm">Aucun tag</p>
                @endif
            </div>

            {{-- Stats --}}
            <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                    Détails
                </p>
                <div class="flex flex-col gap-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-stone-400">Photos</span>
                        <span class="font-medium text-stone-700">{{ $project->images->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-stone-400">Publié le</span>
                        <span class="font-medium text-stone-700">
                            {{ $project->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-stone-400">Modifié le</span>
                        <span class="font-medium text-stone-700">
                            {{ $project->updated_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Supprimer --}}
            <form method="POST"
                  action="{{ route('architect.projects.destroy', $project) }}"
                  onsubmit="return confirm('Supprimer définitivement ce projet et toutes ses photos ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5
                               bg-red-50 text-red-600 border border-red-200 text-sm
                               rounded-xl hover:bg-red-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14H6L5 6"/>
                        <path d="M10 11v6M14 11v6"/>
                        <path d="M9 6V4h6v2"/>
                    </svg>
                    Supprimer ce projet
                </button>
            </form>

        </div>
    </div>

@endsection

@push('scripts')
<script>
function changeMain(src) {
    document.getElementById('main-image').src = src;
}
</script>
@endpush