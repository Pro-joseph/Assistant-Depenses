## ADDED Requirements

### Requirement: User can delete a receipt
The system SHALL allow authenticated users to delete their own receipts. Deleting a receipt SHALL also delete all associated depenses (cascaded at the database level).

#### Scenario: Deleting own receipt
- **WHEN** user clicks the delete button on their own receipt and confirms
- **THEN** the system deletes the Recu and its associated Depenses, and redirects to the index page with a success message

#### Scenario: Deleting another user's receipt
- **WHEN** user tries to delete a receipt owned by another user
- **THEN** the system returns a 403 Forbidden response

#### Scenario: Deleting a receipt with image file
- **WHEN** user deletes a receipt that has an associated image
- **THEN** the system deletes the image file from storage, then deletes the record

#### Scenario: Delete confirmation
- **WHEN** user clicks the delete button
- **THEN** the system shows a confirmation dialog before proceeding with deletion
