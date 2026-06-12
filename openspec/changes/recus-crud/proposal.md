## Why

The Recu (receipt) CRUD is the core workflow of the application — without fully working create, read, update, and delete operations, the merchant cannot manage their receipts. Currently the scaffolding exists (controller, model, migration, partial views) but `store`, `show`, `update`, and `destroy` are all broken or empty. Completing CRUD is a prerequisite for US2–US5 and unblocks the AI extraction pipeline (US6).

## What Changes

- **Store (create → save)** — Implement `RecuController::store()` to validate input, persist the Recu, handle optional image upload, and dispatch the AI extraction job.
- **Show (detail view)** — Create the missing `recus/show.blade.php` view and fix the controller's `show()` method to load related depenses.
- **Edit/Update** — Register `edit` and `update` routes, implement `RecuController::edit()` and `update()`, create the edit view, and build a `UpdateRecuRequest` with proper validation.
- **Destroy (delete)** — Register `destroy` route, implement `RecuController::destroy()` with cascade-aware deletion, and add a delete button/confirmation in the list and detail views.
- **Policy enforcement** — Fix `RecuPolicy` so authenticated users can view/create/update/delete only their own Recus, and gate all controller methods with `$this->authorize()`.
- **Form request validation** — Complete `StoreRecuRequest` rules (at least one of `texte_brut` or `image_path` required, length bounds) and `UpdateRecuRequest` rules.
- **Index view fixes** — Replace references to non-existent `$recu->date`, `$recu->name`, `$recu->depenses_count` with valid model attributes (`created_at`, `id`, `depenses_count` via withCount).
- **Status badge rendering** — Make the index view display dynamic status badges based on the `statut` enum rather than hardcoded "Traité".
- **Navigation & layout** — Ensure sidebar navigation links are correct and the layout supports all new views.

## Capabilities

### New Capabilities

- `recus-store`: Validate and persist a new Recu with optional image upload
- `recus-detail`: View a single Recu with its associated Depenses
- `recus-edit`: Update an existing Recu (texte brut, image, metadata)
- `recus-delete`: Remove a Recu and cascade-delete its Depenses
- `recus-authorization`: Policy-based access control for Recu CRUD operations

### Modified Capabilities

*(No existing specs to modify — specs directory does not exist yet.)*

## Impact

- **Controllers**: `RecuController` — all stubs filled, policy gating added
- **Models**: `Recu` — add `withCount('depenses')` or similar for index view
- **Policies**: `RecuPolicy` — all methods updated from `return false` to real logic
- **Form Requests**: `StoreRecuRequest`, `UpdateRecuRequest` — validation rules added
- **Routes**: `web.php` — add `edit`, `update`, `destroy` to the resource route
- **Views**: New `recus/show.blade.php`, `recus/edit.blade.php`; update `recus/index.blade.php`
- **Tests**: New feature tests for each CRUD operation
