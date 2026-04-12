<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spacely — @yield('title', 'Espace Architecte')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen">

    {{-- ── Navbar ── --}}
    <nav class="sticky top-0 z-50 bg-stone-50/90 backdrop-blur border-b border-stone-200">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

            {{-- Logo + liens --}}
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="font-serif text-xl text-green-700 font-semibold">
                    Spacely
                </a>
                <div class="flex items-center gap-1">
                    <a href="{{ route('architect.dashboard') }}"
                       class="px-3 py-1.5 rounded-lg text-sm transition
                              {{ request()->routeIs('architect.dashboard') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('architect.profile.show') }}"
                       class="px-3 py-1.5 rounded-lg text-sm transition
                              {{ request()->routeIs('architect.profile.*') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Profil
                    </a>
                    <a href="{{ route('architect.projects.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm transition
                              {{ request()->routeIs('architect.projects.*') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Projets
                    </a>
                    <a href="{{ route('architect.availabilities.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm transition
                              {{ request()->routeIs('architect.availabilities.*') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Calendrier
                    </a>
                    <a href="{{ route('architect.bookings.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm transition
                              {{ request()->routeIs('architect.bookings.*') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Réservations
                    </a>
                    <a href="#"
                       class="px-3 py-1.5 rounded-lg text-sm transition
                              {{ request()->routeIs('architect.quotes.*') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Devis
                    </a>
                    <a href="#"
                       class="px-3 py-1.5 rounded-lg text-sm transition
                              {{ request()->routeIs('architect.blog.*') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Blog
                    </a>
                    <a href="#"
                       class="px-3 py-1.5 rounded-lg text-sm transition
                              {{ request()->routeIs('architect.messages.*') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Messages
                    </a>
                </div>
            </div>

            {{-- Avatar + déconnexion --}}
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center
                            text-green-800 text-sm font-medium border border-stone-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-stone-400 hover:text-red-500 transition">
                        Déconnexion
                    </button>
                </form>
            </div>

        </div>
    </nav>

    {{-- ── Flash messages ── --}}
    <div class="max-w-6xl mx-auto px-6 mt-5">

        @if(session('success'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200
                        text-green-800 text-sm rounded-xl px-4 py-3 mb-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-3">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-3">
                {{ $errors->first() }}
            </div>
        @endif

    </div>

    {{-- ── Contenu ── --}}
    <main class="max-w-6xl mx-auto px-6 py-8">
        @yield('content')
    </main>

</body>
</html>