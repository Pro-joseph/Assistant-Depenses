<?php

use App\Models\Depense;
use App\Models\Recu;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

test('unauthenticated user cannot access depenses page', function () {
    $this->get(route('depenses.index'))->assertRedirect(route('login'));
});

test('user can view their own depenses list', function () {
    $recu = Recu::factory()->for($this->user)->create();
    $depense = Depense::factory()->for($recu)->create(['libelle' => 'Pain complet']);

    $this->actingAs($this->user)
        ->get(route('depenses.index'))
        ->assertOk()
        ->assertSee('Pain complet');
});

test('user cannot see other users depenses in their list', function () {
    $recu = Recu::factory()->for($this->otherUser)->create();
    Depense::factory()->for($recu)->create(['libelle' => 'Dépense cachée']);

    $this->actingAs($this->user)
        ->get(route('depenses.index'))
        ->assertOk()
        ->assertDontSee('Dépense cachée');
});

test('user can filter depenses by category', function () {
    $recu = Recu::factory()->for($this->user)->create();
    Depense::factory()->for($recu)->create(['libelle' => 'Pommes', 'categorie' => 'alimentaire']);
    Depense::factory()->for($recu)->create(['libelle' => 'Jus', 'categorie' => 'boissons']);

    $this->actingAs($this->user)
        ->get(route('depenses.index', ['categorie' => 'alimentaire']))
        ->assertOk()
        ->assertSee('Pommes')
        ->assertDontSee('Jus');
});

test('user can search depenses by keyword', function () {
    $recu = Recu::factory()->for($this->user)->create();
    Depense::factory()->for($recu)->create(['libelle' => 'Café Arabica']);
    Depense::factory()->for($recu)->create(['libelle' => 'Thé vert']);

    $this->actingAs($this->user)
        ->get(route('depenses.index', ['q' => 'Café']))
        ->assertOk()
        ->assertSee('Café Arabica')
        ->assertDontSee('Thé vert');
});

test('user can combine category filter and search', function () {
    $recu = Recu::factory()->for($this->user)->create();
    Depense::factory()->for($recu)->create(['libelle' => 'Pommes', 'categorie' => 'alimentaire']);
    Depense::factory()->for($recu)->create(['libelle' => 'Chips', 'categorie' => 'alimentaire']);
    Depense::factory()->for($recu)->create(['libelle' => 'Jus', 'categorie' => 'boissons']);

    $this->actingAs($this->user)
        ->get(route('depenses.index', ['categorie' => 'alimentaire', 'q' => 'Pommes']))
        ->assertOk()
        ->assertSee('Pommes')
        ->assertDontSee('Chips')
        ->assertDontSee('Jus');
});

test('depense policy denies access to other users depenses', function () {
    $recu = Recu::factory()->for($this->otherUser)->create();
    $depense = Depense::factory()->for($recu)->create();

    $policy = new \App\Policies\DepensePolicy();
    expect($policy->view($this->user, $depense))->toBeFalse();
    expect($policy->view($this->otherUser, $depense))->toBeTrue();
});

test('summary card shows total for the month', function () {
    $recu = Recu::factory()->for($this->user)->create();
    Depense::factory()->for($recu)->create(['quantite' => 2, 'prix_unitaire' => 10]);

    $this->actingAs($this->user)
        ->get(route('depenses.index'))
        ->assertOk()
        ->assertSee('20.00');
});
