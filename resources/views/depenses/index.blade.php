@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Mes Dépenses')
@section('page-title', 'Mes Dépenses')
@section('search-placeholder', 'Rechercher une dépense...')
@section('search', true)

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
        <div class="flex flex-wrap gap-sm">
            <button class="px-md py-sm bg-primary text-on-primary rounded-full text-xs font-medium flex items-center gap-xs shadow-sm hover:opacity-90 transition-all">
                Toutes
            </button>
            <button class="px-md py-sm bg-surface-container-lowest border border-outline-variant text-on-surface-variant rounded-full text-xs font-medium flex items-center gap-xs hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-[18px]">restaurant</span>
                Alimentaire
            </button>
            <button class="px-md py-sm bg-surface-container-lowest border border-outline-variant text-on-surface-variant rounded-full text-xs font-medium flex items-center gap-xs hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-[18px]">local_bar</span>
                Boissons
            </button>
            <button class="px-md py-sm bg-surface-container-lowest border border-outline-variant text-on-surface-variant rounded-full text-xs font-medium flex items-center gap-xs hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-[18px]">directions_car</span>
                Transport
            </button>
            <button class="px-md py-sm bg-surface-container-lowest border border-outline-variant text-on-surface-variant rounded-full text-xs font-medium flex items-center gap-xs hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                Shopping
            </button>
            <button class="px-md py-sm bg-surface-container-lowest border border-outline-variant text-on-surface-variant rounded-full text-xs font-medium flex items-center gap-xs hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-[18px]">more_horiz</span>
                Autre
            </button>
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
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium">Libellé</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium">Catégorie</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium text-right">Quantité</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium text-right">Prix Unitaire</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium text-right">Total</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium">Reçu Source</th>
                        <th class="px-lg py-md text-xs text-on-surface-variant uppercase tracking-wider font-medium w-16"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50">
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md">
                            <div class="flex flex-col">
                                <span class="text-sm text-on-surface font-medium">Panier hebdomadaire Bio</span>
                                <span class="text-xs text-on-surface-variant">12 Oct 2023 • 14:32</span>
                            </div>
                        </td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium gap-xs bg-green-100 text-green-800">
                                <span class="material-symbols-outlined text-[14px]">restaurant</span>
                                Alimentaire
                            </span>
                        </td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">1</td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">45,90 €</td>
                        <td class="px-lg py-md text-right text-base font-bold text-primary">45,90 €</td>
                        <td class="px-lg py-md">
                            <a href="{{ route('recus.show', 1) }}" class="flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors cursor-pointer">
                                <span class="material-symbols-outlined">description</span>
                                <span class="text-xs underline">RECU_1289.pdf</span>
                            </a>
                        </td>
                        <td class="px-lg py-md text-right">
                            <button class="opacity-0 group-hover:opacity-100 transition-opacity p-xs hover:bg-surface-container-high rounded-full">
                                <span class="material-symbols-outlined text-outline">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md">
                            <div class="flex flex-col">
                                <span class="text-sm text-on-surface font-medium">Café Arabica (Pack de 3)</span>
                                <span class="text-xs text-on-surface-variant">11 Oct 2023 • 09:15</span>
                            </div>
                        </td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium gap-xs bg-orange-100 text-orange-800">
                                <span class="material-symbols-outlined text-[14px]">local_bar</span>
                                Boissons
                            </span>
                        </td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">3</td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">12,50 €</td>
                        <td class="px-lg py-md text-right text-base font-bold text-primary">37,50 €</td>
                        <td class="px-lg py-md">
                            <a href="{{ route('recus.show', 2) }}" class="flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors cursor-pointer">
                                <span class="material-symbols-outlined">description</span>
                                <span class="text-xs underline">RECU_4412.jpg</span>
                            </a>
                        </td>
                        <td class="px-lg py-md text-right">
                            <button class="opacity-0 group-hover:opacity-100 transition-opacity p-xs hover:bg-surface-container-high rounded-full">
                                <span class="material-symbols-outlined text-outline">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md">
                            <div class="flex flex-col">
                                <span class="text-sm text-on-surface font-medium">Uber Ride - Paris HQ</span>
                                <span class="text-xs text-on-surface-variant">10 Oct 2023 • 18:45</span>
                            </div>
                        </td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium gap-xs bg-blue-100 text-blue-800">
                                <span class="material-symbols-outlined text-[14px]">directions_car</span>
                                Transport
                            </span>
                        </td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">1</td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">22,40 €</td>
                        <td class="px-lg py-md text-right text-base font-bold text-primary">22,40 €</td>
                        <td class="px-lg py-md">
                            <a href="{{ route('recus.show', 3) }}" class="flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors cursor-pointer">
                                <span class="material-symbols-outlined">description</span>
                                <span class="text-xs underline">RECU_Uber_88.pdf</span>
                            </a>
                        </td>
                        <td class="px-lg py-md text-right">
                            <button class="opacity-0 group-hover:opacity-100 transition-opacity p-xs hover:bg-surface-container-high rounded-full">
                                <span class="material-symbols-outlined text-outline">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md">
                            <div class="flex flex-col">
                                <span class="text-sm text-on-surface font-medium">Bose QC45 Headphones</span>
                                <span class="text-xs text-on-surface-variant">08 Oct 2023 • 11:20</span>
                            </div>
                        </td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium gap-xs bg-purple-100 text-purple-800">
                                <span class="material-symbols-outlined text-[14px]">shopping_bag</span>
                                Shopping
                            </span>
                        </td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">1</td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">329,00 €</td>
                        <td class="px-lg py-md text-right text-base font-bold text-primary">329,00 €</td>
                        <td class="px-lg py-md">
                            <a href="{{ route('recus.show', 4) }}" class="flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors cursor-pointer">
                                <span class="material-symbols-outlined">description</span>
                                <span class="text-xs underline">AMZN_INV_331.pdf</span>
                            </a>
                        </td>
                        <td class="px-lg py-md text-right">
                            <button class="opacity-0 group-hover:opacity-100 transition-opacity p-xs hover:bg-surface-container-high rounded-full">
                                <span class="material-symbols-outlined text-outline">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-lg py-md">
                            <div class="flex flex-col">
                                <span class="text-sm text-on-surface font-medium">Abonnement SaaS Mensuel</span>
                                <span class="text-xs text-on-surface-variant">05 Oct 2023 • 00:01</span>
                            </div>
                        </td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium gap-xs bg-slate-100 text-slate-800">
                                <span class="material-symbols-outlined text-[14px]">more_horiz</span>
                                Autre
                            </span>
                        </td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">1</td>
                        <td class="px-lg py-md text-right text-sm text-on-surface">19,99 €</td>
                        <td class="px-lg py-md text-right text-base font-bold text-primary">19,99 €</td>
                        <td class="px-lg py-md">
                            <a href="{{ route('recus.show', 5) }}" class="flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors cursor-pointer">
                                <span class="material-symbols-outlined">description</span>
                                <span class="text-xs underline">SUB_INV_902.pdf</span>
                            </a>
                        </td>
                        <td class="px-lg py-md text-right">
                            <button class="opacity-0 group-hover:opacity-100 transition-opacity p-xs hover:bg-surface-container-high rounded-full">
                                <span class="material-symbols-outlined text-outline">more_vert</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-lg py-md border-t border-outline-variant flex items-center justify-between">
            <span class="text-xs text-on-surface-variant">Affichage de 1-5 sur 42 dépenses</span>
            <div class="flex items-center gap-xs">
                <button class="p-xs rounded-lg hover:bg-surface-container transition-all disabled:opacity-30" disabled>
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="w-8 h-8 rounded-lg bg-primary text-on-primary text-xs font-medium flex items-center justify-center">1</button>
                <button class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant text-xs font-medium flex items-center justify-center">2</button>
                <button class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant text-xs font-medium flex items-center justify-center">3</button>
                <span class="px-xs text-on-surface-variant text-xs">...</span>
                <button class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant text-xs font-medium flex items-center justify-center">9</button>
                <button class="p-xs rounded-lg hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg mt-xxl">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between h-40 group hover:shadow-md transition-all">
            <div class="flex justify-between">
                <span class="text-xs text-on-surface-variant font-medium">Total du mois</span>
                <span class="material-symbols-outlined text-primary">trending_up</span>
            </div>
            <div class="text-2xl font-bold text-primary">1 254,40 €</div>
            <div class="text-xs text-green-600 flex items-center gap-xs">
                <span class="material-symbols-outlined text-[14px]">arrow_downward</span>
                12% de moins que sept.
            </div>
        </div>
        <div class="bg-primary-container rounded-xl p-lg flex flex-col justify-between h-40 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex justify-between">
                    <span class="text-xs text-on-primary/70 font-medium">Catégorie dominante</span>
                    <span class="material-symbols-outlined text-secondary-container">auto_awesome</span>
                </div>
                <div class="text-2xl font-bold text-on-primary">Alimentaire</div>
            </div>
            <div class="relative z-10 text-xs text-on-primary/70">
                42% de vos dépenses totales
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-[120px]">restaurant</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between h-40">
            <div class="flex justify-between">
                <span class="text-xs text-on-surface-variant font-medium">Dernier reçu scanné</span>
                <span class="material-symbols-outlined text-secondary">history</span>
            </div>
            <div class="flex items-center gap-md">
                <div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-outline">image</span>
                </div>
                <div>
                    <div class="text-sm font-medium">Boulangerie Paul</div>
                    <div class="text-xs text-on-surface-variant">Il y a 2 heures • 4,50 €</div>
                </div>
            </div>
            <div class="w-full bg-surface-container h-1.5 rounded-full overflow-hidden">
                <div class="bg-primary h-full w-2/3"></div>
            </div>
        </div>
    </div>
@endsection