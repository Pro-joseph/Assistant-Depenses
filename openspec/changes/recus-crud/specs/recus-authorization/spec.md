## ADDED Requirements

### Requirement: Authorization is enforced on all receipt actions
The system SHALL use Laravel Policies to enforce that users can only view, edit, or delete their own receipts. All authenticated users can create new receipts.

#### Scenario: Policy gates index access
- **WHEN** any authenticated user visits the receipts list
- **THEN** they see only their own receipts (filtered by `user_id`)

#### Scenario: Policy gates show access
- **WHEN** user tries to view another user's receipt
- **THEN** the policy returns false and the system returns 403

#### Scenario: Policy gates update access
- **WHEN** user tries to edit another user's receipt
- **THEN** the policy returns false and the system returns 403

#### Scenario: Policy gates delete access
- **WHEN** user tries to delete another user's receipt
- **THEN** the policy returns false and the system returns 403

#### Scenario: Unauthenticated access is blocked
- **WHEN** a guest (unauthenticated) tries to access any receipt route
- **THEN** they are redirected to the login page (auth middleware)

#### Scenario: Create action is always authorized
- **WHEN** any authenticated user accesses the create form or submits a store request
- **THEN** authorization passes (all users can create receipts)
