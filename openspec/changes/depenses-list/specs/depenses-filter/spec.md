## ADDED Requirements

### Requirement: User can filter expenses by category
The system SHALL allow authenticated users to filter their expenses by category using filter buttons above the table.

#### Scenario: Filtering by a specific category
- **WHEN** user clicks a category filter button (e.g., "Alimentaire")
- **THEN** the system displays only expenses with that category, and the active filter button is visually highlighted

#### Scenario: Resetting filter to show all
- **WHEN** user clicks "Toutes" button while a category filter is active
- **THEN** the system displays all expenses without category filtering

#### Scenario: Filter is preserved during pagination
- **WHEN** user applies a category filter and navigates to page 2
- **THEN** the filter parameter is preserved in the pagination links

#### Scenario: No expenses match the filter
- **WHEN** user selects a category with no matching expenses
- **THEN** the system displays "Aucune dépense trouvée" message
