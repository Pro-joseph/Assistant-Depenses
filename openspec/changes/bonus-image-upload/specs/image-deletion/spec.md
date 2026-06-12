## ADDED Requirements

### Requirement: User can delete image without deleting the recu
The system SHALL allow a user to remove the image attached to their recu without deleting the recu itself.

#### Scenario: User removes image from edit page
- **WHEN** the user checks "Supprimer l'image" on the edit form and submits
- **THEN** the image is deleted from storage
- **AND** `image_path` on the recu is set to null
- **AND** the user is redirected with a success message

#### Scenario: Image can be deleted via dedicated route
- **WHEN** a DELETE request is sent to `/recus/{recu}/image`
- **THEN** the image is deleted from storage
- **AND** `image_path` on the recu is set to null
- **AND** a success response is returned (redirect with flash message)

#### Scenario: User cannot delete another user's image
- **WHEN** a user tries to delete an image from another user's recu
- **THEN** the system returns a 403 Forbidden response

#### Scenario: Remove image when no image exists
- **WHEN** an image deletion request is made for a recu with no image
- **THEN** the system returns a 404 Not Found response
