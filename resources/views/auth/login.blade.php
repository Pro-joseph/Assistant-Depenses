@extends('layouts.auth')

@section('title', 'Assistant Dépenses - Connexion')
@section('subtitle', 'Gérez votre budget en toute confiance')

@section('auth-card')
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
@endsection
