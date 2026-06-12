## 1. Enum et Model

- [x] 1.1 Créer `app/Enums/CategorieDepense.php` — enum string-backed avec les cas : alimentaire, boissons, hygiene, entretien, autre
- [x] 1.2 Ajouter `$casts` au modèle Depense : 'categorie' => CategorieDepense::class, 'quantite' => 'integer', 'prix_unitaire' => 'float'
- [x] 1.3 Ajouter la relation `belongsTo(Recu::class)` au modèle Depense
- [x] 1.4 Ajouter les scopes locaux `scopeCategorie($query, $categorie)` et `scopeSearch($query, $term)` au modèle Depense

## 2. Policy

- [x] 2.1 Corriger `DepensePolicy::viewAny()` — retourner `true`
- [x] 2.2 Corriger `DepensePolicy::view()` — vérifier `$user->id === $depense->recu->user_id`

## 3. Controller

- [x] 3.1 Implémenter `DepenseController::index()` : requête paginée avec filtres (categorie, q), eager load recu, stats agrégées, passage à la vue

## 4. Vue

- [x] 4.1 Remplacer `resources/views/depenses/index.blade.php` : tableau dynamique avec `$depenses`, colonnes (libellé, montant, catégorie, date, source), pagination, cartes résumé
- [x] 4.2 Ajouter les boutons de filtre par catégorie avec `request()->fullUrlWithQuery()`
- [x] 4.3 Intégrer la recherche textuelle dans le champ de recherche du layout

## 5. Tests

- [x] 5.1 Tester l'affichage de la liste paginée
- [x] 5.2 Tester le filtre par catégorie
- [x] 5.3 Tester la recherche textuelle
- [x] 5.4 Tester la combinaison filtre + recherche
- [x] 5.5 Tester la policy (vue de sa propre dépense vs celle d'un autre)
