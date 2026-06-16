<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatRequest;
use App\Jobs\AnalyseCvJob;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CandidatController extends Controller
{
    public function create(Offre $offre): View
    {
        Gate::authorize('view', $offre);

        return view('candidats.create', compact('offre'));
    }

    public function store(StoreCandidatRequest $request, Offre $offre): RedirectResponse
    {
        Gate::authorize('view', $offre);

        $candidat = $offre->candidats()->create($request->validated());

        dispatch(new AnalyseCvJob($candidat));

        return to_route('offres.candidats.show', [$offre, $candidat])
            ->with('success', __("Candidat ajouté. L'analyse est en cours."));
    }

    public function show(Offre $offre, Candidat $candidat): View
    {
        Gate::authorize('view', $offre);

        $candidat->load('analyse');

        return view('candidats.show', compact('offre', 'candidat'));
    }
}
