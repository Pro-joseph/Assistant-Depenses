## ADDED Requirements

### Requirement: Email verification is required
The system SHALL require email verification for all new user registrations.

#### Scenario: User registers and is prompted to verify
- **WHEN** a new user registers
- **THEN** they are redirected to the verify-email notice page
- **AND** a verification email is sent

#### Scenario: User verifies their email
- **WHEN** the user clicks the verification link in the email
- **THEN** their `email_verified_at` is set
- **AND** they are redirected to the app (receipts page)

#### Scenario: Unverified user tries to access the app
- **WHEN** an unverified user tries to access any page behind the `verified` middleware
- **THEN** they are redirected to the verify-email notice page

### Requirement: Verify email page matches the app design
The verify email notice page SHALL be redesigned to match the existing login/register page design.

#### Scenario: User sees verify-email page
- **WHEN** the user is redirected to verify their email
- **THEN** the page displays the app header, a centered card with an info message in French, "Renvoyer l'email" button, and "Déconnexion" link

### Requirement: Verification banner in sidebar
An unverified user SHALL see a persistent banner in the sidebar layout reminding them to verify their email.

#### Scenario: Unverified user browses the app
- **WHEN** an unverified user is on any app page
- **THEN** a banner at the top of the content area says "Veuillez vérifier votre adresse email" with a link to resend the verification email

#### Scenario: Verified user does not see banner
- **WHEN** a verified user browses the app
- **THEN** no verification banner is displayed
