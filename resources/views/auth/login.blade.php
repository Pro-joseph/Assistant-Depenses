<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assistant Dépenses - Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface min-h-screen flex flex-col relative overflow-hidden">
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-primary-container rounded-full blur-[120px] opacity-10"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-secondary-container rounded-full blur-[100px] opacity-10"></div>
    </div>

    <main class="flex-grow flex items-center justify-center p-lg relative z-10">
        <div class="w-full max-w-[440px]">
            <div class="flex flex-col items-center mb-xl">
                <div class="w-16 h-16 bg-primary flex items-center justify-center rounded-xl shadow-md mb-md">
                    <span class="material-symbols-outlined text-on-primary text-[32px]" style="font-variation-settings: 'FILL' 1;">payments</span>
                </div>
                <h1 class="text-2xl font-bold text-primary">Assistant Dépenses</h1>
                <p class="text-sm text-on-surface-variant mt-xs">Gérez votre budget en toute confiance</p>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-md p-xl">
                <h2 class="text-xl font-medium text-on-surface mb-lg">Bienvenue</h2>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-lg">
                        <div class="space-y-xs">
                            <label class="text-xs text-on-surface-variant uppercase tracking-wider font-medium" for="email">Email</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">mail</span>
                                <input class="w-full pl-xxl pr-md py-md bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary-container focus:border-primary transition-all outline-none" id="email" name="email" placeholder="nom@entreprise.com" required type="email" value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="text-sm text-error mt-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-xs">
                            <div class="flex justify-between items-center">
                                <label class="text-xs text-on-surface-variant uppercase tracking-wider font-medium" for="password">Mot de passe</label>
                                @if (Route::has('password.request'))
                                    <a class="text-xs text-primary hover:underline" href="{{ route('password.request') }}">Oublié ?</a>
                                @endif
                            </div>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
                                <input class="w-full pl-xxl pr-md py-md bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary-container focus:border-primary transition-all outline-none" id="password" name="password" placeholder="••••••••" required type="password">
                            </div>
                            @error('password')
                                <p class="text-sm text-error mt-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center space-x-sm">
                            <input class="w-4 h-4 text-primary bg-surface-container-low border-outline-variant rounded focus:ring-primary" id="remember" name="remember" type="checkbox">
                            <label class="text-sm text-on-surface-variant select-none" for="remember">Se souvenir de moi</label>
                        </div>

                        <button class="w-full py-md px-lg bg-primary text-on-primary rounded-xl text-base font-semibold hover:bg-primary-container active:scale-[0.98] transition-all shadow-sm flex items-center justify-center gap-sm" type="submit">
                            Se connecter
                            <span class="material-symbols-outlined text-[18px]">login</span>
                        </button>
                    </div>
                </form>

                <div class="mt-xl pt-lg border-t border-outline-variant text-center">
                    <p class="text-sm text-on-surface-variant">
                        Nouveau sur la plateforme ?
                        <a class="text-primary font-bold hover:underline transition-colors ml-xs" href="{{ route('register') }}">Créer un compte</a>
                    </p>
                </div>
            </div>

            <div class="mt-lg flex justify-center items-center gap-lg opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="flex items-center gap-xs">
                    <span class="material-symbols-outlined text-outline text-sm">verified_user</span>
                    <span class="text-xs text-outline">Sécurisé par SSL</span>
                </div>
                <div class="flex items-center gap-xs">
                    <span class="material-symbols-outlined text-outline text-sm">gpp_good</span>
                    <span class="text-xs text-outline">Conforme RGPD</span>
                </div>
            </div>
        </div>
    </main>

    <footer class="p-lg text-center relative z-10">
        <p class="text-xs text-outline">© {{ date('Y') }} Assistant Dépenses. Tous droits réservés.</p>
    </footer>
</body>
</html>