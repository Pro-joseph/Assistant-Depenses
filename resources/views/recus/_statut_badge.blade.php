@switch($statut)
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
            <span class="w-1.5 h-1.5 rounded-full bg-gray-500 me-2"></span> {{ $statut }}
        </span>
@endswitch
