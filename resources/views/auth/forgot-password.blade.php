@extends('layouts.auth')

@section('title', 'Assistant Dépenses - Mot de passe oublié')
@section('subtitle', 'Réinitialisation du mot de passe')

@section('auth-card')
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-md p-xl">
        <h2 class="text-xl font-medium text-on-surface mb-lg">Mot de passe oublié</h2>

        <p class="text-sm text-on-surface-variant mb-lg">
            Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
        </p>

        @if (session('status'))
            <div class="mb-lg p-md bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="space-y-lg">
                <div class="space-y-xs">
                    <label class="text-xs text-on-surface-variant uppercase tracking-wider font-medium" for="email">Email</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">mail</span>
                        <input class="w-full pl-xxl pr-md py-md bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary-container focus:border-primary transition-all outline-none" id="email" name="email" placeholder="nom@exemple.com" required type="email" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="text-sm text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <button class="w-full py-md px-lg bg-primary text-on-primary rounded-xl text-base font-semibold hover:bg-primary-container active:scale-[0.98] transition-all shadow-sm flex items-center justify-center gap-sm" type="submit">
                    Envoyer le lien
                    <span class="material-symbols-outlined text-[18px]">send</span>
                </button>
            </div>
        </form>

        <div class="mt-xl pt-lg border-t border-outline-variant text-center">
            <a class="text-primary text-sm font-medium hover:underline transition-colors flex items-center justify-center gap-xs" href="{{ route('login') }}">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Retour à la connexion
            </a>
        </div>
    </div>
@endsection
