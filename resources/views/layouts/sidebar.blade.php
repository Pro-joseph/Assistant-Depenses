<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Assistant Dépenses')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Roboto Flex', sans-serif; }
    </style>
</head>
<body class="bg-surface text-on-surface">
    <aside class="h-screen w-64 fixed left-0 top-0 bg-primary flex flex-col py-lg z-50 shadow-md">
        <div class="px-lg mb-xl">
            <h1 class="text-secondary-fixed font-bold tracking-tight text-xl">Assistant Dépenses</h1>
            <p class="text-sm text-on-primary/70">Gestion de budget</p>
        </div>
        <nav class="flex-1 flex flex-col gap-sm">
            <a class="flex items-center gap-md py-sm px-lg text-on-primary hover:bg-on-primary-fixed-variant rounded-xl mx-2 transition-all {{ request()->routeIs('recus.index') ? 'bg-secondary-container text-on-secondary-container font-bold' : '' }}" href="{{ route('recus.index') }}">
                <span class="material-symbols-outlined">receipt_long</span>
                <span class="text-sm">Mes Reçus</span>
            </a>
            <a class="flex items-center gap-md py-sm px-lg text-on-primary hover:bg-on-primary-fixed-variant rounded-xl mx-2 transition-all {{ request()->routeIs('recus.create') ? 'bg-secondary-container text-on-secondary-container font-bold' : '' }}" href="{{ route('recus.create') }}">
                <span class="material-symbols-outlined">add_circle</span>
                <span class="text-sm">Nouveau Reçu</span>
            </a>
            <a class="flex items-center gap-md py-sm px-lg text-on-primary hover:bg-on-primary-fixed-variant rounded-xl mx-2 transition-all {{ request()->routeIs('depenses.index') ? 'bg-secondary-container text-on-secondary-container font-bold' : '' }}" href="{{ route('depenses.index') }}">
                <span class="material-symbols-outlined">payments</span>
                <span class="text-sm">Mes Dépenses</span>
            </a>
        </nav>
        <div class="mt-auto border-t border-on-primary/10 pt-md flex flex-col gap-sm">
            <a class="flex items-center gap-md py-sm px-lg text-on-primary/80 hover:bg-on-primary-fixed-variant rounded-xl mx-2 transition-all" href="#">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-sm">Paramètres</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-md py-sm px-lg text-on-primary/80 hover:bg-on-primary-fixed-variant rounded-xl mx-2 transition-all">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-sm">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="ms-64 min-h-screen">
        <header class="sticky top-0 bg-surface-container-lowest border-b border-outline-variant shadow-sm z-40">
            <div class="flex justify-between items-center px-lg py-sm max-w-[1440px] mx-auto">
                <div class="flex items-center gap-lg">
                    <h2 class="text-xl font-bold text-primary">@yield('page-title', 'Assistant Dépenses')</h2>
                    @hasSection('search')
                        <form method="GET" action="{{ url()->current() }}" class="hidden md:flex bg-surface-container-high rounded-full px-md py-xs items-center gap-sm border border-outline-variant">
                            <span class="material-symbols-outlined text-outline text-sm">search</span>
                            <input class="bg-transparent border-none focus:ring-0 text-sm w-64" type="text" name="q" value="{{ request('q') }}" placeholder="@yield('search-placeholder', 'Rechercher...')"/>
                        </form>
                    @endif
                </div>
                <div class="flex items-center gap-md">
                    <span class="text-sm text-on-surface-variant cursor-pointer hover:text-secondary transition-colors">Mon Profil</span>
                    <div class="h-8 w-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold overflow-hidden border border-outline-variant">
                        <span class="material-symbols-outlined text-sm">person</span>
                    </div>
                </div>
            </div>
        </header>

        <section class="p-lg max-w-[1440px] mx-auto">
            @yield('content')
        </section>
    </main>

    <div class="fixed top-0 right-0 -z-10 w-1/2 h-full opacity-30 pointer-events-none">
        <div class="absolute top-1/4 right-0 w-96 h-96 bg-primary-fixed blur-[120px] rounded-full"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-secondary-fixed blur-[100px] rounded-full"></div>
    </div>
</body>
</html>