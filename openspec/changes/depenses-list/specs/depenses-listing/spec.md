## ADDED Requirements

### Requirement: User can view paginated list of their expenses
The system SHALL display a paginated list of all expenses (dépenses) belonging to the authenticated user, linked through their receipts (Recus).

#### Scenario: Viewing expenses list
- **WHEN** authenticated user navigates to `/depenses`
- **THEN** the system displays a paginated table of expenses with columns: libellé, montant, catégorie, date, recu source

#### Scenario: Pagination works
- **WHEN** user has more than 15 expenses
- **THEN** the system displays pagination controls at the bottom of the table

#### Scenario: Summary cards show real data
- **WHEN** user views the expenses page
- **THEN** the summary cards display the actual total amount, count by category, and month-over-month change

#### Scenario: Unauthenticated access is blocked
- **WHEN** a guest tries to access `/depenses`
- **THEN** they are redirected to the login page
