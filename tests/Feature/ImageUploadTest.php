<?php

use App\Models\Recu;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

test('user can delete image via dedicated route', function () {
    Storage::fake('public');

    $recu = Recu::factory()->for($this->user)->create([
        'image_path' => 'recus/1/test.jpg',
    ]);

    Storage::disk('public')->put('recus/1/test.jpg', 'fake');

    $this->actingAs($this->user)
        ->delete(route('recus.image.destroy', $recu))
        ->assertRedirect()
        ->assertSessionHas('success');

    $recu->refresh();
    expect($recu->image_path)->toBeNull();
    Storage::disk('public')->assertMissing('recus/1/test.jpg');
});

test('user cannot delete image of another user', function () {
    $recu = Recu::factory()->for($this->otherUser)->create([
        'image_path' => 'recus/1/test.jpg',
    ]);

    $this->actingAs($this->user)
        ->delete(route('recus.image.destroy', $recu))
        ->assertForbidden();
});

test('deleting image on recu without image returns 404', function () {
    $recu = Recu::factory()->for($this->user)->create([
        'image_path' => null,
    ]);

    $this->actingAs($this->user)
        ->delete(route('recus.image.destroy', $recu))
        ->assertNotFound();
});

test('user can delete image via checkbox in update form', function () {
    Storage::fake('public');

    $recu = Recu::factory()->for($this->user)->create([
        'image_path' => 'recus/1/test.jpg',
    ]);

    Storage::disk('public')->put('recus/1/test.jpg', 'fake');

    $this->actingAs($this->user)
        ->put(route('recus.update', $recu), [
            'supprimer_image' => '1',
        ])
        ->assertRedirect(route('recus.show', $recu))
        ->assertSessionHas('success');

    $recu->refresh();
    expect($recu->image_path)->toBeNull();
    Storage::disk('public')->assertMissing('recus/1/test.jpg');
});

test('unauthenticated user cannot delete image', function () {
    $this->delete(route('recus.image.destroy', 1))
        ->assertRedirect(route('login'));
});
