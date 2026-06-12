<?php

namespace App\Jobs;

use App\Enums\StatutRecu;
use App\Models\Depense;
use App\Models\Recu;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Exceptions\AiException;
use Laravel\Ai\Files\Image;
use Laravel\Ai\StructuredAnonymousAgent;

class ExtraireDepensesDuRecu implements ShouldQueue
{
    use Queueable;

    public Recu $recu;

    public function __construct(Recu $recu)
    {
        $this->recu = $recu;
    }

    public function handle(): void
    {
        $text = $this->recu->texte_brut;

        if (empty(trim($text ?? '')) && $this->recu->image_path) {
            $text = $this->ocrDepuisImage();
        }

        if (empty(trim($text ?? ''))) {
            $this->recu->update(['statut' => StatutRecu::Echoue]);

            return;
        }

        $agent = new StructuredAnonymousAgent(
            instructions: 'Tu es un assistant spécialisé dans l\'extraction de données de reçus de caisse. Extrais les articles listés sur le reçu écrit en Darija. Réponds UNIQUEMENT avec du JSON valide correspondant au schéma fourni. Le champ "catégorie" doit être l\'un de : alimentaire, boissons, hygiene, entretien, autre.',
            messages: [],
            tools: [],
            schema: fn (JsonSchema $schema) => [
                'articles' => $schema->array()->items(
                    $schema->object([
                        'libellé' => $schema->string()->required(),
                        'quantité' => $schema->integer()->required()->min(1),
                        'prix_unitaire' => $schema->number()->required()->min(0),
                        'catégorie' => $schema->string()->required()->enum([
                            'alimentaire', 'boissons', 'hygiene', 'entretien', 'autre',
                        ]),
                    ])
                )->required(),
                'total_estimé' => $schema->number()->required(),
                'devise' => $schema->string()->required(),
            ],
        );

        try {
            $response = $agent->prompt($text);

            $data = $response->structured;

            DB::transaction(function () use ($data) {
                $this->recu->depenses()->delete();

                foreach ($data['articles'] as $article) {
                    Depense::create([
                        'recu_id' => $this->recu->id,
                        'libelle' => $article['libellé'],
                        'quantite' => $article['quantité'],
                        'prix_unitaire' => $article['prix_unitaire'],
                        'categorie' => $article['catégorie'],
                    ]);
                }

                $this->recu->update([
                    'statut' => StatutRecu::Traite,
                    'total_estime' => $data['total_estimé'],
                    'devise' => $data['devise'],
                    'payload_brut' => $data,
                ]);
            });
        } catch (AiException $e) {
            Log::error('Extraction IA échouée pour le recu #' . $this->recu->id . ': ' . $e->getMessage());

            $this->recu->update(['statut' => StatutRecu::Echoue]);
        } catch (\Throwable $e) {
            Log::error('Erreur inattendue lors de l\'extraction IA pour le recu #' . $this->recu->id . ': ' . $e->getMessage());

            $this->recu->update(['statut' => StatutRecu::Echoue]);
        }
    }

    private function ocrDepuisImage(): ?string
    {
        $ocrAgent = new StructuredAnonymousAgent(
            instructions: 'Tu es un assistant OCR spécialisé dans l\'extraction de texte de reçus de caisse. Extrais TOUT le texte visible sur ce reçu, ligne par ligne. Conserve la langue originale (Darija, français, arabe). Ne modifie pas les montants ni les noms de produits.',
            messages: [],
            tools: [],
            schema: fn (JsonSchema $schema) => [
                'texte_complet' => $schema->string()->required(),
            ],
        );

        try {
            $response = $ocrAgent->prompt(
                'Extrais tout le texte visible sur ce reçu.',
                [Image::fromStorage($this->recu->image_path, 'public')],
                null,
                env('GROQ_VISION_MODEL', 'meta-llama/llama-4-scout-17b-16e-instruct'),
            );

            $ocrText = $response->structured['texte_complet'];

            $this->recu->update([
                'texte_brut' => $ocrText,
            ]);

            return $ocrText;
        } catch (AiException $e) {
            Log::error('OCR échoué pour le recu #' . $this->recu->id . ': ' . $e->getMessage());

            return null;
        } catch (\Throwable $e) {
            Log::error('Erreur inattendue lors de l\'OCR pour le recu #' . $this->recu->id . ': ' . $e->getMessage());

            return null;
        }
    }
}
