## Why

Le cœur de l'application est d'extraire automatiquement les dépenses structurées à partir du texte brut des reçus via une IA. Actuellement, le dispatch est en place dans `RecuController::store()` mais le Job `ExtraireDepensesDuRecu` n'existe pas, le SDK `laravel/ai` n'est pas installé, Groq n'est pas configuré, et les enums/casts ne sont pas finalisés. Sans cette change, l'application crée des reçus sans jamais les traiter.

## What Changes

- **Installer `laravel/ai`** — ajout de la dépendance via Composer
- **Configurer Groq** — création de `config/ai.php`, ajout de `GROQ_API_KEY` et `AI_DEFAULT_PROVIDER` dans `.env` et `.env.example`
- **Créer `StatutRecu` enum** — enum string-backed avec les cas `EnAttente`, `Traite`, `Echoue`
- **Ajouter les casts Eloquent sur `Recu`** — `statut` → `StatutRecu`, `payload_brut` → `array`
- **Créer le Job `ExtraireDepensesDuRecu`** — implémentation qui appelle le SDK `laravel/ai` avec structured output, crée les `Depense` records, et met à jour le statut du `Recu`
- **Gestion d'erreurs dans le Job** — catch les exceptions, définit `statut = echoue`, ne plante jamais
- **Supprimer le `class_exists` guard** — une fois le Job créé, le dispatch direct fonctionne

## Capabilities

### New Capabilities

- `extraction-ia-job`: Job asynchrone qui appelle l'API Groq via `laravel/ai` pour extraire les articles d'un reçu
- `statut-recu-enum`: Enum PHP `StatutRecu` avec cast Eloquent sur le modèle Recu
- `laravel-ai-config`: Installation et configuration du package `laravel/ai` avec le provider Groq

### Modified Capabilities

*(Aucune spec existante à modifier.)*

## Impact

- **composer.json** — ajout de `laravel/ai`
- **.env / .env.example** — ajout de `GROQ_API_KEY`, `AI_DEFAULT_PROVIDER`
- **config/ai.php** — nouveau fichier de configuration
- **app/Enums/StatutRecu.php** — nouveau fichier enum
- **app/Models/Recu.php** — ajout du tableau `$casts`
- **app/Jobs/ExtraireDepensesDuRecu.php** — nouveau Job
- **app/Http/Controllers/RecuController.php** — suppression du `class_exists` guard, utilisation de `StatutRecu::EnAttente`
