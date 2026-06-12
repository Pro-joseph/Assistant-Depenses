## ADDED Requirements

### Requirement: laravel/ai package is installed
The system SHALL use the `laravel/ai` Composer package for AI API calls.

#### Scenario: Package is available
- **WHEN** `composer install` is run
- **THEN** the `laravel/ai` package is available in `vendor/`

### Requirement: Groq provider is configured
The system SHALL configure Groq as the AI provider with an API key from the environment.

#### Scenario: config/ai.php defines groq provider
- **WHEN** the application boots
- **THEN** `config('ai.providers.groq')` returns the correct configuration with `api_key` from `GROQ_API_KEY` env var

#### Scenario: Default provider is groq
- **WHEN** the AI SDK is used without specifying a provider
- **THEN** it uses the `groq` provider as defined by `AI_DEFAULT_PROVIDER`

#### Scenario: Missing API key shows error
- **WHEN** `GROQ_API_KEY` is not set in the environment
- **THEN** the AI SDK throws a configuration exception when attempting a call
