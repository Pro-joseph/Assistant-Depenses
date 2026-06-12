## 1. Form Request Validation

- [x] 1.1 Add rules to `StoreRecuRequest`: require at least one of `texte_brut` or `image`, max length 10000 for texte_brut, image validation (jpg/png/webp, max 10MB)
- [x] 1.2 Add rules to `UpdateRecuRequest`: all fields optional, max length 10000 for texte_brut, image validation if provided

## 2. Authorization Policy

- [x] 2.1 Fix `RecuPolicy::viewAny()` — return true (authenticated users see their own list)
- [x] 2.2 Fix `RecuPolicy::view()` — check `$user->id === $recu->user_id`
- [x] 2.3 Fix `RecuPolicy::create()` — return true (all authenticated users can create)
- [x] 2.4 Fix `RecuPolicy::update()` — check `$user->id === $recu->user_id`
- [x] 2.5 Fix `RecuPolicy::delete()` — check `$user->id === $recu->user_id`

## 3. Controller Implementation

- [x] 3.1 Implement `store()`: validate with StoreRecuRequest, create Recu, handle image upload, dispatch AI job, redirect to index
- [x] 3.2 Implement `show()`: eager load depenses, return show view, add `$this->authorize('view', $recu)`
- [x] 3.3 Implement `edit()`: return edit view with $recu, add `$this->authorize('update', $recu)`
- [x] 3.4 Implement `update()`: validate with UpdateRecuRequest, update Recu, handle image replacement, reset statut to en_attente, redirect
- [x] 3.5 Implement `destroy()`: authorize, delete image file if exists, delete Recu (cascade deletes depenses), redirect to index with flash

## 4. Routes

- [x] 4.1 Update `web.php` — change resource route from `->only([...])` to full resource (remove `->only()`)

## 5. Views

- [x] 5.1 Fix `index.blade.php`: replace `$recu->date` with `$recu->created_at`, `$recu->name` with `$recu->id`, use `$recu->depenses_count` (via withCount), render dynamic status badges based on `$recu->statut`
- [x] 5.2 Create `show.blade.php`: display receipt metadata (status, date, texte_brut, image), table of associated depenses, action buttons (edit/delete)
- [x] 5.3 Create `edit.blade.php`: form pre-filled with existing Recu data, textarea for texte_brut, file upload for image, submit button

## 6. Controller Housekeeping

- [x] 6.1 Add `$this->authorize()` calls to existing `index()` and `create()` methods
- [x] 6.2 Add `withCount('depenses')` to `index()` query for the view

## 7. Testing

- [x] 7.1 Write feature test for store (validation passes, validation fails, image upload)
- [x] 7.2 Write feature test for show (own recu, other's recu, non-existent)
- [x] 7.3 Write feature test for update (partial update, full update, authorization)
- [x] 7.4 Write feature test for delete (own recu, other's recu, cascade)
- [x] 7.5 Write feature test for policy enforcement on all routes
