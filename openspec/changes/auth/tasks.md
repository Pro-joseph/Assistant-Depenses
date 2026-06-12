## 1. Password Reset UX

- [x] 1.1 Redesign `forgot-password.blade.php` — standalone page avec app header, carte centrée, Material Symbols, fond blur circles, français
- [x] 1.2 Redesign `reset-password.blade.php` — standalone page avec app header, carte centrée, Material Symbols, français

## 2. Email Verification

- [x] 2.1 Décommenter `MustVerifyEmail` sur le modèle User, ajouter `implements MustVerifyEmail`
- [x] 2.2 Redesign `verify-email.blade.php` — standalone page, français, app design
- [x] 2.3 Ajouter le middleware `verified` aux routes protégées dans `web.php`
- [x] 2.4 Ajouter une bannière de vérification email dans `layouts/sidebar.blade.php`

## 3. Confirm Password UX

- [x] 3.1 Redesign `confirm-password.blade.php` — standalone page, français, app design

## 4. Two-Factor Authentication

- [x] 4.1 Créer la migration pour la table `two_factor_authenticators` (user_id, secret, confirmed_at)
- [x] 4.2 Créer le modèle `TwoFactorAuthenticator` avec relation `belongsTo User`
- [x] 4.3 Implémenter la classe TOTP (RFC 6238) — génération de secret, génération de code, validation
- [x] 4.4 Créer `TwoFactorController` avec setup, confirm, disable methods
- [x] 4.5 Créer la vue `two-factor-setup.blade.php` — QR code + setup key + confirmation input
- [x] 4.6 Créer `TwoFactorChallengeController` pour le challenge après login
- [x] 4.7 Créer la vue `two-factor-challenge.blade.php` — input code 6 digits, français, app design
- [x] 4.8 Ajouter les routes 2FA dans `routes/auth.php`
- [x] 4.9 Intégrer le challenge 2FA dans le login flow (redirection si 2FA activé)
- [x] 4.10 Ajouter rate limiting sur le challenge 2FA

## 5. Tests

- [x] 5.1 Tester le redesign des vues (vérifier présence des éléments Material Symbols, textes français)
- [x] 5.2 Tester l'activation et la désactivation du 2FA
- [x] 5.3 Tester le challenge 2FA au login
