project:
  name: Assistant Dépenses
  version: "1.0.0"
  description: >
    A Laravel 13 application that helps a neighborhood merchant extract
    structured expense data from raw supplier receipts written in Darija.
    The user pastes raw text or uploads a photo, and the app uses an AI model
    via the laravel/ai SDK (Groq) to extract structured line items, stored as
    typed Depense records linked to a Recu.
  language: PHP 8.3
  framework: Laravel 13
  ai_sdk: laravel/ai
  ai_provider: Groq
  deadline: "2026-06-12"

# -----------------------------------------------
# Tech Stack
# -----------------------------------------------
stack:
  backend: Laravel 13
  language: PHP 8.3
  database: MySQL 8
  ai_sdk: laravel/ai (Groq provider)
  queue_driver: database (local) / redis (prod)
  auth: Laravel Breeze
  testing: Pest
  frontend: Blade + Tailwind CSS
  storage: Laravel File Storage (local disk)

# -----------------------------------------------
# Database
# -----------------------------------------------
database:
  tables:
    utilisateurs:
      columns:
        id: BIGINT UNSIGNED AUTO_INCREMENT PK
        name: VARCHAR(100) NOT NULL
        email: VARCHAR(255) NOT NULL UNIQUE
        password: VARCHAR(255) NOT NULL
        created_at: TIMESTAMP NOT NULL
        updated_at: TIMESTAMP NULL

    recus:
      columns:
        id: BIGINT UNSIGNED AUTO_INCREMENT PK
        user_id: BIGINT UNSIGNED NOT NULL FK -> utilisateurs.id CASCADE DELETE
        texte_brut: TEXT NULL
        image_path: VARCHAR(255) NULL
        statut: ENUM(en_attente, traite, echoue) NOT NULL DEFAULT en_attente
        payload_brut: JSON NULL
        total_estime: DECIMAL(10,2) NULL
        devise: VARCHAR(10) NULL
        created_at: TIMESTAMP NOT NULL
        updated_at: TIMESTAMP NULL

    depenses:
      columns:
        id: BIGINT UNSIGNED AUTO_INCREMENT PK
        recu_id: BIGINT UNSIGNED NOT NULL FK -> recus.id CASCADE DELETE
        libelle: VARCHAR(255) NOT NULL
        quantite: INT UNSIGNED NOT NULL
        prix_unitaire: DECIMAL(10,2) NOT NULL
        categorie: ENUM(alimentaire, boissons, hygiene, entretien, autre) NOT NULL
        created_at: TIMESTAMP NOT NULL
        updated_at: TIMESTAMP NULL

  relations:
    - User hasMany Recu
    - Recu belongsTo User
    - Recu hasMany Depense
    - Depense belongsTo Recu

# -----------------------------------------------
# Enums
# -----------------------------------------------
enums:
  StatutRecu:
    path: app/Enums/StatutRecu.php
    values:
      - EnAttente: en_attente
      - Traite: traite
      - Echoue: echoue

  CategorieDepense:
    path: app/Enums/CategorieDepense.php
    values:
      - Alimentaire: alimentaire
      - Boissons: boissons
      - Hygiene: hygiene
      - Entretien: entretien
      - Autre: autre

# -----------------------------------------------
# AI Contract
# -----------------------------------------------
ai_contract:
  description: >
    The structured JSON output that the laravel/ai SDK must always return.
    Any response outside this schema must be caught and result in statut = echoue.
  schema:
    articles:
      type: array
      items:
        libellé: string
        quantité: integer
        prix_unitaire: number
        catégorie:
          type: enum
          values: [alimentaire, boissons, hygiène, entretien, autre]
    total_estimé: number
    devise: string

# -----------------------------------------------
# Features
# -----------------------------------------------
features:

  - id: F01
    name: Authentication
    branch: feature/auth-register, feature/auth-login-logout
    spec: specs/auth.md
    user_stories:
      - id: US1
        title: Inscription / Connexion / Déconnexion
        description: >
          As a user, I want to create my account, log in and log out,
          so that my receipts are attached to me.
    routes:
      - GET /register
      - POST /register
      - GET /login
      - POST /login
      - POST /logout
    pages:
      - /register — form: name, email, password, confirm password
      - /login — form: email, password
    acceptance_criteria:
      - User can register with name, email, password
      - User can log in with email and password
      - User can log out
      - All recu routes are protected by auth middleware

  - id: F02
    name: Recus CRUD
    branch: feature/recus-list, feature/recus-submit, feature/recus-detail, feature/recus-delete
    spec: specs/recus-crud.md
    user_stories:
      - id: US2
        title: Liste des reçus
        description: >
          As a connected user, I want to see all my receipts with
          their formatted processing status and extracted expense count.
      - id: US3
        title: Soumettre un reçu
        description: >
          As a connected user, I want to paste supplier receipt text and
          launch extraction. A message must appear immediately — the page
          must never freeze while the AI works.
      - id: US4
        title: Voir le détail d'un reçu
        description: >
          As a connected user, I want to open a receipt and see the source
          text, its status, and the list of extracted expenses.
      - id: US5
        title: Supprimer un reçu
        description: >
          As a connected user, I want to delete a receipt and its
          associated expenses.
    routes:
      - GET    /recus              recus.index
      - GET    /recus/create       recus.create
      - POST   /recus              recus.store
      - GET    /recus/{recu}       recus.show
      - DELETE /recus/{recu}       recus.destroy
    validation:
      class: StoreRecuRequest
      rules:
        texte_brut: nullable|string|min:10|max:5000
        image: nullable|file|mimes:jpg,jpeg,png|max:5120
        _at_least_one: texte_brut or image required
    pages:
      - /recus — table with date, texte_brut excerpt, statut badge, depenses_count, actions
      - /recus/create — textarea for raw text + optional image upload
      - /recus/{id} — source text, statut badge, depenses table
    acceptance_criteria:
      - Submitting a recu dispatches a Job immediately and redirects
      - Page never freezes during AI extraction
      - Statut badge reflects real enum value (en_attente / traite / echoue)
      - Delete removes recu and all depenses (cascade)
      - User can only see and delete their own recus (policy)

  - id: F03
    name: Extraction IA
    branch: feature/ia-extraction-job, feature/ia-status-tracking
    spec: specs/extraction-ia.md
    user_stories:
      - id: US6
        title: Extraction structurée
        description: >
          As a connected user, when I submit a receipt, I want the AI to
          extract articles in guaranteed structured output, validated and
          saved in the database — one typed depense per article.
      - id: US7
        title: Suivi du traitement
        description: >
          As a connected user, I want to see the status of my receipt
          evolve (En attente → Traité), and in case of a problem, see a
          clear Échoué status instead of a blank page.
    job:
      class: ExtraireDepensesDuRecu
      path: app/Jobs/ExtraireDepensesDuRecu.php
      dispatched_from: RecuController@store
      steps:
        - Call laravel/ai SDK with structured output schema
        - Parse and validate returned JSON
        - Save each article as a Depense record
        - Update recu statut to traite
        - On exception: update recu statut to echoue
    acceptance_criteria:
      - Job is dispatched asynchronously — controller never waits
      - AI response always matches the JSON contract
      - Each article becomes one Depense row in DB
      - statut updates to traite on success, echoue on failure
      - Failed jobs never crash the app — graceful error handling

  - id: F04
    name: Dépenses List
    branch: feature/depenses-list-filter
    spec: specs/depenses-list.md
    user_stories:
      - id: US8
        title: Liste filtrable des dépenses
        description: >
          As a connected user, I want to see all my expenses with their
          formatted category and be able to filter by category.
    routes:
      - GET /depenses        depenses.index
      - GET /depenses?categorie=alimentaire
    pages:
      - /depenses — table with libelle, quantite, prix_unitaire, categorie badge, recu source
        filter: dropdown or buttons (Alimentaire / Boissons / Hygiène / Entretien / Autre)
    acceptance_criteria:
      - All depenses for the logged-in user are displayed
      - Filter by categorie works without page reload or with simple GET param
      - Categorie displayed as formatted badge not raw string

  - id: F05
    name: Queue & Traitement
    branch: chore/queue-worker-setup
    spec: specs/queue-traitement.md
    description: >
      Async processing via Laravel queues. The AI call is slow — the page
      must never block. The Job is dispatched immediately after Recu creation
      and processed by a worker in the background.
    commands:
      - php artisan queue:table
      - php artisan migrate
      - php artisan queue:work
    env:
      QUEUE_CONNECTION: database
    acceptance_criteria:
      - Worker processes jobs from the database queue
      - Failed jobs are logged and recu statut set to echoue
      - Controller returns immediately after dispatch

# -----------------------------------------------
# Bonus Features
# -----------------------------------------------
bonus:

  - id: B01
    name: Image Upload
    branch: feature/bonus-image-upload
    description: >
      Upload a photo of a receipt instead of pasting text.
      Uses Laravel File Storage and a multimodal AI model.
    changes:
      - recus.image_path: VARCHAR(255) NULL already in schema
      - texte_brut: nullable (either text or image required)
      - StoreRecuRequest: validate image file type and size
      - RecuController@store: store image via Storage::disk('public')
      - ExtraireDepensesDuRecu: detect image_path and send as base64 to AI

  - id: B02
    name: Pest Test
    branch: feature/bonus-pest-test
    description: >
      A Pest test on the extraction using the laravel/ai SDK fake.
      The test runs without a real Groq call — fast and deterministic.
    file: tests/Feature/ExtractionTest.php
    example: |
      it('extracts depenses from raw receipt text', function () {
          AiFacade::fake([...]);
          $recu = Recu::factory()->create(['texte_brut' => '...']);
          ExtraireDepensesDuRecu::dispatchSync($recu);
          expect($recu->fresh()->statut)->toBe(StatutRecu::Traite);
          expect($recu->depenses()->count())->toBeGreaterThan(0);
      });

# -----------------------------------------------
# Architecture Decisions
# -----------------------------------------------
architecture:
  decisions:
    - id: AD01
      title: Queue + Job for AI extraction
      reason: >
        The Groq API call is slow (1-5s). Without a queue, the user stares
        at a frozen page. The Job is dispatched immediately, the user sees
        a confirmation instantly, and the worker processes in the background.
        Never call the AI synchronously inside a controller.

    - id: AD02
      title: Structured output via laravel/ai SDK
      reason: >
        Si Brahim's data must always have the correct shape. The SDK guarantees
        a valid JSON contract — no json_decode that silently returns null,
        no missing fields, no broken saves. If the AI returns something outside
        the schema, the Job catches the exception and sets statut = echoue.

    - id: AD03
      title: Eloquent Casts for enums
      reason: >
        statut and categorie are closed value sets. Casting to PHP enums
        means data is typed from the database all the way to the view.
        No raw strings, no typos, no magic values scattered across the codebase.

    - id: AD04
      title: Form Request before AI call
      reason: >
        StoreRecuRequest validates the submitted text before dispatching the Job.
        This prevents wasting an API call on invalid input.
        Validation is always the first gate.

    - id: AD05
      title: cascadeOnDelete on foreign keys
      reason: >
        Deleting a Recu must delete all its Depenses (US5).
        Enforced at the database level, not in application code.

    - id: AD06
      title: withCount instead of with for index
      reason: >
        On the index page only the count of depenses is needed, not the records.
        withCount generates a single COUNT() subquery — zero N+1.
        with('depenses') would load all depense rows unnecessarily.

# -----------------------------------------------
# Routes Summary
# -----------------------------------------------
routes:
  middleware: auth
  resource:
    recus:
      controller: RecuController
      only: [index, create, store, show, destroy]
    depenses:
      controller: DepenseController
      only: [index]

# -----------------------------------------------
# Policies
# -----------------------------------------------
policies:
  RecuPolicy:
    model: Recu
    methods:
      view: user.id === recu.user_id
      delete: user.id === recu.user_id
  DepensePolicy:
    model: Depense
    methods:
      viewAny: authenticated user sees only their own depenses

# -----------------------------------------------
# Environment Variables
# -----------------------------------------------
env:
  GROQ_API_KEY: ~
  AI_DEFAULT_PROVIDER: groq
  QUEUE_CONNECTION: database
  FILESYSTEM_DISK: public

# -----------------------------------------------
# Branches
# -----------------------------------------------
branches:
  main: stable, demo-ready
  develop: integration branch
  feature:
    - feature/auth-register
    - feature/auth-login-logout
    - feature/recus-list
    - feature/recus-submit
    - feature/recus-detail
    - feature/recus-delete
    - feature/ia-extraction-job
    - feature/ia-status-tracking
    - feature/depenses-list-filter
    - feature/bonus-image-upload
    - feature/bonus-pest-test
  chore:
    - chore/laravel-setup-groq-config
    - chore/eloquent-casts
    - chore/queue-worker-setup
  docs:
    - docs/agents-md
    - docs/openspec-auth
    - docs/openspec-recus-crud
    - docs/openspec-extraction-ia
    - docs/mcd-mld

# -----------------------------------------------
# Commit Convention
# -----------------------------------------------
commits:
  format: "[AI] type: description"
  types:
    feat: new feature
    fix: bug fix
    chore: setup, config, refactoring
    docs: documentation only
    test: tests only
  examples:
    - "[AI] feat: generate RecuController scaffold via OpenCode"
    - "[AI] chore: generate migration for recus table"
    - "[AI] fix: correct enum cast on Depense model"
    - "feat: implement StoreRecuRequest validation"
    - "fix: eager load depenses on recu index to fix N+1"

# -----------------------------------------------
# Evaluation Checklist
# -----------------------------------------------
checklist:
  architecture:
    - Recu hasMany Depense relation defined and used correctly
    - StoreRecuRequest validates before AI call
    - Eloquent Casts functional (enum statut, enum categorie, array payload)
    - AI dispatched in Job processed by worker — page never freezes
    - AI called via laravel/ai SDK — result saved in DB
    - Zero N+1 verified with Debugbar
  features:
    - Complete authentication
    - Full Recus CRUD with visible processing status
    - Functional structured AI extraction — JSON contract respected
    - Status tracking (En attente / Traité / Échoué) reflects real state
    - Filterable expenses list by category
  workflow:
    - AGENTS.md present, complete and committed
    - specs/ folder managed with OpenSpec, at least 3 features documented
    - Commits with clear AI usage mention
    - Able to explain what the agent generated vs what was modified
    - Able to explain why structured output and Queue are there
  deliverables:
    - Jira board with tickets
    - MCD and MLD
    - GitHub repository with minimum 15 commits
    - Daily commits
    - Feature branches
    - specs/ folder
    - README.md