## ADDED Requirements

### Requirement: User can edit an existing receipt
The system SHALL allow authenticated users to update their own receipts, including modifying the raw text, replacing the image, and changing the status.

#### Scenario: Updating text only
- **WHEN** user edits the `texte_brut` field on their own receipt and submits
- **THEN** the system updates the text, resets `statut` to `en_attente`, and redirects with a success message

#### Scenario: Replacing the image
- **WHEN** user uploads a new image on an existing receipt
- **THEN** the system stores the new image, updates `image_path`, and deletes the old image file

#### Scenario: Removing the image
- **WHEN** user submits the edit form without an image on a receipt that previously had one
- **THEN** the system keeps the existing image (image update is only on replacement)

#### Scenario: Editing another user's receipt
- **WHEN** user tries to edit a receipt owned by another user
- **THEN** the system returns a 403 Forbidden response

#### Scenario: Invalid update data
- **WHEN** user submits text exceeding 10,000 characters
- **THEN** the system shows a validation error for the `texte_brut` field
