## 1. Installation et Configuration laravel/ai

- [x] 1.1 Installer `laravel/ai` avec `composer require laravel/ai`
- [x] 1.2 Publier la config AI : `php artisan vendor:publish --provider="Laravel\AI\AIServiceProvider"` (ou créer `config/ai.php` manuellement)
- [x] 1.3 Ajouter `GROQ_API_KEY=` et `AI_DEFAULT_PROVIDER=groq` dans `.env` et `.env.example`

## 2. Enum et Casts

- [x] 2.1 Créer `app/Enums/StatutRecu.php` — enum string-backed : EnAttente → en_attente, Traite → traite, Echoue → echoue
- [x] 2.2 Ajouter `$casts` au modèle Recu : `'statut' => StatutRecu::class`, `'payload_brut' => 'array'`

## 3. Job d'Extraction

- [x] 3.1 Créer `app/Jobs/ExtraireDepensesDuRecu.php` qui implémente `ShouldQueue` et `InteractsWithQueue`
- [x] 3.2 Implémenter `handle()` : appeler l'IA via `\Laravel\AI\Facades\AI::chat()->structuredOutput()` avec le schéma JSON
- [x] 3.3 Créer les `Depense` records à partir de la réponse structurée de l'IA
- [x] 3.4 Mettre à jour le Recu : `statut = traite`, `total_estime`, `devise`, `payload_brut`
- [x] 3.5 Gérer les erreurs : try/catch avec `statut = echoue`, logging

## 4. Nettoyage du Controller

- [x] 4.1 Remplacer le `class_exists` guard dans `RecuController::store()` par un dispatch direct : `ExtraireDepensesDuRecu::dispatch($recu)`
- [x] 4.2 Remplacer la string `'en_attente'` par `StatutRecu::EnAttente` dans `store()` et `update()`

## 5. Tests

- [x] 5.1 Tester que le Job est dispatché après création d'un recu
- [x] 5.2 Tester que le Job passe le statut à `traite` après extraction réussie (mock IA)
- [x] 5.3 Tester que le Job passe le statut à `echoue` en cas d'erreur API
- [x] 5.4 Tester la création des Depense par le Job
