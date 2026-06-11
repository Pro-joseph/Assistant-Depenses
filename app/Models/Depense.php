<?php

namespace App\Models;

use App\Enums\CategorieDepense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'recu_id',
        'libelle',
        'quantite',
        'prix_unitaire',
        'categorie',
    ];

    protected $casts = [
        'categorie' => CategorieDepense::class,
        'quantite' => 'integer',
        'prix_unitaire' => 'float',
    ];

    public function recu(): BelongsTo
    {
        return $this->belongsTo(Recu::class);
    }

    public function scopeCategorie(Builder $query, string $categorie): Builder
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where('libelle', 'like', '%' . $term . '%');
    }
}
