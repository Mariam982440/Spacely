@extends('layouts.client')
@section('title', 'Articles sauvegardés')

@section('content')

    {{-- ── Onglets blog ── --}}
    <div class="flex items-center gap-1 mb-8 border-b border-stone-200">
        <a href="{{ route('client.blog.index') }}"
           class="px-4 py-3 text-sm font-medium text-stone-500
                  hover:text-stone-800 border-b-2 border-transparent hover:border-stone-300 transition">
            Tous les articles
        </a>
        <a href="{{ route('client.blog.favorites') }}"
           class="px-4 py-3 text-sm font-medium text-green-700
                  border-b-2 border-green-700 transition">
            Mes articles sauvegardés
            <span class="ml-1.5 bg-green-100 text-green-700 text-xs px-1.5 py-0.5 rounded-full">
                {{ $favorites->total() }}
            </span>
        </a>
    </div>

    @if($favorites->count())
        <div class="grid grid-cols-3 gap-5 mb-8">
            @foreach($favorites as $favorite)
                @php $post = $favorite->favoritable; @endphp
                @if(!$post) @continue @endif

                <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden
                            shadow-sm hover:shadow-md transition group relative">

                    {{-- Image --}}
                    <a href="{{ route('client.blog.show', $post) }}" class="block">
                        <div class="aspect-video bg-stone-100 overflow-hidden">
                            @if($post->cover_image)
                                <img src="{{ Storage::url($post->cover_image) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-green-50">
                                    <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor"
                                         stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <p class="font-medium text-stone-900 text-sm leading-snug mb-1 line-clamp-2
                                      group-hover:text-green-700 transition">
                                {{ $post->title }}
                            </p>
                            <p class="text-xs text-stone-400 mb-3">
                                {{ $post->architectProfile->user->name }}
                                · {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>

                    {{-- Retirer --}}
                    <div class="px-4 pb-4 flex justify-end border-t border-stone-100 pt-3">
                        <form method="POST"
                              action="{{ route('client.favorites.destroy', $favorite) }}"
                              onsubmit="return confirm('Retirer cet article de vos favoris ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-stone-400 hover:text-red-500 transition">
                                Retirer des favoris
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>

        {{ $favorites->links() }}

    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor"
                     stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucun article sauvegardé</h3>
            <p class="text-stone-400 text-sm mb-6">
                Sauvegardez des articles depuis le blog pour les retrouver ici.
            </p>
            <a href="{{ route('client.blog.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700
                      text-white text-sm rounded-xl hover:bg-green-800 transition">
                Parcourir le blog
            </a>
        </div>
    @endif

@endsection