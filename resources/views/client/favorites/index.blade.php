@extends('layouts.client')
@section('title', 'Mon Moodboard')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Mon Moodboard</h1>
            <p class="text-stone-400 text-sm mt-1">{{ $favorites->total() }} élément(s) sauvegardé(s)</p>
        </div>
    </div>

    @if($favorites->count())
        <div class="grid grid-cols-3 gap-5 mb-8">
            @foreach($favorites as $favorite)
                @php $item = $favorite->favoritable; @endphp

                @if(!$item) @continue @endif

                <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden
                            shadow-sm hover:shadow-md transition group">

                    {{-- Image --}}
                    <div class="aspect-video bg-stone-100 overflow-hidden relative">
                        @if($item instanceof \App\Models\Project && $item->images->first())
                            <img src="{{ Storage::url($item->images->first()->image_path) }}"
                                 alt="{{ $item->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @elseif($item instanceof \App\Models\BlogPost && $item->cover_image)
                            <img src="{{ Storage::url($item->cover_image) }}"
                                 alt="{{ $item->title }}"
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

                        {{-- Badge type --}}
                        <span class="absolute top-2 left-2 text-xs px-2 py-0.5 rounded-full font-medium
                                     {{ $item instanceof \App\Models\Project
                                        ? 'bg-green-700 text-white'
                                        : 'bg-stone-700 text-white' }}">
                            {{ $item instanceof \App\Models\Project ? 'Projet' : 'Article' }}
                        </span>
                    </div>

                    <div class="p-4">
                        <p class="font-medium text-stone-900 text-sm mb-1 truncate">
                            {{ $item->title }}
                        </p>
                        <p class="text-xs text-stone-400 mb-3">
                            Ajouté {{ $favorite->created_at->diffForHumans() }}
                        </p>

                        <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                            @if($item instanceof \App\Models\BlogPost)
                                <a href="{{ route('client.blog.show', $item) }}"
                                   class="text-xs text-green-600 hover:text-green-800 font-medium transition">
                                    Lire l'article →
                                </a>
                            @else
                                <span class="text-xs text-stone-400">Projet</span>
                            @endif

                            <form method="POST"
                                  action="{{ route('client.favorites.destroy', $favorite) }}"
                                  onsubmit="return confirm('Retirer du moodboard ?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-xs text-stone-400 hover:text-red-500 transition">
                                    Retirer
                                </button>
                            </form>
                        </div>
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
                    <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Votre moodboard est vide</h3>
            <p class="text-stone-400 text-sm mb-6">
                Ajoutez des projets et articles qui vous inspirent depuis les profils des architectes.
            </p>
            <a href="{{ route('client.architects.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700
                      text-white text-sm rounded-xl hover:bg-green-800 transition">
                Explorer les architectes
            </a>
        </div>
    @endif

@endsection