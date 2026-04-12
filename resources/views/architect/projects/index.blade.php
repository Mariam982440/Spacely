@extends('layouts.architect')
@section('title', 'Mes Projets')

@section('content')

    {{-- ── En-tête ── --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Mes Projets</h1>
            <p class="text-stone-400 text-sm mt-1">{{ $projects->total() }} projet(s) publié(s)</p>
        </div>
        <a href="{{ route('architect.projects.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-green-700 text-white
                  text-sm font-medium rounded-xl hover:bg-green-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Nouveau projet
        </a>
    </div>

    {{-- ── Grille de projets ── --}}
    @if($projects->count())
        <div class="grid grid-cols-3 gap-5 mb-8">
            @foreach($projects as $project)
                <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden shadow-sm
                            hover:shadow-md transition group">

                    {{-- Image principale --}}
                    <div class="aspect-video bg-stone-100 overflow-hidden">
                        @if($project->images->first())
                            <img src="{{ Storage::url($project->images->first()->image_path) }}"
                                 alt="{{ $project->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-green-50">
                                <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Contenu --}}
                    <div class="p-5">
                        <h3 class="font-medium text-stone-900 mb-1 truncate">{{ $project->title }}</h3>
                        <p class="text-stone-400 text-sm leading-relaxed line-clamp-2 mb-3">
                            {{ $project->description }}
                        </p>

                        {{-- Tags --}}
                        @if($project->tags->count())
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($project->tags->take(3) as $tag)
                                    <span class="bg-green-100 text-green-800 text-xs px-2.5 py-0.5 rounded-full">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Infos + Actions --}}
                        <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                            <span class="text-xs text-stone-400">
                                {{ $project->images->count() }} photo(s)
                            </span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('architect.projects.show', $project) }}"
                                   class="text-xs text-stone-500 hover:text-green-700 transition">
                                    Voir
                                </a>
                                <span class="text-stone-200">|</span>
                                <a href="{{ route('architect.projects.edit', $project) }}"
                                   class="text-xs text-stone-500 hover:text-green-700 transition">
                                    Modifier
                                </a>
                                <span class="text-stone-200">|</span>
                                <form method="POST"
                                      action="{{ route('architect.projects.destroy', $project) }}"
                                      onsubmit="return confirm('Supprimer ce projet ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-stone-400 hover:text-red-500 transition">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        {{ $projects->links() }}

    @else
        {{-- État vide --}}
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor"
                     stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <path d="M21 15l-5-5L5 21"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucun projet pour le moment</h3>
            <p class="text-stone-400 text-sm mb-6">Publiez votre première réalisation pour attirer des clients.</p>
            <a href="{{ route('architect.projects.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 text-white
                      text-sm rounded-xl hover:bg-green-800 transition">
                + Ajouter un projet
            </a>
        </div>
    @endif

@endsection