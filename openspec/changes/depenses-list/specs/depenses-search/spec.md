## ADDED Requirements

### Requirement: User can search expenses by text
The system SHALL allow authenticated users to search their expenses by typing a keyword that matches the expense label (libellé).

#### Scenario: Searching by keyword
- **WHEN** user types a keyword in the search bar and submits
- **THEN** the system displays only expenses whose libellé contains the keyword (case-insensitive)

#### Scenario: Combining search with category filter
- **WHEN** user has a category filter active and also provides a search keyword
- **THEN** the system applies both filters simultaneously

#### Scenario: No expenses match the search
- **WHEN** user searches for a keyword with no matching libellés
- **THEN** the system displays "Aucune dépense trouvée" message

#### Scenario: Empty search shows all
- **WHEN** user submits an empty search
- **THEN** the system displays all expenses without search filtering
