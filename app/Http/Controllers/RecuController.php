<?php

namespace App\Http\Controllers;

use App\Models\Recu;
use App\Http\Requests\StoreRecuRequest;
use App\Http\Requests\UpdateRecuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecuController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Recu::class);

        $recus = Recu::where('user_id', auth()->id())
            ->with('depenses')
            ->withCount('depenses')
            ->latest()
            ->paginate(10);

        return view('recus.index', compact('recus'));
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
            'statut' => 'en_attente',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recus/' . $recu->id, 'public');
            $recu->update(['image_path' => $path]);
        }

        $job = 'App\\Jobs\\ExtraireDepensesDuRecu';
        if (class_exists($job)) {
            $job::dispatch($recu);
        }

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

        if ($request->filled('texte_brut')) {
            $data['texte_brut'] = $request->texte_brut;
            $data['statut'] = 'en_attente';
        }

        if ($request->hasFile('image')) {
            if ($recu->image_path) {
                Storage::disk('public')->delete($recu->image_path);
            }

            $path = $request->file('image')->store('recus/' . $recu->id, 'public');
            $data['image_path'] = $path;
        }

        $recu->update($data);

        return redirect()->route('recus.show', $recu)
            ->with('success', 'Reçu mis à jour avec succès.');
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
