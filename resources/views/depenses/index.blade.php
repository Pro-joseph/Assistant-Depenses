@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Mes Dépenses')
@section('page-title', 'Mes Dépenses')
@section('search-placeholder', 'Rechercher une dépense...')
@section('search', true)

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
        <div class="flex flex-wrap gap-sm">
            <a href="{{ request()->fullUrlWithQuery(['categorie' => null]) }}"
                class="px-md py-sm rounded-full text-xs font-medium flex items-center gap-xs shadow-sm transition-all {{ !request('categorie') ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:bg-surface-container' }}">
                Toutes
            </a>
            <a href="{{ request()->fullUrlWithQuery(['categorie' => 'alimentaire']) }}"
                class="px-md py-sm rounded-full text-xs font-medium flex items-center gap-xs transition-all {{ request('categorie') === 'alimentaire' ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined text-[18px]">restaurant</span>
                Alimentaire
            </a>
            <a href="{{ request()->fullUrlWithQuery(['categorie' => 'boissons']) }}"
                class="px-md py-sm rounded-full text-xs font-medium flex items-center gap-xs transition-all {{ request('categorie') === 'boissons' ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined text-[18px]">local_bar</span>
                Boissons
            </a>
            <a href="{{ request()->fullUrlWithQuery(['categorie' => 'hygiene']) }}"
                class="px-md py-sm rounded-full text-xs font-medium flex items-center gap-xs transition-all {{ request('categorie') === 'hygiene' ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined text-[18px]">soap</span>
                Hygiène
            </a>
            <a href="{{ request()->fullUrlWithQuery(['categorie' => 'entretien']) }}"
                class="px-md py-sm rounded-full text-xs font-medium flex items-center gap-xs transition-all {{ request('categorie') === 'entretien' ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined text-[18px]">cleaning_services</span>
                Entretien
            </a>
            <a href="{{ request()->fullUrlWithQuery(['categorie' => 'autre']) }}"
                class="px-md py-sm rounded-full text-xs font-medium flex items-center gap-xs transition-all {{ request('categorie') === 'autre' ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined text-[18px]">more_horiz</span>
                Autre
            </a>
        </div>
        <div class="flex items-center gap-sm">
            <button class="flex items-center gap-xs px-md py-sm bg-surface-container-lowest border border-outline-variant rounded-lg text-xs font-medium text-on-surface-variant hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filtres Avancés
            </button>
            <button class="flex items-center gap-xs px-md py-sm bg-surface-container-lowest border border-outline-variant rounded-lg text-xs font-medium text-on-surface-variant hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Exporter
            </button>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-start">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium">Libellé</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium">Catégorie</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium text-end">Qté</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium text-end">Prix unitaire</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium text-end">Total</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium">Reçu Source</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium w-16"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50">
                    @forelse ($depenses as $depense)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-lg py-md">
                                <div class="flex flex-col">
                                    <span class="text-sm text-on-surface font-medium">{{ $depense->libelle }}</span>
                                    <span class="text-xs text-on-surface-variant">{{ $depense->created_at->format('d M Y • H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                @php
                                    $couleurs = [
                                        'alimentaire' => 'bg-green-100 text-green-800',
                                        'boissons' => 'bg-orange-100 text-orange-800',
                                        'hygiene' => 'bg-blue-100 text-blue-800',
                                        'entretien' => 'bg-purple-100 text-purple-800',
                                        'autre' => 'bg-slate-100 text-slate-800',
                                    ];
                                    $icones = [
                                        'alimentaire' => 'restaurant',
                                        'boissons' => 'local_bar',
                                        'hygiene' => 'soap',
                                        'entretien' => 'cleaning_services',
                                        'autre' => 'more_horiz',
                                    ];
                                    $cat = $depense->categorie?->value ?? 'autre';
                                @endphp
                                <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium gap-xs {{ $couleurs[$cat] ?? 'bg-slate-100 text-slate-800' }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $icones[$cat] ?? 'more_horiz' }}</span>
                                    {{ $depense->categorie?->name ?? $cat }}
                                </span>
                            </td>
                            <td class="px-lg py-md text-end text-sm text-on-surface">{{ $depense->quantite }}</td>
                            <td class="px-lg py-md text-end text-sm text-on-surface">{{ number_format($depense->prix_unitaire, 2) }} €</td>
                            <td class="px-lg py-md text-end text-base font-bold text-primary">{{ number_format($depense->quantite * $depense->prix_unitaire, 2) }} €</td>
                            <td class="px-lg py-md">
                                <a href="{{ route('recus.show', $depense->recu_id) }}" class="flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">description</span>
                                    <span class="text-xs underline">Reçu #{{ $depense->recu_id }}</span>
                                </a>
                            </td>
                            <td class="px-lg py-md text-end">
                                <button class="opacity-0 group-hover:opacity-100 transition-opacity p-xs hover:bg-surface-container-high rounded-full">
                                    <span class="material-symbols-outlined text-outline">more_vert</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-lg py-xl text-center text-on-surface-variant">
                                <div class="flex flex-col items-center gap-sm">
                                    <span class="material-symbols-outlined text-[48px] text-outline">payments</span>
                                    <p class="text-sm font-medium">Aucune dépense trouvée</p>
                                    <a href="{{ route('recus.create') }}" class="text-primary text-xs underline hover:opacity-70">
                                        Soumettre un nouveau reçu
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($depenses->count())
            <div class="px-lg py-md border-t border-outline-variant bg-surface-container-low/30 flex justify-between items-center">
                <span class="text-xs text-on-surface-variant">
                    Affichage de {{ $depenses->firstItem() }} à {{ $depenses->lastItem() }}
                    sur {{ $depenses->total() }} dépenses
                </span>
                <div class="flex gap-sm">
                    @if ($depenses->onFirstPage())
                        <button class="p-xs rounded-lg hover:bg-surface-variant text-outline opacity-30" disabled>
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $depenses->previousPageUrl() }}" class="p-xs rounded-lg hover:bg-surface-variant text-outline flex items-center">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    @foreach ($depenses->getUrlRange(1, $depenses->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium
                        {{ $page == $depenses->currentPage()
                            ? 'bg-primary text-on-primary font-bold'
                            : 'hover:bg-surface-variant text-primary' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if ($depenses->hasMorePages())
                        <a href="{{ $depenses->nextPageUrl() }}" class="p-xs rounded-lg hover:bg-surface-variant text-outline flex items-center">
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg mt-xxl">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between h-40 group hover:shadow-md transition-all">
            <div class="flex justify-between">
                <span class="text-xs text-on-surface-variant font-medium">Total du mois</span>
                <span class="material-symbols-outlined text-primary">trending_up</span>
            </div>
            <div class="text-2xl font-bold text-primary">{{ number_format($totalMois, 2) }} €</div>
            <div class="text-xs text-green-600 flex items-center gap-xs">
                <span class="material-symbols-outlined text-[14px]">arrow_downward</span>
                Basé sur {{ $depenses->total() }} dépenses
            </div>
        </div>
        <div class="bg-primary-container rounded-xl p-lg flex flex-col justify-between h-40 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex justify-between">
                    <span class="text-xs text-on-primary/70 font-medium">Catégorie dominante</span>
                    <span class="material-symbols-outlined text-secondary-container">auto_awesome</span>
                </div>
                <div class="text-2xl font-bold text-on-primary">{{ $dominante?->categorie ?? '—' }}</div>
            </div>
            <div class="relative z-10 text-xs text-on-primary/70">
                {{ $pourcentageDominante }}% de vos dépenses totales
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-[120px]">auto_awesome</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between h-40">
            <div class="flex justify-between">
                <span class="text-xs text-on-surface-variant font-medium">Dernier reçu scanné</span>
                <span class="material-symbols-outlined text-secondary">history</span>
            </div>
            @php
                $dernier = $depenses->first();
            @endphp
            @if ($dernier)
                <div class="flex items-center gap-md">
                    <div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-outline">receipt</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium">Reçu #{{ $dernier->recu_id }}</div>
                        <div class="text-xs text-on-surface-variant">{{ $dernier->created_at->diffForHumans() }} • {{ number_format($dernier->prix_unitaire, 2) }} €</div>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-md">
                    <div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-outline">receipt</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-on-surface-variant">Aucun reçu</div>
                        <div class="text-xs text-on-surface-variant">Soumettez votre premier reçu</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
