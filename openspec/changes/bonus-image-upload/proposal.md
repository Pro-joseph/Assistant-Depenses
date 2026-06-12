## Why

L'upload d'image existe déjà (create/edit stocke `image_path`), mais l'expérience utilisateur est basique : pas de prévisualisation avant upload, pas de drag-and-drop, pas d'affichage dans la liste des reçus, et aucune façon de supprimer l'image sans supprimer le reçu. Cette change améliore l'ensemble du parcours utilisateur autour des images.

## What Changes

- **Prévisualisation immédiate** — Afficher un aperçu de l'image sélectionnée avant soumission (create + edit)
- **Drag-and-drop** — Zone de drop active avec retour visuel (highlight, ondulations)
- **Affichage dans la liste** — Miniature/image indicator dans le tableau des reçus (index)
- **Suppression d'image** — Bouton pour supprimer l'image seule depuis la page d'édition
- **Route dédiée** — Nouvelle route `DELETE /recus/{recu}/image` pour la suppression d'image
- **Nom du fichier** — Afficher le nom et la taille du fichier sélectionné

## Capabilities

### New Capabilities
- `image-upload-ux`: Amélioration de l'interface d'upload avec preview, drag-and-drop, et feedback utilisateur
- `image-deletion`: Suppression d'une image attachée à un reçu sans supprimer le reçu
- `image-display-list`: Affichage des miniatures/images dans la liste des reçus

### Modified Capabilities
*(Aucune spec existante n'est modifiée — les changements sont des ajouts.)*

## Impact

- `resources/views/recus/create.blade.php` — Upload zone with preview, drag-and-drop
- `resources/views/recus/edit.blade.php` — Upload zone with preview, drag-and-drop, remove image button
- `resources/views/recus/index.blade.php` — Thumbnail column in table
- `resources/views/recus/show.blade.php` — (déjà OK, inchangé)
- `app/Http/Controllers/RecuController.php` — New `destroyImage()` method
- `routes/web.php` — New route for image deletion
