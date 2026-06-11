@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Détail du Reçu')
@section('page-title', 'Détail du Reçu #' . $recu->id)
@section('search', false)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
        <section class="lg:col-span-7 flex flex-col gap-lg">
            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                <div class="flex justify-between items-start mb-md">
                    <div>
                        <h3 class="text-lg font-medium text-primary">Informations</h3>
                        <p class="text-xs text-on-surface-variant mt-xs">{{ $recu->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @switch($recu->statut)
                        @case('en_attente')
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-yellow-100 text-yellow-700 border-yellow-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 me-2"></span> En attente
                            </span>
                        @break
                        @case('traite')
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-green-100 text-green-700 border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 me-2"></span> Traité
                            </span>
                        @break
                        @case('echoue')
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-red-100 text-red-700 border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 me-2"></span> Échoué
                            </span>
                        @break
                        @default
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-gray-100 text-gray-700 border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500 me-2"></span> {{ $recu->statut }}
                            </span>
                    @endswitch
                </div>

                @if ($recu->total_estime)
                    <div class="flex items-baseline gap-xs mb-md p-md bg-surface-container-low rounded-lg">
                        <span class="text-sm text-on-surface-variant">Total estimé :</span>
                        <span class="text-2xl font-bold text-primary">{{ number_format($recu->total_estime, 2) }} {{ $recu->devise ?? 'MAD' }}</span>
                    </div>
                @endif

                @if ($recu->texte_brut)
                    <div class="mb-md">
                        <h4 class="text-sm font-medium text-on-surface mb-sm">Texte brut</h4>
                        <div class="p-md bg-surface-container-low rounded-lg border border-outline-variant">
                            <pre class="text-sm text-on-surface whitespace-pre-wrap font-sans">{{ $recu->texte_brut }}</pre>
                        </div>
                    </div>
                @endif
            </div>

            @if ($recu->depenses->count())
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="px-lg py-md border-b border-outline-variant">
                        <h3 class="text-lg font-medium text-primary">Dépenses extraites</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-start border-collapse">
                            <thead>
                                <tr class="bg-surface-container/30">
                                    <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Libellé</th>
                                    <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Quantité</th>
                                    <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Prix unitaire</th>
                                    <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Catégorie</th>
                                    <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @foreach ($recu->depenses as $depense)
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="px-lg py-md text-sm text-on-surface">{{ $depense->libelle }}</td>
                                        <td class="px-lg py-md text-sm text-on-surface-variant">{{ $depense->quantite }}</td>
                                        <td class="px-lg py-md text-sm text-on-surface-variant">{{ number_format($depense->prix_unitaire, 2) }}</td>
                                        <td class="px-lg py-md">
                                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium bg-surface-variant text-on-surface-variant">
                                                {{ $depense->categorie }}
                                            </span>
                                        </td>
                                        <td class="px-lg py-md text-sm text-on-surface font-medium text-end">{{ number_format($depense->quantite * $depense->prix_unitaire, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>

        <section class="lg:col-span-5 flex flex-col gap-lg">
            @if ($recu->image_path)
                <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                    <h3 class="text-lg font-medium text-primary mb-md">Document</h3>
                    <img src="{{ Storage::url($recu->image_path) }}" alt="Reçu" class="w-full rounded-lg border border-outline-variant">
                </div>
            @endif

            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                <h3 class="text-lg font-medium text-primary mb-md">Actions</h3>
                <div class="flex flex-col gap-sm">
                    <a href="{{ route('recus.edit', $recu->id) }}"
                        class="flex items-center justify-center gap-sm w-full px-lg py-sm bg-primary text-on-primary rounded-lg text-sm font-semibold hover:bg-primary-container shadow-md transition-all">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('recus.destroy', $recu->id) }}" class="w-full" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce reçu ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center justify-center gap-sm w-full px-lg py-sm border border-error text-error rounded-lg text-sm font-medium hover:bg-error/5 transition-all">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            Supprimer
                        </button>
                    </form>
                    <a href="{{ route('recus.index') }}"
                        class="flex items-center justify-center gap-sm w-full px-lg py-sm border border-outline-variant text-on-surface-variant rounded-lg text-sm font-medium hover:bg-surface-variant transition-all">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
