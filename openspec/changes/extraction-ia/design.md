## Context

L'extraction IA est le cœur de l'application. Le `RecuController::store()` tente déjà de dispatcher `ExtraireDepensesDuRecu` via un `class_exists` guard, mais le Job n'existe pas. Le SDK `laravel/ai` n'est pas installé, Groq n'est pas configuré, l'enum `StatutRecu` manque, et le modèle `Recu` n'a pas de casts Eloquent. Cette change complète toute la pipeline : de la configuration du provider AI jusqu'à la création des `Depense` en base.

## Goals / Non-Goals

**Goals:**
- Installer et configurer `laravel/ai` avec le provider Groq
- Créer l'enum `StatutRecu` (EnAttente, Traite, Echoue)
- Ajouter les casts Eloquent sur `Recu` (statut, payload_brut)
- Créer le Job `ExtraireDepensesDuRecu` avec structured output via `laravel/ai`
- Gérer les erreurs : statut = echoue, pas de crash
- Supprimer le `class_exists` guard dans le controller

**Non-Goals:**
- Interface utilisateur pour le statut (déjà gérée par les badges dans les vues)
- Ré-extraction manuelle (sera une change future)
- Tests unitaires du SDK AI (tests d'intégration uniquement)
- Optimisation des prompts Groq (itérations futures)

## Decisions

1. **`laravel/ai` avec structured output** — Utiliser le SDK officiel `laravel/ai` avec la méthode `->structuredOutput()` qui garantit un JSON validé par un schéma. C'est plus fiable que `json_decode` qui peut retourner null silencieusement.

2. **Provider Groq via variable d'environnement** — Configurer `config/ai.php` pour utiliser le driver `groq` avec la clé API depuis `GROQ_API_KEY`. Le `AI_DEFAULT_PROVIDER=groq` évite de spécifier le provider à chaque appel.

3. **Schéma JSON strict** — Le contrat JSON (articles, total_estimé, devise) est défini dans AGENTS.md. En cas de réponse invalide, le Job catch l'exception et passe le statut à `echoue`.

4. **Enum StatutRecu rétrocompatible** — Les valeurs de l'enum (`en_attente`, `traite`, `echoue`) correspondent exactement aux valeurs de la colonne ENUM MySQL. Le cast Eloquent convertit automatiquement.

5. **Job synchrone en test, async en prod** — `QUEUE_CONNECTION=sync` dans phpunit.xml pour les tests, `QUEUE_CONNECTION=database` (ou redis) en production.

6. **Création des Depense en transaction** — Le Job crée les `Depense` dans une transaction DB. Si une dépense échoue, aucune n'est persistée et le statut passe à `echoue`.

7. **Payload brut conservé** — La réponse JSON brute de l'IA est stockée dans `recus.payload_brut` pour débogage et ré-extraction future.

## Risks / Trade-offs

- [Risk] L'API Groq peut être lente (1-5s) ou planter → Mitigation: Le Job est asynchrone (queue), l'utilisateur voit "en attente" immédiatement. Les timeouts sont gérés par le SDK.
- [Risk] La clé API Groq peut manquer → Mitigation: Le Job vérifie la configuration et passe en `echoue` avec un message clair. Le `config/ai.php` lance une exception si la clé est absente.
- [Risk] Le schéma JSON peut changer si Groq met à jour son API → Mitigation: Le contrat est côté SDK `laravel/ai`, on suit les versions majeures.
- [Trade-off] Pas de validation des `Depense` avant persistance → On fait confiance au structured output du SDK. Les cas tordus sont gérés par le catch global du Job.
