## ADDED Requirements

### Requirement: User can view a single receipt detail
The system SHALL allow authenticated users to view the details of a single Recu, including its raw text, image (if any), status, estimated total, currency, and associated Depenses.

#### Scenario: Viewing own receipt
- **WHEN** user navigates to `/recus/{id}` for a receipt they own
- **THEN** the system displays the receipt details including all depenses

#### Scenario: Viewing another user's receipt
- **WHEN** user navigates to `/recus/{id}` for a receipt owned by another user
- **THEN** the system returns a 403 Forbidden response

#### Scenario: Viewing non-existent receipt
- **WHEN** user navigates to `/recus/{id}` with an invalid ID
- **THEN** the system returns a 404 Not Found response

#### Scenario: Receipt with extracted depenses
- **WHEN** the receipt has `statut = traite` and associated depenses
- **THEN** the system displays all depenses in a table with libelle, quantite, prix_unitaire, and categorie
