@extends('layouts.auth')

@section('title', 'Assistant Dépenses - Vérification email')
@section('subtitle', 'Vérification de votre adresse email')

@section('auth-card')
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-md p-xl">
        <div class="flex flex-col items-center text-center mb-lg">
            <span class="material-symbols-outlined text-5xl text-primary mb-md" style="font-variation-settings: 'FILL' 1;">mark_email_unread</span>
            <h2 class="text-xl font-medium text-on-surface mb-sm">Vérifiez votre email</h2>
            <p class="text-sm text-on-surface-variant">
                Merci de vous être inscrit ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-lg p-md bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
                Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie.
            </div>
        @endif

        <div class="flex flex-col gap-sm">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="w-full py-md px-lg bg-primary text-on-primary rounded-xl text-base font-semibold hover:bg-primary-container active:scale-[0.98] transition-all shadow-sm flex items-center justify-center gap-sm" type="submit">
                    Renvoyer l'email de vérification
                    <span class="material-symbols-outlined text-[18px]">refresh</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full py-md px-lg border border-outline-variant text-on-surface-variant rounded-xl text-sm font-medium hover:bg-surface-variant transition-all flex items-center justify-center gap-sm" type="submit">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
@endsection
