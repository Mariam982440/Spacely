@extends('layouts.client')
@section('title', $post->title)

@section('content')

    <div class="max-w-3xl mx-auto">

        <a href="{{ route('client.blog.index') }}"
           class="inline-flex items-center gap-2 text-sm text-stone-400
                  hover:text-stone-600 transition mb-6">
            ← Retour au blog
        </a>

        @if($post->cover_image)
            <div class="aspect-video rounded-2xl overflow-hidden bg-stone-100 mb-8">
                <img src="{{ Storage::url($post->cover_image) }}"
                     alt="{{ $post->title }}"
                     class="w-full h-full object-cover">
            </div>
        @endif

        <div class="mb-8">
            <h1 class="font-serif text-3xl font-semibold text-stone-900 leading-tight mb-4">
                {{ $post->title }}
            </h1>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center
                                justify-center text-green-800 text-sm font-medium">
                        {{ strtoupper(substr($post->architectProfile->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-stone-700">
                            {{ $post->architectProfile->user->name }}
                        </p>
                        <p class="text-xs text-stone-400">{{ $post->architectProfile->city }}</p>
                    </div>
                </div>
                <span class="text-stone-300">·</span>
                <span class="text-sm text-stone-400">
                    {{ $post->created_at->locale('fr')->isoFormat('D MMMM YYYY') }}
                </span>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-8 shadow-sm mb-8">
            <div class="text-stone-600 leading-relaxed text-sm font-light">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>

        <div class="flex items-center gap-3 mb-10">

            {{-- Ajouter au moodboard --}}
            <form method="POST" action="{{ route('client.favorites.store') }}">
                @csrf
                <input type="hidden" name="favoritable_id" value="{{ $post->id }}">
                <input type="hidden" name="favoritable_type" value="App\Models\BlogPost">
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 border border-stone-200
                               text-stone-600 text-sm rounded-xl hover:bg-stone-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                    </svg>
                    Sauvegarder dans mon moodboard
                </button>
            </form>

            <a href="{{ route('client.messages.show', $post->architectProfile->user) }}"
               class="flex items-center gap-2 px-4 py-2 bg-green-700 text-white
                      text-sm rounded-xl hover:bg-green-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
                Contacter {{ $post->architectProfile->user->name }}
            </a>
        </div>

        @if($related->count())
            <div>
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                    Du même architecte
                </p>
                <div class="grid grid-cols-3 gap-4">
                    @foreach($related as $relatedPost)
                        <a href="{{ route('client.blog.show', $relatedPost) }}"
                           class="bg-white border border-stone-200 rounded-xl overflow-hidden
                                  shadow-sm hover:shadow-md transition group block">
                            <div class="aspect-video bg-stone-100 overflow-hidden">
                                @if($relatedPost->cover_image)
                                    <img src="{{ Storage::url($relatedPost->cover_image) }}"
                                         alt="{{ $relatedPost->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition">
                                @else
                                    <div class="w-full h-full bg-green-50"></div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="text-sm font-medium text-stone-800 line-clamp-2
                                          group-hover:text-green-700 transition">
                                    {{ $relatedPost->title }}
                                </p>
                                <p class="text-xs text-stone-400 mt-1">
                                    {{ $relatedPost->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection