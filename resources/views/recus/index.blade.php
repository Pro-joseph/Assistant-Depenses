@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Mes Reçus')
@section('page-title', 'Mes Reçus')
@section('search-placeholder', 'Rechercher un reçu...')
@section('search', true)

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-lg mb-xl">
        <div
            class="md:col-span-2 relative overflow-hidden rounded-xl bg-primary text-on-primary p-xl flex flex-col justify-between shadow-md">
            <div class="relative z-10">
                <h2 class="text-2xl font-semibold mb-xs">Bonjour, Marc</h2>
                <p class="text-sm text-on-primary/80">Vous avez 4 reçus en attente de traitement aujourd'hui.</p>
            </div>
            <div class="relative z-10 flex gap-sm mt-lg">
                <a href="{{ route('recus.create') }}"
                    class="inline-block bg-secondary-container text-on-secondary-container px-lg py-sm rounded-full font-bold text-xs hover:brightness-110 transition-all">Analyser
                    maintenant</a>
            </div>
        </div>
        <div
            class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between shadow-sm">
            <span class="text-outline text-xs uppercase tracking-wider font-medium">Total Traité</span>
            <div class="flex items-baseline gap-xs">
                <span class="text-4xl font-bold text-primary">124</span>
                <span class="text-primary/60 text-sm">reçus</span>
            </div>
            <div class="flex items-center text-green-600 gap-xs text-xs">
                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                <span>+12% vs mois dernier</span>
            </div>
        </div>
        <div
            class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between shadow-sm">
            <span class="text-outline text-xs uppercase tracking-wider font-medium">En attente</span>
            <div class="flex items-baseline gap-xs">
                <span class="text-4xl font-bold text-secondary-container">08</span>
                <span class="text-primary/60 text-sm">reçus</span>
            </div>
            <div class="flex items-center text-outline gap-xs text-xs">
                <span class="material-symbols-outlined text-[16px]">schedule</span>
                <span>Traitement en cours</span>
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div
            class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low/50">
            <h3 class="text-lg font-medium text-primary">Liste des Reçus</h3>
            <div class="flex gap-sm">
                <button
                    class="flex items-center gap-xs px-md py-sm border border-outline-variant rounded-lg text-sm hover:bg-surface-variant transition-all">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Filtrer
                </button>
                <button
                    class="flex items-center gap-xs px-md py-sm border border-outline-variant rounded-lg text-sm hover:bg-surface-variant transition-all">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Exporter
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-surface-container/30">
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Date</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Référence / Nom
                        </th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Statut</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Dépenses</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium text-end">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($recus as $recu)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-lg py-md text-sm text-on-surface">{{ $recu->created_at->format('d/m/Y') }}</td>
                            <td class="px-lg py-md text-sm font-medium text-primary">Reçu #{{ $recu->id }}</td>
                            <td class="px-lg py-md">
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
                            </td>
                            <td class="px-lg py-md text-sm text-on-surface-variant">{{ $recu->depenses_count }} dépenses
                            </td>
                            <td class="px-lg py-md text-end">
                                <div class="flex justify-end gap-md opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('recus.show', $recu->id) }}"
                                        class="text-primary hover:text-secondary flex items-center gap-xs text-xs font-medium uppercase">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span> Voir
                                    </a>
                                    <form method="POST" action="{{ route('recus.destroy', $recu->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce reçu ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-error hover:opacity-70 flex items-center gap-xs text-xs font-medium uppercase">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-lg py-xl text-center text-on-surface-variant">
                                <div class="flex flex-col items-center gap-sm">
                                    <span class="material-symbols-outlined text-[48px] text-outline">receipt_long</span>
                                    <p class="text-sm font-medium">Aucun reçu pour le moment</p>
                                    <a href="{{ route('recus.create') }}"
                                        class="text-primary text-xs underline hover:opacity-70">
                                        Soumettre votre premier reçu
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($recus->count())
            <div class="px-lg py-md border-t border-outline-variant bg-surface-container-low/30 flex justify-between items-center">
                <span class="text-xs text-on-surface-variant">Affichage de {{ $recus->firstItem() }} à {{ $recus->lastItem() }}
                    sur {{ $recus->total() }} reçus</span>
                <div class="flex gap-sm">
                    @if ($recus->onFirstPage())
                        <button class="p-xs rounded-lg hover:bg-surface-variant text-outline opacity-30" disabled>
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $recus->previousPageUrl() }}" class="p-xs rounded-lg hover:bg-surface-variant text-outline flex items-center">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    @foreach ($recus->getUrlRange(1, $recus->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium
                    {{ $page == $recus->currentPage()
                        ? 'bg-primary text-on-primary font-bold'
                        : 'hover:bg-surface-variant text-primary' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if ($recus->hasMorePages())
                        <a href="{{ $recus->nextPageUrl() }}" class="p-xs rounded-lg hover:bg-surface-variant text-outline flex items-center">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    @else
                        <button class="p-xs rounded-lg hover:bg-surface-variant text-outline opacity-30" disabled>
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
