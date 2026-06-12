<?php

use App\Enums\StatutRecu;
use App\Jobs\ExtraireDepensesDuRecu;
use App\Models\Depense;
use App\Models\Recu;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Laravel\Ai\StructuredAnonymousAgent;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('job is dispatched after creating a recu', function () {
    Queue::fake();

    $this->actingAs($this->user)
        ->post(route('recus.store'), [
            'texte_brut' => 'Pain 10dh, Lait 15dh',
        ])
        ->assertRedirect(route('recus.index'));

    Queue::assertPushed(ExtraireDepensesDuRecu::class);
});

test('job sets statut to traite after successful extraction', function () {
    StructuredAnonymousAgent::fake([
        [
            'articles' => [
                [
                    'libellé' => 'Pain complet',
                    'quantité' => 2,
                    'prix_unitaire' => 10,
                    'catégorie' => 'alimentaire',
                ],
                [
                    'libellé' => 'Lait demi-écrémé',
                    'quantité' => 1,
                    'prix_unitaire' => 15,
                    'catégorie' => 'boissons',
                ],
            ],
            'total_estimé' => 35,
            'devise' => 'MAD',
        ],
    ]);

    $recu = Recu::factory()->for($this->user)->create([
        'texte_brut' => 'Pain complet 2x10=20, Lait 1x15=15',
        'statut' => StatutRecu::EnAttente,
    ]);

    (new ExtraireDepensesDuRecu($recu))->handle();

    $recu->refresh();

    expect($recu->statut)->toBe(StatutRecu::Traite);
    expect($recu->total_estime)->toEqual(35);
    expect($recu->devise)->toBe('MAD');
    expect($recu->payload_brut)->toBeArray();
});

test('job sets statut to echoue when texte_brut is null', function () {
    $recu = Recu::factory()->for($this->user)->create([
        'texte_brut' => null,
        'statut' => StatutRecu::EnAttente,
    ]);

    (new ExtraireDepensesDuRecu($recu))->handle();

    $recu->refresh();

    expect($recu->statut)->toBe(StatutRecu::Echoue);
});

test('job sets statut to echoue when ai api call fails', function () {
    StructuredAnonymousAgent::fake(function () {
        throw new \Laravel\Ai\Exceptions\AiException('API Error');
    });

    $recu = Recu::factory()->for($this->user)->create([
        'texte_brut' => 'Some text',
        'statut' => StatutRecu::EnAttente,
    ]);

    (new ExtraireDepensesDuRecu($recu))->handle();

    $recu->refresh();

    expect($recu->statut)->toBe(StatutRecu::Echoue);
});

test('job creates depense records after successful extraction', function () {
    StructuredAnonymousAgent::fake([
        [
            'articles' => [
                [
                    'libellé' => 'Pain complet',
                    'quantité' => 2,
                    'prix_unitaire' => 10,
                    'catégorie' => 'alimentaire',
                ],
            ],
            'total_estimé' => 20,
            'devise' => 'MAD',
        ],
    ]);

    $recu = Recu::factory()->for($this->user)->create([
        'texte_brut' => 'Pain complet 2x10=20',
        'statut' => StatutRecu::EnAttente,
    ]);

    (new ExtraireDepensesDuRecu($recu))->handle();

    expect(Depense::where('recu_id', $recu->id)->count())->toBe(1);

    $depense = Depense::where('recu_id', $recu->id)->first();

    expect($depense->libelle)->toBe('Pain complet');
    expect($depense->quantite)->toBe(2);
    expect($depense->prix_unitaire)->toEqual(10);
    expect($depense->categorie->value)->toBe('alimentaire');
});
