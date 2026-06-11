<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Assistant Dépenses') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Roboto+Flex:opsz,wght@8..144,400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --terracotta: #c8655a;
            --terracotta-dark: #a84a3f;
            --indigo: #1b2d4f;
            --indigo-light: #2a4068;
            --gold: #d4a843;
            --cream: #fbf6f0;
            --warm-dark: #2c2420;
            --warm-gray: #8a7a72;
        }

        .bg-moroccan {
            background-color: var(--cream);
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(200, 101, 90, 0.08) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 80% 90%, rgba(212, 168, 67, 0.06) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 10% 80%, rgba(27, 45, 79, 0.05) 0%, transparent 60%);
        }

        .pattern-geometric {
            position: relative;
        }

        .pattern-geometric::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M40 0 L80 40 L40 80 L0 40 Z' fill='none' stroke='%23c8655a' stroke-width='0.3' stroke-opacity='0.06'/%3E%3C/svg%3E");
            background-size: 120px 120px;
            pointer-events: none;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .deco-star {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            position: relative;
        }

        .deco-star::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--terracotta);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            opacity: 0.15;
            transform: rotate(0deg);
        }

        .deco-star::after {
            content: '';
            position: absolute;
            inset: 10px;
            background: var(--terracotta);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            opacity: 0.25;
        }

        .deco-line {
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--terracotta), var(--gold), var(--terracotta), transparent);
            margin: 0 auto;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 2.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 9999px;
            background: var(--terracotta);
            color: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            letter-spacing: 0.01em;
            border: 2px solid var(--terracotta);
        }

        .btn-primary:hover {
            background: var(--terracotta-dark);
            border-color: var(--terracotta-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(200, 101, 90, 0.3);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 2.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 9999px;
            background: transparent;
            color: var(--indigo);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            letter-spacing: 0.01em;
            border: 2px solid var(--indigo);
        }

        .btn-secondary:hover {
            background: var(--indigo);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(27, 45, 79, 0.2);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes decoPulse {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.15; }
            50% { transform: scale(1.05) rotate(5deg); opacity: 0.2; }
        }

        .animate-fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .animate-fade-up-delay-1 { animation-delay: 0.1s; }
        .animate-fade-up-delay-2 { animation-delay: 0.25s; }
        .animate-fade-up-delay-3 { animation-delay: 0.4s; }
        .animate-fade-up-delay-4 { animation-delay: 0.55s; }

        .deco-star-animate::before {
            animation: decoPulse 4s ease-in-out infinite;
        }

        .heading-tajawal {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased bg-moroccan pattern-geometric min-h-screen flex flex-col">

    <div class="hero-content flex-1 flex flex-col items-center justify-center px-6 py-16">
        <div class="text-center max-w-2xl mx-auto">

            <div class="deco-star deco-star-animate animate-fade-up mb-10 mx-auto"></div>

            <div class="animate-fade-up animate-fade-up-delay-1 mb-3">
                <span class="inline-block text-sm font-semibold tracking-[0.2em] uppercase text-[var(--warm-gray)]">
                    Si Brahim
                </span>
            </div>

            <h1 class="heading-tajawal text-5xl sm:text-6xl md:text-7xl font-extrabold text-[var(--indigo)] leading-[1.1] tracking-tight animate-fade-up animate-fade-up-delay-1">
                Assistant
                <span class="text-[var(--terracotta)]">Dépenses</span>
            </h1>

            <div class="deco-line my-8 animate-fade-up animate-fade-up-delay-2"></div>

            <p class="text-lg sm:text-xl text-[var(--warm-gray)] leading-relaxed max-w-lg mx-auto animate-fade-up animate-fade-up-delay-2">
                Gérez vos reçus fournisseurs et suivez vos dépenses
                en toute simplicité.
            </p>

            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-up animate-fade-up-delay-3">
                <a href="{{ route('login') }}" class="btn-primary">
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="btn-secondary">
                    Inscription
                </a>
            </div>
        </div>
    </div>

    <footer class="relative z-[1] py-8 text-center animate-fade-up animate-fade-up-delay-4">
        <p class="text-sm text-[var(--warm-gray)]/60">
            &copy; {{ date('Y') }} Si Brahim
        </p>
    </footer>

</body>
</html>
