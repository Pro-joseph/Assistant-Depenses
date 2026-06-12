<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'email_verified_at' => null,
    ]);
});

// ─── View redesign tests ───────────────────────────────────────

test('forgot password page shows Material Symbols and French text', function () {
    $this->get(route('password.request'))
        ->assertSee('material-symbols-outlined')
        ->assertSee('Mot de passe oublié')
        ->assertSee('Envoyer le lien')
        ->assertSee(route('login'));
});

test('reset password page shows Material Symbols and French text', function () {
    $token = 'test-token';
    $this->get(route('password.reset', $token))
        ->assertSee('material-symbols-outlined')
        ->assertSee('Réinitialiser le mot de passe')
        ->assertSee('Nouveau mot de passe')
        ->assertSee('Confirmer le mot de passe');
});

test('verify email page shows Material Symbols and French text', function () {
    $this->actingAs($this->user)
        ->get(route('verification.notice'))
        ->assertSee('material-symbols-outlined')
        ->assertSee('Vérifiez votre email')
        ->assertSee('Renvoyer')
        ->assertSee('Se déconnecter');
});

test('confirm password page shows Material Symbols and French text', function () {
    $this->actingAs($this->user)
        ->get(route('password.confirm'))
        ->assertSee('material-symbols-outlined')
        ->assertSee('Confirmez votre mot de passe')
        ->assertSee('Confirmer');
});

