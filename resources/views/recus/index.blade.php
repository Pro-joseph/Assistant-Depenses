@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Mes Reçus')
@section('page-title', 'Mes Reçus')
@section('search-placeholder', 'Rechercher un reçu...')
@section('search', true)

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-lg mb-xl">
        <div class="md:col-span-2 relative overflow-hidden rounded-xl bg-primary text-on-primary p-xl flex flex-col justify-between shadow-md">
            <div class="relative z-10">
                <h2 class="text-2xl font-semibold mb-xs">Bonjour, Marc</h2>
                <p class="text-sm text-on-primary/80">Vous avez 4 reçus en attente de traitement aujourd'hui.</p>
            </div>
            <div class="relative z-10 flex gap-sm mt-lg">
                <a href="{{ route('recus.create') }}" class="inline-block bg-secondary-container text-on-secondary-container px-lg py-sm rounded-full font-bold text-xs hover:brightness-110 transition-all">Analyser maintenant</a>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between shadow-sm">
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
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between shadow-sm">
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
        <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low/50">
            <h3 class="text-lg font-medium text-primary">Liste des Reçus</h3>
            <div class="flex gap-sm">
                <button class="flex items-center gap-xs px-md py-sm border border-outline-variant rounded-lg text-sm hover:bg-surface-variant transition-all">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Filtrer
                </button>
                <button class="flex items-center gap-xs px-md py-sm border border-outline-variant rounded-lg text-sm hover:bg-surface-variant transition-all">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Exporter
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container/30">
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Date</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Référence / Nom</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Statut</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Dépenses</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md text-sm text-on-surface">15 Oct 2023</td>
                        <td class="px-lg py-md text-sm font-medium text-primary">Restaurant Le Gourmet</td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-green-100 text-green-700 border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> Traité
                            </span>
                        </td>
                        <td class="px-lg py-md text-sm text-on-surface-variant">2 dépenses</td>
                        <td class="px-lg py-md text-right">
                            <div class="flex justify-end gap-md opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('recus.show', 1) }}" class="text-primary hover:text-secondary flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span> Voir
                                </a>
                                <button class="text-error hover:opacity-70 flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md text-sm text-on-surface">14 Oct 2023</td>
                        <td class="px-lg py-md text-sm font-medium text-primary">Apple Store Paris</td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-amber-100 text-amber-700 border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span> En attente
                            </span>
                        </td>
                        <td class="px-lg py-md text-sm text-on-surface-variant">1 dépense</td>
                        <td class="px-lg py-md text-right">
                            <div class="flex justify-end gap-md opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('recus.show', 2) }}" class="text-primary hover:text-secondary flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span> Voir
                                </a>
                                <button class="text-error hover:opacity-70 flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md text-sm text-on-surface">12 Oct 2023</td>
                        <td class="px-lg py-md text-sm font-medium text-primary">Station Service Shell</td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-red-100 text-red-700 border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span> Échoué
                            </span>
                        </td>
                        <td class="px-lg py-md text-sm text-on-surface-variant">0 dépense</td>
                        <td class="px-lg py-md text-right">
                            <div class="flex justify-end gap-md opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="text-primary hover:text-secondary flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">autorenew</span> Réessayer
                                </button>
                                <button class="text-error hover:opacity-70 flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md text-sm text-on-surface">10 Oct 2023</td>
                        <td class="px-lg py-md text-sm font-medium text-primary">Uber Trip - Business</td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-green-100 text-green-700 border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> Traité
                            </span>
                        </td>
                        <td class="px-lg py-md text-sm text-on-surface-variant">1 dépense</td>
                        <td class="px-lg py-md text-right">
                            <div class="flex justify-end gap-md opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('recus.show', 4) }}" class="text-primary hover:text-secondary flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span> Voir
                                </a>
                                <button class="text-error hover:opacity-70 flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md text-sm text-on-surface">09 Oct 2023</td>
                        <td class="px-lg py-md text-sm font-medium text-primary">Hôtel Mercure Lyon</td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-green-100 text-green-700 border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> Traité
                            </span>
                        </td>
                        <td class="px-lg py-md text-sm text-on-surface-variant">4 dépenses</td>
                        <td class="px-lg py-md text-right">
                            <div class="flex justify-end gap-md opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('recus.show', 5) }}" class="text-primary hover:text-secondary flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span> Voir
                                </a>
                                <button class="text-error hover:opacity-70 flex items-center gap-xs text-xs font-medium uppercase">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-lg py-md border-t border-outline-variant bg-surface-container-low/30 flex justify-between items-center">
            <span class="text-xs text-on-surface-variant">Affichage de 1-5 sur 124 reçus</span>
            <div class="flex gap-sm">
                <button class="p-xs rounded-lg hover:bg-surface-variant text-outline disabled:opacity-30" disabled>
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="w-8 h-8 rounded-lg bg-primary text-on-primary font-bold text-xs">1</button>
                <button class="w-8 h-8 rounded-lg hover:bg-surface-variant text-primary font-medium text-xs">2</button>
                <button class="w-8 h-8 rounded-lg hover:bg-surface-variant text-primary font-medium text-xs">3</button>
                <button class="p-xs rounded-lg hover:bg-surface-variant text-outline">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endsection