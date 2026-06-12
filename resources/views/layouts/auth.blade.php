<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Assistant Dépenses')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface min-h-screen flex flex-col relative overflow-hidden">
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-primary-container rounded-full blur-[120px] opacity-10"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-secondary-container rounded-full blur-[100px] opacity-10"></div>
    </div>

    <main class="flex-grow flex items-center justify-center p-lg relative z-10">
        <div class="w-full max-w-[440px]">
            @section('auth-header')
                <div class="flex flex-col items-center mb-xl">
                    <div class="w-16 h-16 bg-primary flex items-center justify-center rounded-xl shadow-md mb-md">
                        <span class="material-symbols-outlined text-on-primary text-[32px]" style="font-variation-settings: 'FILL' 1;">payments</span>
                    </div>
                    <h1 class="text-2xl font-bold text-primary">Assistant Dépenses</h1>
                    <p class="text-sm text-on-surface-variant mt-xs">@yield('subtitle')</p>
                </div>
            @show

            @yield('auth-card')

            @section('auth-badges')
                <div class="mt-lg flex justify-center items-center gap-lg opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                    <div class="flex items-center gap-xs">
                        <span class="material-symbols-outlined text-outline text-sm">verified_user</span>
                        <span class="text-xs text-outline">Sécurisé par SSL</span>
                    </div>
                    <div class="flex items-center gap-xs">
                        <span class="material-symbols-outlined text-outline text-sm">gpp_good</span>
                        <span class="text-xs text-outline">Conforme RGPD</span>
                    </div>
                </div>
            @show
        </div>
    </main>

    @section('auth-footer')
        <footer class="p-lg text-center relative z-10">
            <p class="text-xs text-outline">© {{ date('Y') }} Assistant Dépenses. Tous droits réservés.</p>
        </footer>
    @show

    @stack('scripts')
</body>
</html>
