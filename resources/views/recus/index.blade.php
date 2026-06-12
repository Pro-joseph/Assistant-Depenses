@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Mes Reçus')
@section('page-title', 'Mes Reçus')
@section('search-placeholder', 'Rechercher un reçu...')
@section('search', true)

@section('content')
    @if (session('success'))
        <div class="mb-lg p-md bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg flex items-center gap-md">
            <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-lg mb-xl">
        <div class="md:col-span-2 relative overflow-hidden rounded-xl bg-primary text-on-primary p-xl flex flex-col justify-between shadow-md">
            <div class="relative z-10">
                <h2 class="text-2xl font-semibold mb-xs">Bonjour, {{ Auth::user()->name }}</h2>
                <p class="text-sm text-on-primary/80">
                    @if ($enAttente > 0)
                        Vous avez {{ $enAttente }} reçu{{ $enAttente > 1 ? 's' : '' }} en attente de traitement aujourd'hui.
                    @else
                        Tous vos reçus ont été traités.
                    @endif
                </p>
            </div>
            <div class="relative z-10 flex gap-sm mt-lg">
                <a href="{{ route('recus.create') }}"
                    class="inline-block bg-secondary-container text-on-secondary-container px-lg py-sm rounded-full font-bold text-xs hover:brightness-110 transition-all">Analyser
                    maintenant</a>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between shadow-sm">
            <span class="text-outline text-xs uppercase tracking-wider font-medium">Total Traité</span>
            <div class="flex items-baseline gap-xs">
                <span class="text-4xl font-bold text-primary">{{ $totalTraite }}</span>
                <span class="text-primary/60 text-sm">reçu{{ $totalTraite > 1 ? 's' : '' }}</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-between shadow-sm">
            <span class="text-outline text-xs uppercase tracking-wider font-medium">En attente</span>
            <div class="flex items-baseline gap-xs">
                <span class="text-4xl font-bold text-secondary-container">{{ str_pad($enAttente, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="text-primary/60 text-sm">reçu{{ $enAttente > 1 ? 's' : '' }}</span>
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
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-surface-container/30">
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Doc</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Date</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Référence / Nom</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Statut</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium">Dépenses</th>
                        <th class="px-lg py-md text-xs text-outline uppercase tracking-wider font-medium text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($recus as $recu)
                        <tr class="hover:bg-surface-container-low transition-colors group" data-recu-id="{{ $recu->id }}">
                            <td class="px-lg py-md">
                                @if ($recu->image_path)
                                    <img src="{{ Storage::url($recu->image_path) }}" alt="" class="w-10 h-10 rounded-lg object-cover border border-outline-variant">
                                @else
                                    <span class="material-symbols-outlined text-outline text-2xl block text-center">description</span>
                                @endif
                            </td>
                            <td class="px-lg py-md text-sm text-on-surface">{{ $recu->created_at->format('d/m/Y') }}</td>
                            <td class="px-lg py-md text-sm font-medium text-primary">Reçu #{{ $recu->id }}</td>
                            <td class="px-lg py-md"><span class="statut-badge" data-statut="{{ $recu->statut->value }}">@include('recus._statut_badge', ['statut' => $recu->statut->value])</span></td>
                            <td class="px-lg py-md text-sm text-on-surface-variant">{{ $recu->depenses_count }} dépense{{ $recu->depenses_count > 1 ? 's' : '' }}</td>
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
                            <td colspan="6" class="px-lg py-xl text-center text-on-surface-variant">
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
        @include('partials.pagination', ['paginator' => $recus, 'label' => 'reçus'])
    </div>
@push('scripts')
    <script>
        const badgeHTML = {
            en_attente: '<span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-yellow-100 text-yellow-700 border-yellow-200"><span class="w-1.5 h-1.5 rounded-full bg-yellow-500 me-2"></span> En attente</span>',
            traite: '<span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-green-100 text-green-700 border-green-200"><span class="w-1.5 h-1.5 rounded-full bg-green-500 me-2"></span> Traité</span>',
            echoue: '<span class="inline-flex items-center px-sm py-xs rounded-full text-xs font-medium border bg-red-100 text-red-700 border-red-200"><span class="w-1.5 h-1.5 rounded-full bg-red-500 me-2"></span> Échoué</span>',
        };

        function pollStatuts() {
            const rows = document.querySelectorAll('[data-recu-id]');
            if (!rows.length) return;

            fetch('{{ route("recus.statuts") }}')
                .then(r => r.json())
                .then(data => {
                    data.recus.forEach(recu => {
                        const row = document.querySelector(`[data-recu-id="${recu.id}"]`);
                        if (!row) return;
                        const badge = row.querySelector('.statut-badge');
                        if (!badge) return;
                        if (badge.dataset.statut === 'en_attente' && recu.statut !== 'en_attente') {
                            badge.dataset.statut = recu.statut;
                            badge.innerHTML = badgeHTML[recu.statut] || badgeHTML.echoue;
                        }
                    });

                    const enAttente = data.recus.filter(r => r.statut === 'en_attente').length;
                    const badge = document.querySelector('[data-statut="en_attente"]');
                    if (!enAttente && !badge) {
                        clearInterval(pollInterval);
                    }
                })
                .catch(() => {});
        }

        const pollInterval = setInterval(pollStatuts, 5000);
        pollStatuts();
    </script>
@endpush
@endsection
