<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ComparerController extends Controller
{
    public function compare(Request $request, Offre $offre): View
    {
        Gate::authorize('view', $offre);

        $candidatIds = $request->query('candidats', []);

        if (! is_array($candidatIds)) {
            $candidatIds = [$candidatIds];
        }

        $candidatIds = array_unique(array_filter($candidatIds));

        abort_if(count($candidatIds) !== 2, 422, __('Veuillez sélectionner exactement deux candidats.'));

        $analyses = Analyse::whereIn('candidat_id', $candidatIds)
            ->with('candidat.offre')
            ->get();

        abort_if($analyses->count() !== 2, 422, __('Un ou les deux candidats n\'ont pas d\'analyse.'));

        foreach ($analyses as $analyse) {
            abort_if($analyse->candidat->offre_id !== $offre->id, 403);
        }

        $candidat1 = $analyses->firstWhere('candidat_id', $candidatIds[0]);
        $candidat2 = $analyses->firstWhere('candidat_id', $candidatIds[1]);

        $best = $candidat1->matching_score >= $candidat2->matching_score
            ? $candidat1
            : $candidat2;

        $scoreGap = abs($candidat1->matching_score - $candidat2->matching_score);

        return view('offres.comparer', compact(
            'offre', 'candidat1', 'candidat2', 'best', 'scoreGap'
        ));
    }
}
