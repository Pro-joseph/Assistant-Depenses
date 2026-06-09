# AGENTS.md — Assistant Dépenses

## Project overview

Assistant Dépenses is a Laravel application that helps a neighborhood merchant
extract structured expense data from raw supplier receipts written in Darija.
The user pastes raw text (or uploads a photo), and the app uses an AI model via
the `laravel/ai` SDK to extract structured line items, stored as typed Depense
records linked to a Recu.

---

## Tech stack

| Layer | Choice |
|---|---|
| Framework | Laravel 13 |
| Language | PHP 8.3 |
| Database | MySQL 8.4 |
| AI SDK | `laravel/ai` (provider: Groq) |
| Queue driver | Database (local) / Redis (prod) |
| Auth | Laravel Breeze |
| Testing | Pest |
| Frontend | Blade + Tailwind CSS |
| Storage | Laravel File Storage (local disk) |

---


---

## Architecture decisions

### Why a Queue + Job for AI extraction?
The Groq API call is slow (1–5s). Without a queue, the user stares at a frozen
page waiting for the response. The Job `ExtraireDepensesDuRecu` is dispatched
immediately after the Recu is created, the user sees "En cours de traitement"
instantly, and the worker processes the extraction in the background.
**Never call the AI synchronously inside a controller.**

### Why structured output via `laravel/ai`?
Si Brahim's data must always have the correct shape. The SDK guarantees a valid
JSON contract — no `json_decode` that silently returns null, no missing fields,
no broken saves. If the AI returns something outside the schema, the Job catches
the exception and sets `statut = echoue`.

### Why Eloquent Casts for enums?
`statut` and `categorie` are closed value sets. Casting them to PHP enums
(`StatutRecu`, `CategorieDepense`) means the data is typed from the database
all the way to the view — no raw strings, no typos, no magic values scattered
across the codebase.

### Why Form Request before the AI call?
`StoreRecuRequest` validates that the submitted text is non-empty and within
length bounds **before** dispatching the Job. This prevents wasting an API call
on invalid input. Validation is always the first gate.

### Why `cascadeOnDelete` on foreign keys?
Deleting a Recu must delete all its Depenses (US5). Deleting a User must delete
all their Recus and Depenses. This is enforced at the database level, not in
application code.

---

## JSON contract (AI output)

The AI must always return this exact structure:

```json
{
  "articles": [
    {
      "libellé": "string",
      "quantité": "integer",
      "prix_unitaire": "number",
      "catégorie": "enum: alimentaire | boissons | hygiène | entretien | autre"
    }
  ],
  "total_estimé": "number",
  "devise": "string"
}
```

Any response outside this schema must be caught and result in `statut = echoue`.
Never save partial or malformed data.

---

## Branch strategy

| Branch | Purpose |
|---|---|
| `main` | Stable, demo-ready |
| `develop` | Integration branch |
| `feature/auth-register` | US1 register |
| `feature/auth-login-logout` | US1 login/logout |
| `feature/recus-list` | US2 |
| `feature/recus-submit` | US3 |
| `feature/recus-detail` | US4 |
| `feature/recus-delete` | US5 |
| `feature/ia-extraction-job` | US6 |
| `feature/ia-status-tracking` | US7 |
| `feature/depenses-list-filter` | US8 |
| `chore/laravel-setup-groq-config` | Initial setup |
| `chore/eloquent-casts` | Enums + casts |
| `chore/queue-worker-setup` | Queue config |
| `docs/agents-md` | This file |
| `docs/openspec-*` | Specs per feature |
| `feature/bonus-image-upload` | Bonus |
| `feature/bonus-pest-test` | Bonus |

---

## Commit message convention

```
[AI] feat: generate RecuController scaffold via OpenCode
[AI] chore: generate migration for recus table
[AI] fix: correct enum cast on Depense model
feat: implement StoreRecuRequest validation
fix: eager load depenses on recu index to fix N+1
```

- Prefix `[AI]` on every commit where an agent generated or significantly
  modified the code.
- Be explicit about what the agent did vs what you changed manually.

---

## AI workflow rules

1. **Plan before Build** — for every feature, run the agent in Plan mode first.
   Review the plan, adjust if needed, then switch to Build mode.
2. **Never accept agent output blindly** — read every generated file before
   committing. You must be able to explain any line during the demo.
3. **Specs first** — every feature must have a spec file in `specs/` committed
   before the agent writes code for it.
4. **One feature per session** — do not ask the agent to build multiple features
   in one prompt. Scope = one branch = one spec = one agent session.

---

## Coding rules for agents

- All business logic lives in Jobs or dedicated service classes, never in
  controllers.
- Controllers are thin: validate → authorize → dispatch/query → return view.
- Always use `with('depenses')` when loading a Recu to avoid N+1.
- Never call `json_decode` directly on AI output — use the SDK structured output.
- Every new route must be covered by the matching Policy method.
- `texte_brut` and `image_path` are both nullable — `StoreRecuRequest` must
  enforce that at least one is present.

---

## Key models and relationships

```
User         hasMany    Recu
Recu         belongsTo  User
Recu         hasMany    Depense
Depense      belongsTo  Recu
```

---

## Environment variables required

```env
GROQ_API_KEY=
AI_DEFAULT_PROVIDER=groq
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
```

---

## Evaluation checkpoints

Before the demo, verify:

- [ ] `php artisan queue:work` is running and processes jobs correctly
- [ ] Submitting a reçu never blocks the page
- [ ] A failed AI response sets `statut = echoue`, never crashes
- [ ] No N+1 queries — verify with Laravel Debugbar
- [ ] All routes are protected by auth middleware and policies
- [ ] `specs/` folder has at least 3 OpenSpec files committed
- [ ] Every AI-assisted commit has the `[AI]` prefix