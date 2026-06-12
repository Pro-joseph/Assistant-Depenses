## Context

The Recu (receipt) management system has scaffolded Laravel components (controller, model, migration, two views) but critical CRUD paths are unimplemented. `store()` is an empty stub, `show()` references a missing view, `edit()`/`update()`/`destroy()` are not routed. Policies return `false` for all actions. Form requests have empty validation rules. The index view references non-existent model properties (`$recu->date`, `$recu->name`).

This change completes all CRUD operations, adds proper authorization, and ensures the UI correctly displays dynamic statuses. It is a prerequisite for the AI extraction job (US6) which dispatches from `store()`.

## Goals / Non-Goals

**Goals:**
- Full RESTful CRUD for Recu: index, create, store, show, edit, update, destroy
- Policy-based authorization: users can only access their own Recus
- Input validation: StoreRecuRequest requires at least one of `texte_brut` or `image_path`; UpdateRecuRequest validates optional edits
- Image upload handling in store and update (stored via Laravel File Storage on the `public` disk)
- Correct dynamic rendering: index view uses real model attributes and conditional status badges
- All controller methods gated with `$this->authorize()` calls

**Non-Goals:**
- AI extraction job implementation (US6) — only the dispatch call is added to `store()`
- Depense CRUD — this change focuses on Recu only
- Queue worker setup — assumed already configured per AGENTS.md
- Styling of existing views — only functional fixes and status badge rendering
- Soft deletes or restore functionality

## Decisions

1. **Resource route over manual routes** — Use `Route::resource('recus', RecuController::class)` without `->only()` to register all 7 RESTful routes. This is cleaner than registering each route manually and follows Laravel conventions.

2. **Image upload to `public` disk** — Store uploaded receipt images under `recus/{id}/` on the `public` disk. The `image_path` column stores the relative path. We avoid S3/local confusion by using the filesystem disk from config (`FILESYSTEM_DISK=public` per AGENTS.md).

3. **Policy uses `user_id` comparison** — `RecuPolicy::view()` checks `$user->id === $recu->user_id`, same for `update` and `delete`. `create` always returns true. `viewAny` returns true (authenticated users can see their own list). This is simpler than a Gate-based approach and follows Laravel best practices.

4. **StoreRecuRequest conditional validation** — Use `sometimes` + `required_without` for `texte_brut` and `image_path` so at least one must be present. Max length for `texte_brut` is 10000 chars. `image` must be a valid image file under 10MB.

5. **UpdateRecuRequest allows partial updates** — All fields are optional in update. If `texte_brut` is provided, max length 10000. If `image` is provided, must be a valid image under 10MB. If neither is provided, the update still succeeds (only metadata changes like statut).

6. **withCount for depenses on index** — Uses `Recu::withCount('depenses')` to avoid N+1 and provide `$recu->depenses_count` to the view, replacing the non-existent attribute reference.

7. **Status badges via Blade conditional** — Use `@switch($recu->statut)` to render color-coded badges (en_attente → yellow, traite → green, echoue → red) instead of the current hardcoded "Traité".

8. **show view uses eager loading** — `Recu::with('depenses')->findOrFail($id)` to load the Recu with all its Depenses for the detail view.

9. **destroy returns to index** — After successful deletion, redirect to `recus.index` with a success flash message. Uses `authorize('delete', $recu)` before the delete call.

## Risks / Trade-offs

- [Risk] Deleting a Recu with many Depenses could be slow if cascade is handled in app code → Mitigation: `cascadeOnDelete` on the migration FK already handles this at the DB level, so deletion is fast.
- [Risk] Image upload can fail silently if the filesystem disk is misconfigured → Mitigation: Wrap upload in try-catch and flash an error message; don't let a failed upload prevent the Recu from being saved.
- [Trade-off] Using `->all()` route methods instead of manual registration trades explicitness for conciseness. Some routes (like `edit`) serve both GET (form) and PUT (update) — this is standard Laravel resource behavior.
- [Risk] Policy returning `false` by default for unauthenticated access could cause 403 errors if a route is misconfigured → Mitigation: All routes are under `auth` middleware group, and policies check authentication before proceeding.
