@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Modifier le Reçu')
@section('page-title', 'Modifier le Reçu #' . $recu->id)
@section('search', false)

@section('content')
    @if (session('success'))
        <div class="mb-lg p-md bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg flex items-center gap-md">
            <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
        <section class="lg:col-span-7 flex flex-col gap-lg">
            <form method="POST" action="{{ route('recus.update', $recu->id) }}" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm flex flex-col h-full">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-md p-md bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-md">
                    <h3 class="text-lg font-medium text-primary mb-xs">Détails du reçu</h3>
                    <p class="text-sm text-on-surface-variant">Modifiez le texte brut de votre reçu ci-dessous.</p>
                </div>

                <div class="flex-1 relative group mb-lg">
                    <label for="texte_brut" class="block text-sm font-medium text-on-surface mb-sm">Texte brut du reçu</label>
                    <textarea id="texte_brut" name="texte_brut"
                        class="w-full min-h-[300px] p-md bg-surface-container-low border border-outline-variant rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-sm resize-none"
                        placeholder="Collez le texte brut de votre reçu ici...">{{ old('texte_brut', $recu->texte_brut) }}</textarea>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex gap-sm">
                        <a href="{{ route('recus.show', $recu->id) }}"
                            class="px-md py-sm border border-outline-variant text-on-surface-variant rounded-lg text-xs font-medium hover:bg-surface-variant transition-colors">
                            Annuler
                        </a>
                    </div>
                    <button type="submit"
                        class="px-xl py-sm bg-primary text-on-primary rounded-lg text-base font-semibold hover:bg-primary-container shadow-md active:scale-95 transition-all flex items-center gap-sm">
                        Enregistrer
                        <span class="material-symbols-outlined">save</span>
                    </button>
                </div>
            </form>
        </section>

        <section class="lg:col-span-5 flex flex-col gap-lg">
            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                <h3 class="text-lg font-medium text-primary mb-md">Document Numérique</h3>

                @if ($recu->image_path)
                    <div class="mb-md">
                        <img src="{{ Storage::url($recu->image_path) }}" alt="Reçu actuel" class="w-full rounded-lg border border-outline-variant mb-sm">
                        <p class="text-xs text-on-surface-variant">Image actuelle. Téléchargez un nouveau fichier pour la remplacer.</p>
                    </div>
                    <div class="mb-md flex items-center gap-md p-md bg-error/5 border border-error/20 rounded-lg">
                        <input type="checkbox" id="removeImage" name="supprimer_image" value="1" class="rounded border-error text-error focus:ring-error">
                        <label for="removeImage" class="text-sm text-error cursor-pointer">Supprimer l'image</label>
                    </div>
                @endif

                <div id="uploadZone" class="border-2 border-dashed border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center text-center cursor-pointer hover:border-primary hover:bg-primary-fixed/20 transition-all group relative overflow-hidden">
                    <input accept="image/png,image/jpeg,image/webp" class="hidden" id="fileInput" name="image" type="file">
                    <div id="uploadPlaceholder">
                        <span class="material-symbols-outlined text-5xl text-outline group-hover:text-primary transition-colors mb-sm">upload_file</span>
                        <p class="text-lg text-on-surface mb-xs">{{ $recu->image_path ? "Remplacer l'image" : 'Sélectionnez un fichier' }}</p>
                        <p class="text-sm text-on-surface-variant">PNG, JPG ou WebP (max 10MB)</p>
                    </div>
                    <div id="uploadPreview" class="hidden w-full">
                        <img id="previewImage" class="max-h-48 mx-auto rounded-lg border border-outline-variant mb-sm" src="#" alt="Aperçu">
                        <p id="fileInfo" class="text-sm text-on-surface-variant mb-sm"></p>
                        <button type="button" id="removeSelection" class="text-xs text-error hover:underline">Supprimer la sélection</button>
                    </div>
                </div>

                <div class="mt-lg space-y-md">
                    <div class="flex items-center gap-md p-md bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined text-primary">info</span>
                        <p class="text-sm text-on-surface-variant">Laissez les champs que vous ne souhaitez pas modifier vides.</p>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                <h3 class="text-lg font-medium text-primary mb-md">Statut actuel</h3>
                @include('recus._statut_badge', ['statut' => $recu->statut->value])
                <p class="text-xs text-on-surface-variant mt-sm">Le statut repasse à "En attente" si vous modifiez le texte brut.</p>
            </div>
        </section>
    </div>
@push('scripts')
    @include('recus._upload_js')
@endpush
@endsection
