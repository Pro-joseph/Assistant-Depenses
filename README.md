# Assistant Dépenses

Extract structured expense data from raw supplier receipts written in **Darija** (Moroccan Arabic) using AI.

Paste receipt text or upload a photo — the app sends it to Groq's LLM, which returns clean line items with categories, quantities, and prices. No manual entry needed.

---

## Features

| # | Feature | Description |
|---|---|---|
| 1 | **Authentication** | Register, log in, log out via Laravel Breeze (Blade + Alpine) |
| 2 | **Receipt List** | View all your receipts with status badges; search by text or ID |
| 3 | **Submit a Receipt** | Paste raw Darija text or upload a photo — AI extraction is dispatched in the background |
| 4 | **Receipt Detail** | View the original text, status, and extracted expense lines |
| 5 | **Delete a Receipt** | Delete a receipt and all its expenses (cascading) |
| 6 | **AI Extraction** | Groq's LLM extracts structured `{libellé, quantité, prix_unitaire, catégorie}` items |
| 7 | **Status Tracking** | Real-time status per receipt: `en_attente → traite` or `echoue` |
| 8 | **Expense List & Filter** | Browse all expenses, filter by category, search by keyword, see monthly totals |
| ★ | **Image Upload** | Upload a receipt photo — vision AI OCR provides text when none is pasted |
| ★ | **Test Suite** | 37+ Pest tests covering auth, CRUD, AI extraction, filtering, and image upload |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | **[Laravel 13](https://laravel.com)** |
| Language | PHP 8.3 |
| Database | MySQL 8.4 |
| AI SDK | `laravel/ai` — provider **Groq** (model: `meta-llama/llama-4-scout-17b-16e-instruct`) |
| Queue | Database driver (async job processing) |
| Auth | Laravel Breeze (Blade stack) |
| Frontend | Blade + **Tailwind CSS 4** + **Alpine.js 3** |
| Testing | **[Pest PHP](https://pestphp.com)** |
| Storage | Local disk (`storage/app/public`) |

---

## Requirements

- PHP **8.3** or higher
- [Composer](https://getcomposer.org)
- Node.js **18+** and npm
- MySQL **8.4**
- A [Groq API key](https://console.groq.com) (free tier available)

---

## Installation

```bash
# 1. Clone the repository
git clone <repo-url> assistant-depenses
cd assistant-depenses

# 2. Install PHP dependencies
composer install

# 3. Create environment file
cp .env.example .env

# 4. Configure your .env
#    Set database credentials, GROQ_API_KEY, QUEUE_CONNECTION=database

# 5. Generate application key
php artisan key:generate

# 6. Create storage symlink for uploaded images
php artisan storage:link

# 7. Run migrations
php artisan migrate

# 8. (Optional) Seed a test user (test@example.com / password)
php artisan db:seed

# 9. Install and build frontend assets
npm install && npm run build

# 10. Start the queue worker (keep this running in a separate terminal)
php artisan queue:work

# 11. Start the development server
php artisan serve
```

> **Tip:** You can run `composer setup` to automate steps 2, 5, 6, 7, and 9 in one command.

---

## Development

Run all three services concurrently (PHP server, queue worker, Vite dev server):

```bash
composer dev
```

### Testing

```bash
composer test
```

This runs the full Pest test suite (37+ tests) using an in-memory SQLite database and a fake AI driver — no external API calls required.

---

## Queue Worker

The AI extraction runs asynchronously via Laravel's queue system. After submitting a receipt:

1. The controller validates input and creates a `Recu` with `statut = en_attente`
2. The `ExtraireDepensesDuRecu` job is dispatched to the queue
3. The user immediately sees "En cours de traitement" — **the page never freezes**
4. The worker processes the job, calls Groq, and updates the receipt status

**Always keep the queue worker running** during development:

```bash
php artisan queue:work
```

---

## Routes

### Web Routes (authenticated)

| Method | URI | Name | Description |
|---|---|---|---|
| GET | `/` | — | Redirects to receipts list (auth) or welcome page (guest) |
| GET | `/recus` | `recus.index` | List all receipts (searchable with `?q=`) |
| GET | `/recus/create` | `recus.create` | Show receipt submission form |
| POST | `/recus` | `recus.store` | Submit a new receipt (text + optional image) |
| GET | `/recus/{recu}` | `recus.show` | View receipt detail + extracted expenses |
| GET | `/recus/{recu}/edit` | `recus.edit` | Edit receipt form |
| PUT/PATCH | `/recus/{recu}` | `recus.update` | Update receipt data or reupload image |
| DELETE | `/recus/{recu}` | `recus.destroy` | Delete receipt (cascades to expenses) |
| GET | `/recus/statuts` | `recus.statuts` | JSON endpoint for status polling |
| DELETE | `/recus/{recu}/image` | `recus.image.destroy` | Delete uploaded image |
| GET | `/depenses` | `depenses.index` | List/filter expenses (`?categorie=`, `?q=`) |
| GET | `/dashboard` | `dashboard` | Redirects to `/depenses` |
| GET/PATCH/DELETE | `/profile` | `profile.*` | Breeze user profile management |

### Auth Routes (guest / authenticated)

All standard Breeze routes: `/register`, `/login`, `/forgot-password`, `/reset-password`, `/verify-email`, `/confirm-password`, `/logout`.

---

## How It Works

```
User pastes text ──► StoreRecuRequest validates ──► Recu created (statut = en_attente)
                                                        │
                                                   ExtraireDepensesDuRecu job dispatched
                                                        │
                                              ┌─────────┴─────────┐
                                              │   Queue Worker     │
                                              │  (php artisan      │
                                              │   queue:work)      │
                                              └─────────┬─────────┘
                                                        │
                                              Calls Groq AI via laravel/ai
                                                        │
                                              ┌─────────┴─────────┐
                                              │  Success?          │
                                              ├─────────────────────┤
                                              │  Yes: Create       │
                                              │  Depense records   │
                                              │  statut = traite   │
                                              ├─────────────────────┤
                                              │  No: statut =      │
                                              │  echoue (error     │
                                              │  logged)          │
                                              └───────────────────┘
```

### AI Output Contract

The AI is instructed to return **exactly** this JSON structure:

```json
{
  "articles": [
    {
      "libellé": "string",
      "quantité": "integer",
      "prix_unitaire": "number",
      "catégorie": "alimentaire | boissons | hygiène | entretien | autre"
    }
  ],
  "total_estimé": "number",
  "devise": "string"
}
```

The SDK's structured output mode enforces this schema. Any malformed response sets `statut = echoue` — partial or invalid data is never persisted.

---

## Architecture Highlights

| Decision | Rationale |
|---|---|
| **Business logic in Jobs** | Controllers are thin: validate → authorize → dispatch → return view. The `ExtraireDepensesDuRecu` job holds all extraction logic. |
| **Queue for AI calls** | Groq takes 1–5s. Without a queue, the page would freeze. The user sees instant feedback; the worker processes asynchronously. |
| **Enum casts** | `StatutRecu` and `CategorieDepense` are PHP enums cast via Eloquent — no raw strings, no typos, full type safety. |
| **Form Requests** | `StoreRecuRequest` validates input before the job is dispatched, preventing wasted API calls on invalid data. |
| **Cascade deletes** | Deleting a `Recu` also deletes all its `Depense` records at the database level. Same for `User → Recus`. |
| **Structured AI output** | The SDK guarantees valid JSON — no `json_decode` that silently returns `null`. |
| **Policies** | Every route is protected; users can only see/edit/delete their own receipts. Depenses are read-only (managed by the job). |

---

## Project Structure (key directories)

```
├── app/
│   ├── Enums/                   # StatutRecu, CategorieDepense
│   ├── Http/
│   │   ├── Controllers/         # RecuController, DepenseController
│   │   └── Requests/            # StoreRecuRequest, UpdateRecuRequest
│   ├── Jobs/                    # ExtraireDepensesDuRecu
│   ├── Models/                  # User, Recu, Depense
│   └── Policies/                # RecuPolicy, DepensePolicy
├── config/
├── database/
│   ├── factories/               # UserFactory, RecuFactory, DepenseFactory
│   ├── migrations/
│   └── seeders/
├── openspec/                    # Feature specifications (OpenSpec)
├── resources/views/             # Blade templates
├── routes/
│   ├── web.php                  # Application routes
│   └── auth.php                 # Breeze auth routes
├── tests/                       # Pest test suite
└── AGENTS.md                    # AI agent guide & conventions
```

---

## Environment Variables

| Variable | Required | Description |
|---|---|---|
| `GROQ_API_KEY` | Yes | Groq API key for AI extraction |
| `AI_DEFAULT_PROVIDER` | Yes | Set to `groq` |
| `QUEUE_CONNECTION` | Yes | `database` for local, `redis` for production |
| `FILESYSTEM_DISK` | Yes | `public` for image uploads |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Yes | MySQL connection |

---

## Architecture Decisions

Detailed rationale for every key decision is documented in [`AGENTS.md`](./AGENTS.md) — read it to understand why the project is structured the way it is.

---

## Testing

```bash
composer test
```

The test suite uses:
- SQLite in-memory database
- Synchronous queue driver (jobs run immediately)
- Array cache and session drivers
- A fake AI driver (no real API calls)

This makes tests fast, isolated, and runnable without any external service.

---

## License

This project is open source.
