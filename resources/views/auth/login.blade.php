<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spacely — Connexion</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sage: {
                            DEFAULT: '#4e7228',
                            dark: '#3d5a1e',
                            light: '#6b9a38',
                            pale: '#f0f4eb',
                        },
                        beige: {
                            DEFAULT: '#f5f3ee',
                            dark: '#e8e4dc',
                        },
                        charcoal: '#1e2016',
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans bg-beige text-charcoal min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Texture de fond (Gradients) -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_80%_60%_at_20%_10%,rgba(107,154,56,0.08)_0%,transparent_60%)]"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_60%_50%_at_80%_90%,rgba(78,114,40,0.07)_0%,transparent_55%)]"></div>
    </div>

    <!-- Navbar -->
    <nav class="relative z-10 flex items-center justify-between px-6 md:px-12 py-5 bg-beige/85 backdrop-blur-md border-b border-beige-dark">
        <a href="{{ url('/') }}" class="font-serif text-2xl font-semibold text-sage tracking-tight">Spacely</a>
        <a href="{{ route('register') }}" class="text-xs md:text-sm font-normal text-gray-500 hover:text-sage transition-colors">Créer un compte</a>
    </nav>

    <!-- Main -->
    <main class="flex-1 flex items-center justify-center px-6 py-12 relative z-10">
        <!-- Card -->
        <div class="bg-white rounded-[20px] p-8 md:p-12 w-full max-w-[440px] shadow-[0_24px_64px_rgba(0,0,0,0.13)] border border-black/5 animate-[fadeIn_0.5s_ease-out]">
            
            <div class="text-center mb-8">
                <div class="font-serif text-3xl font-semibold text-sage mb-1 tracking-tight">Spacely</div>
                <h1 class="font-serif text-2xl font-semibold text-charcoal mb-1">Welcome Back</h1>
                <p class="text-sm text-gray-400 font-light">Sign in to your digital atelier</p>
            </div>

            <!-- Erreurs globales -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-charcoal mb-2">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="name@example.com"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-beige border-1.5 border-beige-dark rounded-xl text-sm focus:bg-white focus:border-sage focus:ring-4 focus:ring-sage/10 transition-all outline-none @error('email') border-red-400 @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-xs text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="text-sm font-medium text-charcoal">Password</label>
                        <a href="#" class="text-xs text-sage-light hover:text-sage-dark transition-colors">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-beige border-1.5 border-beige-dark rounded-xl text-sm focus:bg-white focus:border-sage focus:ring-4 focus:ring-sage/10 transition-all outline-none @error('password') border-red-400 @enderror"
                            required
                        >
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors" onclick="togglePassword()">
                            <svg id="eye-icon" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember -->
                <label class="flex items-center gap-3 cursor-pointer group w-fit">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-sage focus:ring-sage cursor-pointer">
                    <span class="text-sm text-gray-500 group-hover:text-charcoal transition-colors">Remember this device</span>
                </label>

                <!-- Submit -->
                <button type="submit" class="group w-full py-3.5 bg-sage hover:bg-sage-dark text-white rounded-xl text-[15px] font-medium transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-sage/30 flex items-center justify-center gap-2 active:translate-y-0">
                    Sign In
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <p class="text-center mt-8 text-sm text-gray-400">
                Don't have an account? <a href="{{ route('register') }}" class="text-sage font-medium hover:text-sage-dark transition-colors">Create an account</a>
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 px-6 md:px-12 py-6 border-t border-beige-dark flex flex-col md:flex-row items-center justify-between gap-4 bg-beige/85">
        <div>
            <div class="font-serif font-semibold text-sage text-lg">Spacely</div>
            <div class="text-[11px] text-gray-400">© 2024 Spacely Digital Atelier. All rights reserved.</div>
        </div>
        <div class="flex gap-6 text-[11px] text-gray-400">
            <a href="#" class="hover:text-sage transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-sage transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-sage transition-colors">Help Center</a>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
            }
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Custom border width pour Tailwind */
        .border-1.5 { border-width: 1.5px; }
    </style>
</body>
</html>