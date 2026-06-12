<?php

namespace App\Http\Controllers;

use App\Enums\StatutRecu;
use App\Jobs\ExtraireDepensesDuRecu;
use App\Models\Recu;
use App\Http\Requests\StoreRecuRequest;
use App\Http\Requests\UpdateRecuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecuController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Recu::class);

        $userId = auth()->id();

        $recus = Recu::where('user_id', $userId)
            ->with('depenses')
            ->withCount('depenses')
            ->when($request->q, fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('texte_brut', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            }))
            ->latest()
            ->paginate(10);

        $totalTraite = Recu::where('user_id', $userId)->where('statut', 'traite')->count();
        $enAttente = Recu::where('user_id', $userId)->where('statut', 'en_attente')->count();
        $echoue = Recu::where('user_id', $userId)->where('statut', 'echoue')->count();

        return view('recus.index', compact('recus', 'totalTraite', 'enAttente', 'echoue'));
    }

    public function statuts()
    {
        $recus = Recu::where('user_id', auth()->id())
            ->get(['id', 'statut']);

        return response()->json(['recus' => $recus]);
    }

    public function create()
    {
        $this->authorize('create', Recu::class);

        return view('recus.create');
    }

    public function store(StoreRecuRequest $request)
    {
        $this->authorize('create', Recu::class);

        $recu = Recu::create([
            'user_id' => auth()->id(),
            'texte_brut' => $request->texte_brut,
            'statut' => StatutRecu::EnAttente,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recus/' . $recu->id, 'public');
            $recu->update(['image_path' => $path]);
        }

        ExtraireDepensesDuRecu::dispatch($recu);

        return redirect()->route('recus.index')
            ->with('success', 'Reçu créé avec succès. L\'extraction des dépenses est en cours.');
    }

    public function show(Recu $recu)
    {
        $this->authorize('view', $recu);

        $recu->load('depenses');

        return view('recus.show', compact('recu'));
    }

    public function edit(Recu $recu)
    {
        $this->authorize('update', $recu);

        return view('recus.edit', compact('recu'));
    }

    public function update(UpdateRecuRequest $request, Recu $recu)
    {
        $this->authorize('update', $recu);

        $data = [];
        $shouldReprocess = false;

        if ($request->has('texte_brut')) {
            $data['texte_brut'] = $request->texte_brut;
            if ($recu->texte_brut !== $request->texte_brut) {
                $shouldReprocess = true;
            }
        }

        if ($request->hasFile('image')) {
            if ($recu->image_path) {
                Storage::disk('public')->delete($recu->image_path);
            }

            $path = $request->file('image')->store('recus/' . $recu->id, 'public');
            $data['image_path'] = $path;

            if (!$request->filled('texte_brut')) {
                $data['texte_brut'] = null;
            }
            $shouldReprocess = true;
        } elseif ($request->boolean('supprimer_image') && $recu->image_path) {
            Storage::disk('public')->delete($recu->image_path);
            $data['image_path'] = null;
        }

        if ($shouldReprocess) {
            $data['statut'] = StatutRecu::EnAttente;
        }

        $recu->update($data);

        if ($shouldReprocess) {
            ExtraireDepensesDuRecu::dispatch($recu);
        }

        return redirect()->route('recus.show', $recu)
            ->with('success', 'Reçu mis à jour avec succès.');
    }

    public function destroyImage(Recu $recu)
    {
        $this->authorize('update', $recu);

        if (is_null($recu->image_path)) {
            abort(404);
        }

        Storage::disk('public')->delete($recu->image_path);

        $recu->update(['image_path' => null]);

        return redirect()->back()
            ->with('success', 'Image supprimée avec succès.');
    }

    public function destroy(Recu $recu)
    {
        $this->authorize('delete', $recu);

        if ($recu->image_path) {
            Storage::disk('public')->delete($recu->image_path);
        }

        $recu->delete();

        return redirect()->route('recus.index')
            ->with('success', 'Reçu supprimé avec succès.');
    }
}
