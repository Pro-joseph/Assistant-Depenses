## ADDED Requirements

### Requirement: Confirm password page matches the app design
The confirm password page SHALL be redesigned to match the existing login/register page design.

#### Scenario: User is prompted to confirm password
- **WHEN** the user accesses a sensitive action requiring password confirmation
- **THEN** the page displays the app header, a centered card with a message in French "Cette action est sécurisée. Veuillez confirmer votre mot de passe pour continuer.", a password input with Material Symbols icon, and "Confirmer" button
