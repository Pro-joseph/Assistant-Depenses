## ADDED Requirements

### Requirement: StatutRecu PHP enum exists
The system SHALL define a PHP backed enum `StatutRecu` with values matching the database ENUM column: `en_attente`, `traite`, `echoue`.

#### Scenario: Enum values match database
- **WHEN** the enum is defined
- **THEN** it MUST contain exactly the cases: `EnAttente` → `en_attente`, `Traite` → `traite`, `Echoue` → `echoue`

#### Scenario: Enum is string-backed
- **WHEN** the enum is used
- **THEN** its values are strings matching the database enum column values

### Requirement: Recu model uses enum cast for statut
The system SHALL cast the `statut` attribute on the Recu model to the `StatutRecu` enum.

#### Scenario: Cast is configured
- **WHEN** a Recu is retrieved from the database
- **THEN** the `statut` attribute is an instance of `StatutRecu` enum

### Requirement: Recu model casts payload_brut to array
The system SHALL cast the `payload_brut` attribute on the Recu model to an `array`.

#### Scenario: JSON payload is cast to array
- **WHEN** a Recu with `payload_brut` is retrieved from the database
- **THEN** the `payload_brut` attribute is a PHP array (not a raw JSON string)
