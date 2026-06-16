<?php

namespace App\Http\Controllers;

use App\Enums\RecommandationEnum;
use App\Enums\StatutJobEnum;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $userId = auth()->id();

        $totalOffres = Offre::where('user_id', $userId)->count();

        $totalCandidats = Candidat::whereHas('offre', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        $analysedCount = Candidat::whereHas('offre', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('statut_job', StatutJobEnum::Analyse)->count();

        $avgScore = Candidat::whereHas('offre', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereHas('analyse')->with('analyse')->get()->avg('analyse.matching_score');

        $distribution = [
            RecommandationEnum::Convoquer->value => 0,
            RecommandationEnum::Attente->value => 0,
            RecommandationEnum::Rejeter->value => 0,
        ];

        $recCounts = Candidat::whereHas('offre', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereHas('analyse')
            ->with('analyse')
            ->get()
            ->groupBy(fn ($c) => $c->analyse->recommandation->value)
            ->map->count();

        foreach ($recCounts as $rec => $count) {
            $distribution[$rec] = $count;
        }

        $candidatsQuery = Candidat::whereHas('offre', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['analyse', 'offre']);

        if ($request->filled('recommandation')) {
            $candidatsQuery->whereHas('analyse', function ($q) use ($request) {
                $q->where('recommandation', $request->recommandation);
            });
        }

        if ($request->filled('search')) {
            $candidatsQuery->where('nom', 'like', '%'.$request->search.'%');
        }

        $candidats = $candidatsQuery->orderBy(
            Analyse::select('matching_score')->whereColumn('candidat_id', 'candidats.id'),
            'desc'
        )->paginate(25)->withQueryString();

        return view('dashboard', compact(
            'totalOffres', 'totalCandidats', 'analysedCount',
            'avgScore', 'distribution', 'candidats'
        ));
    }
}
