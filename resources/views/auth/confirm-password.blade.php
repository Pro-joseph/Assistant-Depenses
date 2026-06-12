@extends('layouts.auth')

@section('title', 'Assistant Dépenses - Confirmer le mot de passe')
@section('subtitle', 'Zone sécurisée')

@section('auth-card')
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-md p-xl">
        <div class="flex flex-col items-center text-center mb-lg">
            <span class="material-symbols-outlined text-4xl text-primary mb-md" style="font-variation-settings: 'FILL' 1;">shield</span>
            <h2 class="text-xl font-medium text-on-surface mb-sm">Confirmez votre mot de passe</h2>
            <p class="text-sm text-on-surface-variant">
                Cette action est sécurisée. Veuillez confirmer votre mot de passe pour continuer.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="space-y-lg">
                <div class="space-y-xs">
                    <label class="text-xs text-on-surface-variant uppercase tracking-wider font-medium" for="password">Mot de passe</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
                        <input class="w-full pl-xxl pr-md py-md bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary-container focus:border-primary transition-all outline-none" id="password" name="password" placeholder="••••••••" required type="password" autocomplete="current-password">
                    </div>
                    @error('password')
                        <p class="text-sm text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <button class="w-full py-md px-lg bg-primary text-on-primary rounded-xl text-base font-semibold hover:bg-primary-container active:scale-[0.98] transition-all shadow-sm flex items-center justify-center gap-sm" type="submit">
                    Confirmer
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                </button>
            </div>
        </form>

        <div class="mt-xl pt-lg border-t border-outline-variant text-center">
            <a class="text-primary text-sm font-medium hover:underline transition-colors flex items-center justify-center gap-xs" href="{{ route('recus.index') }}">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Retour à l'accueil
            </a>
        </div>
    </div>
@endsection
