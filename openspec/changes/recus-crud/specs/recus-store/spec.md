## ADDED Requirements

### Requirement: User can submit a new receipt
The system SHALL allow authenticated users to submit a new receipt (Recu) by providing at least one of: raw text (`texte_brut`) or an uploaded image (`image`).

#### Scenario: Successful submission with text only
- **WHEN** user fills in the textarea with valid receipt text and clicks "Enregistrer"
- **THEN** the system creates a new Recu record with `statut = en_attente` and redirects to the index page with a success message

#### Scenario: Successful submission with image only
- **WHEN** user uploads a valid image file (≤10MB, jpg/png/webp) and clicks "Enregistrer"
- **THEN** the system stores the image, creates a new Recu record, and redirects to the index page

#### Scenario: Submission with both text and image
- **WHEN** user provides both text and an image
- **THEN** the system saves both and creates the Recu record

#### Scenario: Submission with neither text nor image
- **WHEN** user clicks "Enregistrer" without providing text or image
- **THEN** the system shows a validation error requiring at least one of the two fields

#### Scenario: Text exceeds maximum length
- **WHEN** user submits text longer than 10,000 characters
- **THEN** the system shows a validation error for the `texte_brut` field

#### Scenario: Uploaded file is not an image
- **WHEN** user uploads a non-image file (e.g., PDF, .txt)
- **THEN** the system shows a validation error for the `image` field

#### Scenario: Uploaded image exceeds size limit
- **WHEN** user uploads an image larger than 10MB
- **THEN** the system shows a validation error for the `image` field
