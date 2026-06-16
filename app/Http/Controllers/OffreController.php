<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOffreRequest;
use App\Models\Offre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OffreController extends Controller
{
    public function index(): View
    {
        $offres = auth()->user()->offres()->withCount('candidats')->latest()->get();

        return view('offres.index', compact('offres'));
    }

    public function create(): View
    {
        return view('offres.create');
    }

    public function store(StoreOffreRequest $request): RedirectResponse
    {
        $offre = auth()->user()->offres()->create($request->validated());

        return to_route('offres.show', $offre);
    }

    public function show(Offre $offre): View
    {
        Gate::authorize('view', $offre);

        $offre->load('candidats.analyse');

        $candidats = $offre->candidats->sortByDesc(function ($c) {
            return $c->analyse?->matching_score ?? -1;
        })->values();

        return view('offres.show', compact('offre', 'candidats'));
    }

    public function edit(Offre $offre): View
    {
        Gate::authorize('update', $offre);

        return view('offres.edit', compact('offre'));
    }

    public function update(StoreOffreRequest $request, Offre $offre): RedirectResponse
    {
        Gate::authorize('update', $offre);

        $offre->update($request->validated());

        return to_route('offres.show', $offre);
    }

    public function destroy(Offre $offre): RedirectResponse
    {
        Gate::authorize('delete', $offre);

        $offre->delete();

        return to_route('offres.index');
    }
}
