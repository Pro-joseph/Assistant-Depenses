@extends('layouts.auth')

@section('title', 'Inscription - Assistant Dépenses')

@section('auth-header')
    <header class="w-full py-lg flex justify-center items-center">
        <div class="flex items-center gap-sm">
            <span class="material-symbols-outlined text-primary text-2xl">account_balance_wallet</span>
            <h1 class="text-primary text-2xl font-bold tracking-tight">Assistant Dépenses</h1>
        </div>
    </header>
@endsection

@section('auth-card')
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl shadow-sm">
        <div class="text-center mb-xl">
            <h2 class="text-2xl font-semibold text-on-surface mb-xs">Créer un compte</h2>
            <p class="text-sm text-on-surface-variant">Rejoignez Assistant Dépenses pour une gestion de budget simplifiée.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="space-y-lg">
                <div class="space-y-xs">
                    <label class="block text-xs text-outline font-medium uppercase tracking-wider" for="name">Nom complet</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">person</span>
                        <input class="w-full pl-xxl pr-md py-sm rounded-lg border border-outline-variant bg-surface-container-low focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-sm outline-none" id="name" name="name" placeholder="Jean Dupont" type="text" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <p class="text-sm text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-xs">
                    <label class="block text-xs text-outline font-medium uppercase tracking-wider" for="email">Adresse Email</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">mail</span>
                        <input class="w-full pl-xxl pr-md py-sm rounded-lg border border-outline-variant bg-surface-container-low focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-sm outline-none" id="email" name="email" placeholder="nom@exemple.com" type="email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <p class="text-sm text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-xs">
                    <label class="block text-xs text-outline font-medium uppercase tracking-wider" for="password">Mot de passe</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">lock</span>
                        <input class="w-full pl-xxl pr-md py-sm rounded-lg border border-outline-variant bg-surface-container-low focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-sm outline-none" id="password" name="password" placeholder="••••••••" type="password" required>
                    </div>
                    @error('password')
                        <p class="text-sm text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-xs">
                    <label class="block text-xs text-outline font-medium uppercase tracking-wider" for="password_confirmation">Confirmer le mot de passe</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">lock_reset</span>
                        <input class="w-full pl-xxl pr-md py-sm rounded-lg border border-outline-variant bg-surface-container-low focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-sm outline-none" id="password_confirmation" name="password_confirmation" placeholder="••••••••" type="password" required>
                    </div>
                </div>

                <div class="flex items-start gap-sm mt-md">
                    <input class="mt-1 rounded border-outline-variant text-primary focus:ring-primary h-4 w-4" id="terms" type="checkbox" required>
                    <label class="text-sm text-on-surface-variant" for="terms">
                        J'accepte les <a class="text-primary font-medium hover:underline" href="#">conditions d'utilisation</a> et la <a class="text-primary font-medium hover:underline" href="#">politique de confidentialité</a>.
                    </label>
                </div>

                <button class="w-full bg-primary text-white py-sm px-lg rounded-xl text-lg font-semibold hover:bg-primary-container transition-colors shadow-sm active:scale-[0.98] duration-150" type="submit">
                    S'inscrire
                </button>
            </div>
        </form>

        <div class="mt-xl pt-lg border-t border-outline-variant text-center">
            <p class="text-sm text-on-surface-variant">
                Vous avez déjà un compte ?
                <a class="text-primary font-bold hover:underline ml-xs" href="{{ route('login') }}">Se connecter</a>
            </p>
        </div>
    </div>
@endsection

@section('auth-badges')
@endsection

@section('auth-footer')
    <footer class="w-full py-lg text-center opacity-50 select-none">
        <p class="text-xs text-outline">© {{ date('Y') }} Assistant Dépenses — Gestion Professionnelle des Finances</p>
    </footer>
@endsection
