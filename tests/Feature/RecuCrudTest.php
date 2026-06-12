<?php

use App\Enums\StatutRecu;
use App\Models\Recu;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

test('unauthenticated user cannot access recus pages', function () {
    $this->get(route('recus.index'))->assertRedirect(route('login'));
    $this->get(route('recus.create'))->assertRedirect(route('login'));
    $this->get(route('recus.show', 1))->assertRedirect(route('login'));
    $this->get(route('recus.edit', 1))->assertRedirect(route('login'));
    $this->post(route('recus.store'))->assertRedirect(route('login'));
    $this->put(route('recus.update', 1))->assertRedirect(route('login'));
    $this->delete(route('recus.destroy', 1))->assertRedirect(route('login'));
});

test('user can view their own recus list', function () {
    $recu = Recu::factory()->for($this->user)->create();

    $this->actingAs($this->user)
        ->get(route('recus.index'))
        ->assertOk()
        ->assertSee($recu->created_at->format('d/m/Y'));
});

test('user cannot see other users recus in their list', function () {
    Recu::factory()->for($this->otherUser)->create();

    $this->actingAs($this->user)
        ->get(route('recus.index'))
        ->assertOk()
        ->assertSee('Aucun reçu pour le moment');
});

test('user can create a recu with text only', function () {
    Queue::fake();

    $this->actingAs($this->user)
        ->post(route('recus.store'), [
            'texte_brut' => 'CARREFOUR CITY Total: 42.50€',
        ])
        ->assertRedirect(route('recus.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('recus', [
        'user_id' => $this->user->id,
        'statut' => 'en_attente',
    ]);
});

test('user can create a recu with image only', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('receipt.jpg');

    $this->actingAs($this->user)
        ->post(route('recus.store'), [
            'image' => $file,
        ])
        ->assertRedirect(route('recus.index'));

    $recu = Recu::where('user_id', $this->user->id)->first();
    expect($recu)->not->toBeNull();
    expect($recu->image_path)->not->toBeNull();
    Storage::disk('public')->assertExists($recu->image_path);
});

test('store fails without text and image', function () {
    $this->actingAs($this->user)
        ->post(route('recus.store'), [])
        ->assertSessionHasErrors(['texte_brut']);
});

test('store fails with text exceeding max length', function () {
    $this->actingAs($this->user)
        ->post(route('recus.store'), [
            'texte_brut' => str_repeat('a', 10001),
        ])
        ->assertSessionHasErrors(['texte_brut']);
});

test('user can view their own recu detail', function () {
    $recu = Recu::factory()
        ->for($this->user)
        ->has(\App\Models\Depense::factory()->count(3))
        ->create();

    $this->actingAs($this->user)
        ->get(route('recus.show', $recu))
        ->assertOk()
        ->assertSee('Reçu #' . $recu->id);
});

test('user cannot view another users recu', function () {
    $recu = Recu::factory()->for($this->otherUser)->create();

    $this->actingAs($this->user)
        ->get(route('recus.show', $recu))
        ->assertForbidden();
});

test('viewing non-existent recu returns 404', function () {
    $this->actingAs($this->user)
        ->get(route('recus.show', 9999))
        ->assertNotFound();
});

test('user can edit their own recu', function () {
    $recu = Recu::factory()->for($this->user)->create([
        'texte_brut' => 'Original text',
    ]);

    $this->actingAs($this->user)
        ->get(route('recus.edit', $recu))
        ->assertOk()
        ->assertSee('Original text');
});

test('user cannot edit another users recu', function () {
    $recu = Recu::factory()->for($this->otherUser)->create();

    $this->actingAs($this->user)
        ->get(route('recus.edit', $recu))
        ->assertForbidden();
});

test('user can update their own recu text', function () {
    $recu = Recu::factory()->for($this->user)->create([
        'texte_brut' => 'Original text',
        'statut' => 'traite',
    ]);

    $this->actingAs($this->user)
        ->put(route('recus.update', $recu), [
            'texte_brut' => 'Updated text',
        ])
        ->assertRedirect(route('recus.show', $recu));

    $recu->refresh();
    expect($recu->texte_brut)->toBe('Updated text');
    expect($recu->statut)->toBe(StatutRecu::EnAttente);
});

test('user can update their own recu image', function () {
    Storage::fake('public');

    $recu = Recu::factory()->for($this->user)->create();
    $file = UploadedFile::fake()->image('new-receipt.jpg');

    $this->actingAs($this->user)
        ->put(route('recus.update', $recu), [
            'image' => $file,
        ])
        ->assertRedirect(route('recus.show', $recu));

    $recu->refresh();
    expect($recu->image_path)->not->toBeNull();
    Storage::disk('public')->assertExists($recu->image_path);
});

test('user cannot update another users recu', function () {
    $recu = Recu::factory()->for($this->otherUser)->create();

    $this->actingAs($this->user)
        ->put(route('recus.update', $recu), [
            'texte_brut' => 'Hacked',
        ])
        ->assertForbidden();
});

test('user can delete their own recu', function () {
    $recu = Recu::factory()->for($this->user)->create();

    $this->actingAs($this->user)
        ->delete(route('recus.destroy', $recu))
        ->assertRedirect(route('recus.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('recus', ['id' => $recu->id]);
});

test('user cannot delete another users recu', function () {
    $recu = Recu::factory()->for($this->otherUser)->create();

    $this->actingAs($this->user)
        ->delete(route('recus.destroy', $recu))
        ->assertForbidden();
});

test('deleting a recu cascades to depenses', function () {
    $recu = Recu::factory()
        ->for($this->user)
        ->has(\App\Models\Depense::factory()->count(3))
        ->create();

    $this->actingAs($this->user)
        ->delete(route('recus.destroy', $recu));

    expect(\App\Models\Depense::where('recu_id', $recu->id)->count())->toBe(0);
});
