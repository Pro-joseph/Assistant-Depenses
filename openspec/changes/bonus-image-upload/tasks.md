## 1. Image Upload UX

- [x] 1.1 Ajouter la preview JavaScript sur `create.blade.php` (FileReader + affichage dans la zone d'upload)
- [x] 1.2 Ajouter le drag-and-drop JavaScript sur `create.blade.php` (events dragover/dragleave/drop)
- [x] 1.3 Ajouter la preview et drag-and-drop sur `edit.blade.php` (même logique que create)
- [x] 1.4 Ajouter bouton "Supprimer la sélection" pour réinitialiser le file input

## 2. Image Deletion

- [x] 2.1 Ajouter la méthode `destroyImage(Recu $recu)` dans `RecuController` — supprime le fichier Storage, met `image_path` à null, redirige avec flash
- [x] 2.2 Ajouter la route `DELETE /recus/{recu}/image` nommée `recus.image.destroy` dans `routes/web.php`
- [x] 2.3 Ajouter le checkbox "Supprimer l'image" dans `edit.blade.php` (affiché uniquement si image existe)
- [x] 2.4 Gérer le cas "image non trouvée" (404) dans `destroyImage`

## 3. Image Display in List

- [x] 3.1 Ajouter une colonne "Doc" dans `recus/index.blade.php` avec miniature (40x40) si image existe
- [x] 3.2 Ajouter une icône de fallback (description/document) si pas d'image

## 4. Tests

- [x] 4.1 Tester la suppression d'image via la route dédiée
- [x] 4.2 Tester que la suppression d'image échoue (403) pour un autre utilisateur
