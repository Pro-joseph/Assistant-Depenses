<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Support\Facades\DB;

class DepenseController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Depense::class);

        $query = Depense::whereHas('recu', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('recu');

        if ($categorie = request('categorie')) {
            $query->categorie($categorie);
        }

        if ($search = request('q')) {
            $query->search($search);
        }

        $depenses = $query->latest('depenses.created_at')->paginate(15);

        $totalMois = Depense::whereHas('recu', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereMonth('depenses.created_at', now()->month)
          ->sum(DB::raw('quantite * prix_unitaire'));

        $categorieStats = Depense::whereHas('recu', function ($q) {
            $q->where('user_id', auth()->id());
        })->select('categorie', DB::raw('SUM(quantite * prix_unitaire) as total'))
          ->groupBy('categorie')
          ->orderByDesc('total')
          ->get();

        $dominante = $categorieStats->first();
        $totalGlobal = $categorieStats->sum('total');
        $pourcentageDominante = $totalGlobal > 0 ? round(($dominante->total / $totalGlobal) * 100) : 0;

        return view('depenses.index', compact(
            'depenses', 'totalMois', 'categorieStats', 'dominante', 'totalGlobal', 'pourcentageDominante'
        ));
    }
}
