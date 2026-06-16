## Context

L'application TalentMatch est un Laravel 13 avec Breeze (Blade stack, Tailwind CSS, Alpine.js). L'authentification est en place (auth Breeze). Aucun modèle `Offre` ni fonctionnalité de gestion d'offres n'existe dans le code. La base MySQL est configurée.

## Goals / Non-Goals

**Goals:**
- CRUD complet des offres d'emploi accessible uniquement aux utilisateurs authentifiés
- Validation côté serveur via Form Request
- Scoping des offres par utilisateur (un agent RH ne voit que ses propres offres)
- Enum pour le statut (active / archivée) avec cast Eloquent
- Compteur de candidats sur la liste (withCount, pas de N+1)
- Eager loading sur le détail (offre -> candidats -> analyse)

**Non-Goals:**
- Gestion des candidats (feature séparée `candidat-crud`)
- Analyse IA (feature séparée `analyse-ia`)
- Interface API (routes web only)
- Pagination (le volume attendu est faible)

## Decisions

| Décision | Choix | Alternative | Raison |
|---|---|---|---|
| Statut enum | `StatutOffreEnum` backed string | Booléen `is_active` | Extensibilité future (ex: "brouillon", "fermée") |
| Scope actif | `scopeActive()` sur le modèle | Filtre direct dans le controller | Réutilisable et testable |
| Validation | `StoreOffreRequest` dédiée | Validation inline dans le controller | Séparation des responsabilités, réutilisation store/update |
| Routes groupées | `Route::prefix('/offres')->middleware('auth')` | Routes individuelles | Cohérence, factorisation du middleware |
| Propriété | Offre appartient à User (`belongsTo`) | Pas de propriété | Sécurité : un utilisateur ne voit que ses offres |
| Champ compétences | JSON (`array` cast) | Table de normalisation | Simplicité ; pas de besoin de query sur compétences individuellement |

## Risks / Trade-offs

- [N+1] La liste des offres charge le compteur candidats → utiliser `withCount('candidats')`
- [N+1] Le détail charge les candidats et leur analyse → utiliser `load('candidats.analyse')`
- [Sécurité] Un utilisateur pourrait tenter d'accéder à une offre qui n'est pas la sienne → vérification dans edit/update/destroy
- [Validation] `competences_requises` est un array JSON → `StoreOffreRequest` doit valider `array|min:1`
