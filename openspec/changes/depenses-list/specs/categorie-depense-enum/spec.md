## ADDED Requirements

### Requirement: CategorieDepense PHP enum exists
The system SHALL define a PHP backed enum `CategorieDepense` with the same values as the database column: `alimentaire`, `boissons`, `hygiene`, `entretien`, `autre`.

#### Scenario: Enum values match database
- **WHEN** the enum is defined
- **THEN** it MUST contain exactly the cases: `alimentaire`, `boissons`, `hygiene`, `entretien`, `autre`

#### Scenario: Enum is string-backed
- **WHEN** the enum is used
- **THEN** its values are strings matching the database enum column values

### Requirement: Depense model uses enum cast
The system SHALL cast the `categorie` attribute on the Depense model to the `CategorieDepense` enum.

#### Scenario: Cast is configured
- **WHEN** a Depense is retrieved from the database
- **THEN** the `categorie` attribute is an instance of `CategorieDepense` enum

### Requirement: Depense model has belongsTo Recu relationship
The system SHALL define a `belongsTo` relationship from Depense to Recu.

#### Scenario: Relationship is accessible
- **WHEN** calling `$depense->recu`
- **THEN** it returns the associated Recu model instance
