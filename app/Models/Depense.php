<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
