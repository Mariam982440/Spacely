@extends('layouts.admin')
@section('title', 'Architectes')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Architectes</h1>
            <p class="text-stone-400 text-sm mt-1">{{ $architects->total() }} profil(s)</p>
        </div>
    </div>

    <div class="flex items-center gap-2 mb-6">
        @foreach(['' => 'Tous', 'pending' => 'En attente', 'verified' => 'Validés'] as $value => $label)
            <a href="{{ route('admin.architects.index', $value ? ['status' => $value] : []) }}"
               class="px-4 py-1.5 rounded-xl text-sm transition {{ request('status') === $value ? 'bg-green-700 text-white' : 'bg-white border border-stone-200 text-stone-600 hover:bg-stone-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden mb-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-stone-100">
                    <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Architecte</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Ville</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Projets</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Statut</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-stone-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($architects as $profile)
                    <tr class="border-b border-stone-50 hover:bg-stone-50 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-stone-800">{{ $profile->user->name }}</p>
                            <p class="text-xs text-stone-400">{{ $profile->user->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-600">{{ $profile->city ?: '-' }}</td>
                        <td class="px-6 py-4 text-sm text-stone-600">{{ $profile->projects_count }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2.5 py-1 rounded-full {{ $profile->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $profile->is_verified ? 'Validé' : 'En attente' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.architects.show', $profile) }}" class="px-3 py-1.5 bg-stone-100 text-stone-600 text-xs font-medium rounded-lg hover:bg-stone-200 transition">
                                    Voir
                                </a>
                                @if(!$profile->is_verified)
                                    <form method="POST" action="{{ route('admin.architects.approve', $profile) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition">
                                            Valider
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.architects.suspend', $profile) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-1.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-lg hover:bg-yellow-200 transition">
                                            Mettre en attente
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-stone-400">Aucun profil architecte.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $architects->links() }}
@endsection
