<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spacely - @yield('title', 'Administration')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
    @include('partials.dark-ui-theme')
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen">
    <nav class="sticky top-0 z-50 bg-stone-50/90 backdrop-blur border-b border-stone-200">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('admin.dashboard') }}" class="font-serif text-xl text-green-700 font-semibold">
                    Spacely Admin
                </a>
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-3 py-1.5 rounded-lg text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Tableau de bord
                    </a>
                    <a href="{{ route('admin.architects.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm transition {{ request()->routeIs('admin.architects.*') ? 'bg-green-100 text-green-800 font-medium' : 'text-stone-500 hover:bg-stone-100' }}">
                        Architectes
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @include('partials.theme-toggle')
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-stone-400 hover:text-red-500 transition">
                    Déconnexion
                </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 mt-5">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3 mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-3">
                {{ $errors->first() }}
            </div>
        @endif
    </div>

    <main class="max-w-6xl mx-auto px-6 py-8">
        @yield('content')
    </main>
</body>
</html>
