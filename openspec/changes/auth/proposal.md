## Why

Le système d'authentification actuel est incomplet. Les pages login et register sont personnalisées, mais les pages de réinitialisation de mot de passe, vérification d'email, et confirmation de mot de passe utilisent encore les gabarits Breeze par défaut (anglais, `x-guest-layout`, composants Tailwind génériques). La vérification d'email n'est pas activée (`MustVerifyEmail` commenté). L'authentification à deux facteurs (2FA) n'existe pas. Cette change uniformise toute l'expérience d'authentification et ajoute les fonctionnalités manquantes.

## What Changes

- **Redesign des vues Breeze** — Re-styler forgot-password, reset-password, verify-email, confirm-password avec le design system de l'application (fond dégradé, Material Symbols, français)
- **Activation email verification** — Décommenter `MustVerifyEmail` sur User, ajouter middleware `verified`, bannière de vérification dans le layout sidebar
- **Two-factor authentication (2FA)** — Nouvelle fonctionnalité complète avec TOTP via une table `user_two_factor_secrets`, setup wizard, code verification au login

## Capabilities

### New Capabilities
- `password-reset-ux`: Refonte complète des vues de réinitialisation de mot de passe (design system, français)
- `email-verification`: Activation et UI de vérification d'email (MustVerifyEmail, bannière, middleware)
- `confirm-password-ux`: Refonte de la vue de confirmation de mot de passe
- `two-factor-auth`: Authentification à deux facteurs (TOTP)

### Modified Capabilities
*(Aucune spec existante n'est modifiée)*

## Impact

- `resources/views/auth/forgot-password.blade.php` — Refonte complète
- `resources/views/auth/reset-password.blade.php` — Refonte complète
- `resources/views/auth/verify-email.blade.php` — Refonte complète
- `resources/views/auth/confirm-password.blade.php` — Refonte complète
- `resources/views/layouts/sidebar.blade.php` — Bannière de vérification email
- `app/Models/User.php` — Décommenter MustVerifyEmail, nouveau cast/relation pour 2FA
- `app/Http/Controllers/Auth/TwoFactorController.php` — Nouveau contrôleur
- `resources/views/auth/two-factor-challenge.blade.php` — Nouvelle vue
- `resources/views/auth/two-factor-setup.blade.php` — Nouvelle vue
- `routes/auth.php` — Nouvelles routes 2FA
- `database/migrations/xxxx_add_two_factor_to_users_table.php` — Nouvelle migration
- `config/auth.php` ou autre — Rate limiter 2FA
