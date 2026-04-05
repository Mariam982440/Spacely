<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spacely — Créer un compte</title>
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

    <!-- Texture de fond -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_80%_60%_at_20%_10%,rgba(107,154,56,0.08)_0%,transparent_60%)]"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_60%_50%_at_80%_90%,rgba(78,114,40,0.07)_0%,transparent_55%)]"></div>
    </div>

    <!-- Navbar -->
    <nav class="relative z-10 flex items-center justify-between px-6 md:px-12 py-5 bg-beige/85 backdrop-blur-md border-b border-beige-dark">
        <a href="{{ url('/') }}" class="font-serif text-2xl font-semibold text-sage tracking-tight">Spacely</a>
        <a href="{{ route('login') }}" class="text-xs md:text-sm font-normal text-gray-500 hover:text-sage transition-colors">Déjà inscrit ? Se connecter</a>
    </nav>

    <!-- Main -->
    <main class="flex-1 flex items-center justify-center px-6 py-12 relative z-10">
        <!-- Card d'inscription -->
        <div class="bg-white rounded-[20px] p-8 md:p-12 w-full max-w-[500px] shadow-[0_24px_64px_rgba(0,0,0,0.13)] border border-black/5 animate-[fadeIn_0.5s_ease-out]">
            
            <div class="text-center mb-8">
                <div class="font-serif text-3xl font-semibold text-sage mb-1 tracking-tight">Spacely</div>
                <h1 class="font-serif text-2xl font-semibold text-charcoal mb-1">Rejoignez l'atelier</h1>
                <p class="text-sm text-gray-400 font-light">Commencez votre voyage dans le design d'intérieur</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Choix du Rôle (Visual Selection) -->
                <div>
                    <label class="block text-sm font-medium text-charcoal mb-3">Quel est votre profil ?</label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Option Client -->
                        <label class="relative flex flex-col items-center justify-center p-4 bg-beige border-1.5 border-beige-dark rounded-xl cursor-pointer hover:border-sage/40 transition-all group">
                            <input type="radio" name="role" value="client" class="sr-only peer" {{ old('role') == 'client' ? 'checked' : '' }} required>
                            <div class="w-full h-full absolute inset-0 rounded-xl peer-checked:border-2 peer-checked:border-sage peer-checked:bg-sage/5 transition-all"></div>
                            <svg class="w-6 h-6 mb-2 text-gray-400 peer-checked:text-sage transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-xs font-medium text-gray-500 peer-checked:text-sage">Je suis un Client</span>
                        </label>

                        <!-- Option Architecte -->
                        <label class="relative flex flex-col items-center justify-center p-4 bg-beige border-1.5 border-beige-dark rounded-xl cursor-pointer hover:border-sage/40 transition-all group">
                            <input type="radio" name="role" value="architect" class="sr-only peer" {{ old('role') == 'architect' ? 'checked' : '' }} required>
                            <div class="w-full h-full absolute inset-0 rounded-xl peer-checked:border-2 peer-checked:border-sage peer-checked:bg-sage/5 transition-all"></div>
                            <svg class="w-6 h-6 mb-2 text-gray-400 peer-checked:text-sage transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-xs font-medium text-gray-500 peer-checked:text-sage">Je suis Architecte</span>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-xs text-red-600 mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom Complet -->
                <div>
                    <label for="name" class="block text-sm font-medium text-charcoal mb-2">Nom Complet</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Jean Dupont" 
                        class="w-full px-4 py-3 bg-beige border-1.5 border-beige-dark rounded-xl text-sm focus:bg-white focus:border-sage focus:ring-4 focus:ring-sage/10 transition-all outline-none @error('name') border-red-400 @enderror" required>
                    @error('name')
                        <p class="text-xs text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-charcoal mb-2">Adresse Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="jean@exemple.com" 
                        class="w-full px-4 py-3 bg-beige border-1.5 border-beige-dark rounded-xl text-sm focus:bg-white focus:border-sage focus:ring-4 focus:ring-sage/10 transition-all outline-none @error('email') border-red-400 @enderror" required>
                    @error('email')
                        <p class="text-xs text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passwords Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-charcoal mb-2">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" 
                            class="w-full px-4 py-3 bg-beige border-1.5 border-beige-dark rounded-xl text-sm focus:bg-white focus:border-sage focus:ring-4 focus:ring-sage/10 transition-all outline-none @error('password') border-red-400 @enderror" required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-charcoal mb-2">Confirmation</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" 
                            class="w-full px-4 py-3 bg-beige border-1.5 border-beige-dark rounded-xl text-sm focus:bg-white focus:border-sage focus:ring-4 focus:ring-sage/10 transition-all outline-none" required>
                    </div>
                </div>
                @error('password')
                    <p class="text-xs text-red-600 mt-[-0.5rem] ml-1">{{ $message }}</p>
                @enderror

                <!-- Terms -->
                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox" name="terms" class="mt-1 w-4 h-4 rounded border-gray-300 text-sage focus:ring-sage cursor-pointer" required>
                    <span class="text-xs text-gray-500 leading-relaxed group-hover:text-charcoal transition-colors">
                        J'accepte les <a href="#" class="text-sage underline underline-offset-2">Conditions d'utilisation</a> et la <a href="#" class="text-sage underline underline-offset-2">Politique de confidentialité</a> de Spacely.
                    </span>
                </label>

                <!-- Bouton Submit -->
                <button type="submit" class="group w-full py-3.5 bg-sage hover:bg-sage-dark text-white rounded-xl text-[15px] font-medium transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-sage/30 flex items-center justify-center gap-2 active:translate-y-0">
                    Créer mon compte
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                </button>
            </form>

            <p class="text-center mt-8 text-sm text-gray-400">
                Vous avez déjà un compte ? <a href="{{ route('login') }}" class="text-sage font-medium hover:text-sage-dark transition-colors">Se connecter</a>
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 px-6 md:px-12 py-6 border-t border-beige-dark flex flex-col md:flex-row items-center justify-between gap-4 bg-beige/85">
        <div>
            <div class="font-serif font-semibold text-sage text-lg">Spacely</div>
            <div class="text-[11px] text-gray-400">© 2024 Spacely Digital Atelier. Tous droits réservés.</div>
        </div>
        <div class="flex gap-6 text-[11px] text-gray-400">
            <a href="#" class="hover:text-sage transition-colors">Confidentialité</a>
            <a href="#" class="hover:text-sage transition-colors">Conditions</a>
            <a href="#" class="hover:text-sage transition-colors">Aide</a>
        </div>
    </footer>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .border-1.5 { border-width: 1.5px; }
        
        input:focus + div {
            box-shadow: 0 0 0 4px rgba(78, 114, 40, 0.1);
        }
    </style>
</body>
</html>
