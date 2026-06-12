## ADDED Requirements

### Requirement: Forgot password page matches the app design
The forgot password page SHALL be redesigned to match the existing login/register page design.

#### Scenario: User visits forgot-password page
- **WHEN** the user navigates to `/forgot-password`
- **THEN** the page displays the app header (icon + "Assistant Dépenses"), a centered card with email input + Material Symbols icon + "Envoyer le lien" button, blurred background circles, and a return link to login

#### Scenario: Reset link is sent
- **WHEN** the user submits a valid email
- **THEN** a success message appears in French: "Un lien de réinitialisation vous a été envoyé par email."

### Requirement: Reset password page matches the app design
The reset password page SHALL be redesigned to match the existing login/register page design.

#### Scenario: User visits reset-password page
- **WHEN** the user clicks the reset link in their email
- **THEN** the page displays the app header, a centered card with email (disabled), new password, confirm password inputs with Material Symbols, and "Réinitialiser le mot de passe" button
