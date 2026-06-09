@extends('layouts.admin')
@section('title', $architect->user->name)

@section('content')
    <a href="{{ route('admin.architects.index') }}" class="inline-flex items-center text-sm text-stone-400 hover:text-stone-600 transition mb-6">
        ← Retour aux architectes
    </a>

    <div class="h-52 rounded-t-2xl overflow-hidden bg-gradient-to-r from-green-900 via-green-700 to-green-500">
        @if($architect->cover_photo)
            <img src="{{ Storage::url($architect->cover_photo) }}" alt="Couverture {{ $architect->user->name }}" class="w-full h-full object-cover">
        @endif
    </div>

    <div class="bg-white border border-stone-200 rounded-b-2xl px-8 pb-6 mb-6 shadow-sm">
        <div class="flex items-end justify-between -mt-12 mb-4">
            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md bg-green-100 flex items-center justify-center overflow-hidden">
                @if($architect->profile_picture)
                    <img src="{{ Storage::url($architect->profile_picture) }}" alt="{{ $architect->user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="font-serif text-3xl text-green-800">{{ strtoupper(substr($architect->user->name, 0, 1)) }}</span>
                @endif
            </div>

            <div class="flex items-center gap-2">
                @if(!$architect->is_verified)
                    <form method="POST" action="{{ route('admin.architects.approve', $architect) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 bg-green-700 text-white text-sm rounded-xl hover:bg-green-800 transition">
                            Valider le compte
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.architects.suspend', $architect) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 border border-yellow-200 text-yellow-700 text-sm rounded-xl hover:bg-yellow-50 transition">
                            Remettre en attente
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3 mb-2">
            <h1 class="font-serif text-2xl font-semibold text-stone-900">{{ $architect->user->name }}</h1>
            <span class="text-xs px-2.5 py-1 rounded-full {{ $architect->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ $architect->is_verified ? 'Validé' : 'En attente' }}
            </span>
        </div>

        <div class="flex items-center gap-5 text-sm text-stone-500">
            <span>{{ $architect->user->email }}</span>
            <span>{{ $architect->city ?: 'Ville non renseignée' }}</span>
            <span>{{ $architect->experience_years }} ans d'expérience</span>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
            <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">Biographie</p>
            <p class="text-sm text-stone-600 leading-relaxed">{{ $architect->bio ?: 'Aucune biographie renseignée.' }}</p>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
            <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">Statistiques</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-stone-50 rounded-xl p-4 text-center">
                    <p class="font-serif text-2xl font-semibold text-green-800">{{ $architect->projects->count() }}</p>
                    <p class="text-xs text-stone-400 mt-1">Projets</p>
                </div>
                <div class="bg-stone-50 rounded-xl p-4 text-center">
                    <p class="font-serif text-2xl font-semibold text-green-800">{{ $architect->experience_years }}</p>
                    <p class="text-xs text-stone-400 mt-1">Ans exp.</p>
                </div>
            </div>
        </div>
    </div>

    @if($architect->projects->count())
        <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm mt-6">
            <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">Projets</p>
            <div class="grid grid-cols-3 gap-4">
                @foreach($architect->projects as $project)
                    <div class="border border-stone-100 rounded-xl overflow-hidden">
                        <div class="aspect-video bg-stone-100">
                            @if($project->images->first())
                                <img src="{{ Storage::url($project->images->first()->image_path) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-medium text-stone-800">{{ $project->title }}</p>
                            <p class="text-xs text-stone-400 mt-1">{{ $project->tags->pluck('name')->join(', ') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
