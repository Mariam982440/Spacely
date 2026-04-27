@extends('layouts.architect')
@section('title', 'Mon Blog')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Mon Blog</h1>
            <p class="text-stone-400 text-sm mt-1">{{ $posts->total() }} article(s)</p>
        </div>
        <a href="{{ route('architect.blog.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-green-700 text-white
                  text-sm font-medium rounded-xl hover:bg-green-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Nouvel article
        </a>
    </div>

    @if($posts->count())
        <div class="flex flex-col gap-4 mb-6">
            @foreach($posts as $post)
                <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm
                            flex items-start gap-5 hover:shadow-md transition">

                    {{-- Image de couverture --}}
                    <div class="w-28 h-20 rounded-xl overflow-hidden bg-stone-100 shrink-0">
                        @if($post->cover_image)
                            <img src="{{ Storage::url($post->cover_image) }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-green-50">
                                <svg class="w-7 h-7 text-green-300" fill="none" stroke="currentColor"
                                     stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-medium text-stone-900 mb-1">{{ $post->title }}</h3>
                                <p class="text-stone-400 text-sm line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($post->content), 120) }}
                                </p>
                            </div>
                            {{-- Statut --}}
                            <span class="shrink-0 text-xs font-medium px-2.5 py-1 rounded-full
                                         {{ $post->status === 'published'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-stone-100 text-stone-500' }}">
                                {{ $post->status === 'published' ? 'Publié' : 'Brouillon' }}
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3 mt-3 pt-3 border-t border-stone-100">
                            <span class="text-xs text-stone-400">
                                {{ $post->created_at->format('d/m/Y') }}
                            </span>
                            <span class="text-stone-200">•</span>
                            <a href="{{ route('architect.blog.edit', $post) }}"
                               class="text-xs text-stone-500 hover:text-green-700 font-medium transition">
                                Modifier
                            </a>
                            <span class="text-stone-200">•</span>
                            <form method="POST"
                                  action="{{ route('architect.blog.destroy', $post) }}"
                                  onsubmit="return confirm('Supprimer cet article ?')">
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
            @endforeach
        </div>

        {{ $posts->links() }}

    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor"
                     stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucun article pour le moment</h3>
            <p class="text-stone-400 text-sm mb-6">Partagez vos conseils en décoration intérieure.</p>
            <a href="{{ route('architect.blog.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700
                      text-white text-sm rounded-xl hover:bg-green-800 transition">
                + Écrire un article
            </a>
        </div>
    @endif

@endsection