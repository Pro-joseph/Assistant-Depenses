## ADDED Requirements

### Requirement: Job extracts depenses from receipt text via AI
The system SHALL process a `Recu` asynchronously by dispatching a Job that calls the Groq AI API to extract structured expense data from the receipt's raw text (`texte_brut`).

#### Scenario: Successful extraction
- **WHEN** the Job runs on a Recu with valid `texte_brut`
- **THEN** the system calls the AI API, receives structured JSON, creates Depense records, updates `statut` to `traite`, and stores `total_estime`, `devise`, and `payload_brut`

#### Scenario: Extraction with no texte_brut
- **WHEN** the Job runs on a Recu where `texte_brut` is null
- **THEN** the system sets `statut` to `echoue` without calling the AI API

#### Scenario: AI API call fails
- **WHEN** the AI API returns an error or times out
- **THEN** the system catches the exception, sets `statut` to `echoue`, and logs the error

#### Scenario: AI returns malformed JSON
- **WHEN** the AI API returns data that does not match the required schema
- **THEN** the system catches the schema validation exception, sets `statut` to `echoue`

#### Scenario: Depense creation fails
- **WHEN** creating Depense records from the AI result fails (e.g., DB error)
- **THEN** the system rolls back any partial creates, sets `statut` to `echoue`

### Requirement: Job stores raw AI payload for debugging
The system SHALL store the raw JSON response from the AI in `recus.payload_brut` for debugging and future reprocessing.

#### Scenario: Payload is stored after extraction
- **WHEN** the AI API responds successfully
- **THEN** the raw JSON response is saved to `payload_brut` on the Recu
