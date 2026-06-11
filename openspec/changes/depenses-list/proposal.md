## Why

Les dépenses listées dans le tableau de bord sont actuellement en données statiques (hardcodées) sans lien avec la base de données. Le commerçant ne peut ni filtrer, ni rechercher, ni voir ses vraies dépenses extraites des reçus. Cette change remplace les fausses données par un listing dynamique, paginé, filtrable par catégorie et interrogeable via recherche.

## What Changes

- **DepenseController::index()** — requête paginée des dépenses de l'utilisateur connecté, avec filtres par catégorie et recherche textuelle
- **Depense model** — ajout de la relation `belongsTo(Recu::class)`, des casts Eloquent (`categorie` → `CategorieDepense`, types natifs)
- **PHP Enums** — création de `CategorieDepense` (alimentaire, boissons, hygiene, entretien, autre)
- **DepensePolicy** — méthodes retournant `true` pour `viewAny` et `view` (les utilisateurs voient leurs propres dépenses via le scope du controller)
- **Vue index** — remplacement des données statiques par `$depenses` paginées, dynamiques, avec filtres fonctionnels
- **Filtres** — barre de filtres par catégorie, champ de recherche textuelle sur le libellé
- **Pagination** — pagination fonctionnelle réutilisant le pattern des reçus
- **Suppression des routes inutilisées** — nettoyage des stubs `create/store/show/edit/update/destroy` du controller (non nécessaires pour l'US8)

## Capabilities

### New Capabilities

- `depenses-listing`: Afficher la liste paginée des dépenses de l'utilisateur connecté
- `depenses-filter`: Filtrer les dépenses par catégorie (boutons de filtre)
- `depenses-search`: Rechercher une dépense par texte dans le libellé
- `categorie-depense-enum`: Créer l'enum PHP `CategorieDepense` avec les valeurs de la base

### Modified Capabilities

*(Aucune spec existante à modifier — le dossier specs/ n'existe pas encore.)*

## Impact

- **Controller**: `DepenseController` — implémentation de `index()` avec pagination + filtres
- **Model**: `Depense` — ajout de `belongsTo Recu`, `$casts`, `$table` si nécessaire
- **Enum**: Nouveau fichier `app/Enums/CategorieDepense.php`
- **Policy**: `DepensePolicy` — correction des méthodes (retourne `true` pour viewAny/view)
- **View**: `depenses/index.blade.php` — remplacement complet par données dynamiques
- **Routes**: Inchangé (seulement `index` reste routé)
