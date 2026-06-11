## Context

Le tableau de bord des dépenses affiche actuellement des données statiques en dur (5 lignes fictives), sans lien avec la base de données. Le controller `DepenseController::index()` retourne une vue sans aucune variable. Le modèle `Depense` n'a pas de relation `belongsTo(Recu)`, ni de casts Eloquent. Aucun enum PHP n'existe pour `CategorieDepense`. La pagination, le filtrage par catégorie et la recherche textuelle sont inexistants. Ce change rend la page fonctionnelle en affichant les vraies dépenses de l'utilisateur connecté.

## Goals / Non-Goals

**Goals:**
- Listing dynamique paginé des dépenses de l'utilisateur (via Recu->user_id)
- Filtre par catégorie via query param `?categorie=alimentaire`
- Recherche textuelle sur le libellé via query param `?q=motclef`
- Combinaison des filtres (catégorie + recherche simultanément)
- Enum PHP `CategorieDepense` avec cast Eloquent sur le modèle
- Relation `belongsTo(Recu)` sur le modèle Depense
- Policy corrigée pour autoriser `viewAny` et `view`
- Réutilisation du pattern Blade du listing des reçus (pagination, layout)

**Non-Goals:**
- CRUD complet des dépenses (create/store/show/edit/update/destroy) — seule la liste est concernée
- Ajout de catégories manquantes dans la migration (la migration existante avec `enum` reste inchangée)
- Interface de modification des dépenses individuelles
- Export CSV/PDF (bouton "Exporter" inactif conservé)
- Livewire ou Alpine.js (tout est en backend Blade pur)

## Decisions

1. **Query parameters pour filtres** — `?categorie=alimentaire&q=sucre` plutôt que des routes nommées séparées. Plus simple, RESTful, et facile à combiner. Les liens de filtre sont générés avec `request()->fullUrlWithQuery()` pour préserver les autres paramètres.

2. **Filtrage via scopes locaux Eloquent** — Définir `scopeCategorie($query, $categorie)` et `scopeSearch($query, $term)` sur le modèle `Depense`. Cela garde le controller lisible et les scopes réutilisables.

3. **Catégorie "Toutes" par défaut** — Quand `categorie` n'est pas fourni, aucune clause WHERE n'est appliquée. Le bouton "Toutes" est actif par défaut.

4. **Paginator 15 items/page** — 15 dépenses par page (vs 10 pour les reçus) car les dépenses sont plus nombreuses et plus courtes.

5. **Enum CategorieDepense rétrocompatible** — L'enum PHP utilise `string` backed enum avec les mêmes valeurs que la migration DB (`alimentaire`, `boissons`, `hygiene`, `entretien`, `autre`). Le cast Eloquent `categorie` → `CategorieDepense` est ajouté au modèle.

6. **Policy simplifiée** — `viewAny` retourne `true` (le controller filtre par `user_id` via Recu), `view` retourne `$user->id === $depense->recu->user_id`. Les autres méthodes (`create`, `update`, `delete`, `restore`, `forceDelete`) retournent `false` car non implémentées.

7. **Relation Depense→Recu** — Ajout de `belongsTo(Recu::class)` avec `categorie` cast, `quantite` en `integer`, `prix_unitaire` en `float`/`decimal`.

8. **Statistiques en haut de page** — Les cartes de résumé (total dépenses, nombre par catégorie) sont calculées à partir des dépenses de l'utilisateur via des requêtes agrégées dans le controller.

## Risks / Trade-offs

- [Risk] Les filtres et la recherche peuvent retourner 0 résultats → Mitigation: Afficher un message "Aucune dépense trouvée" similaire au pattern `@empty` des reçus.
- [Risk] La page peut devenir lente avec beaucoup de dépenses → Mitigation: Pagination à 15 items, indexation future possible sur `libelle` et `categorie`.
- [Trade-off] Policy qui ne vérifie pas directement l'ownership →  On s'appuie sur le scope du controller (`where('user_id', auth()->id())` via Recu). C'est suffisant car l'US8 ne couvre que la liste.
- [Risk] Les noms de catégories dans les boutons de filtre (français) diffèrent des valeurs enum (anglais) → Mitigation: Utiliser un mapping dans le view composer ou directement dans la vue avec un tableau de traduction.
