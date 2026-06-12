## Context

L'upload d'image est fonctionnel mais l'UX est rudimentaire. Les formulaires create et edit ont une zone de drop basique sans preview ni feedback. La liste des reçus n'affiche aucun indicateur visuel de présence d'image. Il n'existe aucun moyen de supprimer l'image seule — il faut supprimer tout le reçu. Cette change comble ces lacunes avec des améliorations ciblées sur les vues, le contrôleur, et un peu de JavaScript vanilla.

## Goals / Non-Goals

**Goals:**
- Preview image avant upload sur create et edit (FileReader API)
- Drag-and-drop avec feedback visuel
- Miniature dans la liste des reçus
- Suppression d'image via route dédiée
- JavaScript vanilla — pas de framework, pas de dépendance supplémentaire

**Non-Goals:**
- Multi-upload (un seul fichier par reçu pour l'instant)
- Redimensionnement/crop d'image
- Upload via AJAX (soumission traditionnelle)
- Gallery ou organisation d'images
- Optimisation/Metadata d'image

## Decisions

1. **FileReader API pour la preview** — Utiliser `FileReader.readAsDataURL()` en JavaScript vanilla. Pas de librairie externe. Le data URI est utilisé uniquement pour l'affichage, jamais stocké.

2. **Drag-and-drop avec event listeners natifs** — Les events `dragover`, `dragleave`, `drop` sont gérés sur la zone d'upload. Le `dataTransfer.files` remplit le file input.

3. **Route RESTful pour la suppression** — `DELETE /recus/{recu}/image` avec un nom de route `recus.image.destroy`. Pas de paramètre query. Policy réutilise `delete` (modifier un recu = supprimer son image).

4. **Miniature dans la liste** — `<img>` avec `w-10 h-10 rounded-lg object-cover` dans une nouvelle colonne "Doc". Fallback sur une icône `description` grisée si pas d'image.

5. **JavaScript dans la vue** — Inline `<script>` dans les sections `@push('scripts')` du layout sidebar. Pas de fichier JS séparé (taille minimale).

6. **Suppression logique via update** — La méthode `destroyImage()` dans le controller utilise `Storage::delete()` puis `$recu->update(['image_path' => null])`. Pas de nouvelle migration.

## Risks / Trade-offs

- [Risk] JavaScript inline peut devenir difficile à maintenir → Acceptable vu la taille (< 30 lignes par vue)
- [Risk] FileReader peut être lent sur des gros fichiers (10MB) → La preview est quasi-instantanée pour des images standards
- [Risk] Le drag-and-drop mobile a un comportement différent → `dragover`/`drop` fonctionnent sur desktop ; mobile utilise le file input normal
