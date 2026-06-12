## ADDED Requirements

### Requirement: User can enable two-factor authentication
The system SHALL allow a user to enable TOTP-based two-factor authentication from their profile settings.

#### Scenario: User sets up 2FA
- **WHEN** the user navigates to the 2FA setup page
- **THEN** a QR code and manual setup key are displayed
- **AND** the user must enter a verification code from their authenticator app to confirm

#### Scenario: User confirms 2FA setup
- **WHEN** the user enters a valid 6-digit TOTP code
- **THEN** 2FA is enabled for their account
- **AND** they are redirected with a success message

#### Scenario: User enters invalid confirmation code
- **WHEN** the user enters an invalid TOTP code during setup
- **THEN** an error message is shown
- **AND** 2FA is not enabled

### Requirement: User must complete 2FA challenge at login
The system SHALL require a TOTP code after login if 2FA is enabled.

#### Scenario: User with 2FA enabled logs in
- **WHEN** the user submits valid credentials
- **THEN** they are redirected to a 2FA challenge page instead of the app

#### Scenario: User enters valid 2FA code
- **WHEN** the user enters a valid 6-digit TOTP code on the challenge page
- **THEN** they are authenticated and redirected to the app

#### Scenario: User enters invalid 2FA code
- **WHEN** the user enters an invalid TOTP code
- **THEN** an error message is shown
- **AND** they remain on the challenge page

#### Scenario: Too many failed 2FA attempts
- **WHEN** the user fails 5 2FA attempts within a minute
- **THEN** they are rate-limited
- **AND** must wait before retrying

### Requirement: User can disable two-factor authentication
The system SHALL allow a user to disable 2FA from their profile settings.

#### Scenario: User disables 2FA
- **WHEN** the user confirms disabling 2FA
- **THEN** the 2FA secret is removed from the database
- **AND** the user is redirected with a success message
