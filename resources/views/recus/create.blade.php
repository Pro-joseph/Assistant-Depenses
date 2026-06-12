@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Ajouter un Reçu')
@section('page-title', 'Ajouter un Reçu')
@section('search', false)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
        <section class="lg:col-span-7 flex flex-col gap-lg">
            <form method="POST" action="{{ route('recus.store') }}" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                @csrf
                <div class="mb-md">
                    <h3 class="text-lg font-medium text-primary mb-xs">Détails du reçu</h3>
                    <p class="text-sm text-on-surface-variant">Collez le texte brut de votre reçu ci-dessous pour une extraction automatique.</p>
                </div>
                <div class="mb-md">
                    <textarea class="w-full min-h-[300px] p-md bg-surface-container-low border border-outline-variant rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-sm resize-none" name="texte_brut" placeholder="Ex: CARREFOUR CITY
24 RUE DE LA PAIX
TOTAL: 42.50€...">{{ old('texte_brut') }}</textarea>
                </div>
                @error('texte_brut')
                    <p class="text-sm text-error mt-sm">{{ $message }}</p>
                @enderror
                <div class="mt-lg flex items-center justify-between">
                    <div class="flex gap-sm">
                        <button class="px-md py-sm border border-primary text-primary rounded-lg text-xs font-medium hover:bg-primary/5 transition-colors" type="reset">
                            Effacer
                        </button>
                    </div>
                    <button class="px-xl py-sm bg-primary text-on-primary rounded-lg text-base font-semibold hover:bg-primary-container shadow-md active:scale-95 transition-all flex items-center gap-sm" type="submit">
                        Soumettre
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </div>
            </form>
        </section>

        <section class="lg:col-span-5 flex flex-col gap-lg">
            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                <h3 class="text-lg font-medium text-primary mb-md">Document Numérique</h3>
                <div id="uploadZone" class="border-2 border-dashed border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center text-center cursor-pointer hover:border-primary hover:bg-primary-fixed/20 transition-all group relative overflow-hidden h-64">
                    <input accept="image/png,image/jpeg,image/webp" class="hidden" id="fileInput" name="image" type="file">
                    <div id="uploadPlaceholder">
                        <span class="material-symbols-outlined text-5xl text-outline group-hover:text-primary transition-colors mb-sm">upload_file</span>
                        <p class="text-lg text-on-surface mb-xs">Sélectionnez un fichier</p>
                        <p class="text-sm text-on-surface-variant">PNG, JPG ou WebP (max 10MB)</p>
                    </div>
                    <div id="uploadPreview" class="hidden w-full">
                        <img id="previewImage" class="max-h-48 mx-auto rounded-lg border border-outline-variant mb-sm" src="#" alt="Aperçu">
                        <p id="fileInfo" class="text-sm text-on-surface-variant mb-sm"></p>
                        <button type="button" id="removeSelection" class="text-xs text-error hover:underline">Supprimer la sélection</button>
                    </div>
                </div>
                @error('image')
                    <p class="text-sm text-error mt-sm">{{ $message }}</p>
                @enderror
                <div class="mt-lg space-y-md">
                    <div class="flex items-center gap-md p-md bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined text-primary">info</span>
                        <p class="text-sm text-on-surface-variant">L'ajout d'une image permet une validation plus précise par notre IA.</p>
                    </div>
                </div>
            </div>

            <div class="bg-primary-container text-on-primary-container rounded-xl p-lg border border-primary/20 shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-10">
                    <span class="material-symbols-outlined text-[120px]">account_balance_wallet</span>
                </div>
                <h4 class="text-lg font-medium mb-sm">Conseils</h4>
                <ul class="space-y-sm text-sm">
                    <li class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-sm mt-1">check_circle</span>
                        Assurez-vous que le montant total est visible.
                    </li>
                    <li class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-sm mt-1">check_circle</span>
                        La date et le nom du marchand sont essentiels.
                    </li>
                </ul>
            </div>
        </section>
    </div>

@push('scripts')
    @include('recus._upload_js')
@endpush
@endsection
