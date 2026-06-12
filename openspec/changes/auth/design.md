## Context

Les pages d'authentification sont à moitié personnalisées. Login et register ont le nouveau design, mais le reste (reset, forgot, verify, confirm) utilise les gabarits Breeze par défaut. L'email verification n'est pas branchée. Le 2FA n'existe pas. Cette change complète l'expérience utilisateur d'authentification.

## Goals / Non-Goals

**Goals:**
- Unifier toutes les vues d'auth avec le design system de l'app
- Activer la vérification d'email obligatoire
- Ajouter 2FA (TOTP) avec inscription et challenge

**Non-Goals:**
- 2FA par SMS ou email (TOTP seulement)
- Récupération de compte 2FA par codes de secours (future version)
- WebAuthn/passkeys
- Socialite/OAuth

## Decisions

1. **Vues standalone** — Les vues d'auth sont des pages complètes (pas de layout sidebar), comme les login/register existants. Elles partagent le même système de design : fond avec blur circles, carte centrée, Material Symbols, français.

2. **MustVerifyEmail via trait** — Utiliser le trait natif `Illuminate\Contracts\Auth\MustVerifyEmail` fourni par Laravel. Les notifications d'email sont déjà gérées par le framework. La bannière de vérification dans le layout utilise `auth()->user()->hasVerifiedEmail()`.

3. **2FA avec table dédiée** — Pas de colonnes sur `users` pour éviter de surcharger le modèle. Une table `two_factor_authenticators` avec `user_id`, `secret`, `recovery_codes`, `confirmed_at`. Le secret TOTP est généré via une librairie (bacula/phppot ou similaire). Le challenge 2FA est intégré au middleware `auth` via un sous-middleware.

4. **Rate limiting** — Le challenge 2FA est limité à 5 tentatives par minute (comme les routes verify-email).

5. **Pas de librairie 2FA externe** — Utiliser une implémentation TOTP simple (HMAC-SHA1 sur timestamp) qui suit la RFC 6238. Pas de dépendance Composer supplémentaire.

## Risks / Trade-offs

- [Risk] TOTP sans librairie = plus de code à maintenir → La spec RFC 6238 est simple (< 50 lignes PHP). Évite une dépendance.
- [Risk] L'activation de MustVerifyEmail bloque tous les utilisateurs existants non vérifiés → Les comptes existants sont considérés comme vérifiés automatiquement via un migration avec `email_verified_at = now()` pour les users actuels.
- [Risk] Le middleware 2FA doit être appliqué après le middleware auth → Ordre correct dans le Kernel.
