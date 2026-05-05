@extends('layouts.client')
@section('title', 'Blog')

@section('content')

    <div class="mb-8">
        <h1 class="font-serif text-2xl font-semibold text-stone-900">Blog</h1>
        <p class="text-stone-400 text-sm mt-1">Conseils et inspirations en décoration intérieure</p>
    </div>

    @if($posts->count())
        <div class="grid grid-cols-3 gap-5 mb-8">
            @foreach($posts as $post)
                <a href="{{ route('client.blog.show', $post) }}"
                   class="bg-white border border-stone-200 rounded-2xl overflow-hidden
                          shadow-sm hover:shadow-md transition group block">

                    {{-- Image couverture --}}
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

                    <div class="p-5">
                        <h3 class="font-medium text-stone-900 leading-snug mb-2
                                   group-hover:text-green-700 transition line-clamp-2">
                            {{ $post->title }}
                        </h3>
                        <p class="text-stone-400 text-sm line-clamp-2 leading-relaxed mb-4 font-light">
                            {{ Str::limit(strip_tags($post->content), 100) }}
                        </p>
                        <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center
                                            justify-center text-green-800 text-xs font-medium">
                                    {{ strtoupper(substr($post->architectProfile->user->name, 0, 1)) }}
                                </div>
                                <span class="text-xs text-stone-500">
                                    {{ $post->architectProfile->user->name }}
                                </span>
                            </div>
                            <span class="text-xs text-stone-400">
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{ $posts->links() }}

    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucun article publié</h3>
            <p class="text-stone-400 text-sm">Revenez bientôt.</p>
        </div>
    @endif

@endsection