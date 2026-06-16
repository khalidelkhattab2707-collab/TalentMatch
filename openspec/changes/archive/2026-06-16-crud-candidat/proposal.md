## Why

Candidates (candidats) are uploaded against job offers (offres) and are the core entity driving the AI analysis feature. Currently, the Candidat model exists but there is no UI to create, view, or manage candidates. Users need CRUD functionality for candidats nested within each offre to upload CVs, review candidate details, and trigger analyses.

## What Changes

- Add nested CRUD routes for candidats under `/offres/{offre}/candidats`
- Create `CandidatController` with `create`, `store`, and `show` methods
- Create `candidats/create.blade.php` and `candidats/show.blade.php` views
- Create `StoreCandidatRequest` form request for validation
- Complete the `Candidat` model with missing relationships (`belongsTo Offre`), enum cast (`StatutJobEnum`), and `HasFactory` trait
- Create `StatutJobEnum` enum
- Create `CandidatFactory` for testing
- Add a `StatutJobEnum` cast migration for the `statut_job` column
- Add navigation link for candidats within the offre show page
- Write Pest tests for all new functionality

## Capabilities

### New Capabilities
- `candidat-crud`: Create and view candidates (candidats) nested under job offers, with CV text input and status management

### Modified Capabilities
- *(None — no existing specs to modify)*

## Impact

- **Models:** `app/Models/Candidat.php` — add `belongsTo(Offre)`, `StatutJobEnum` cast, `HasFactory`
- **New files:** `CandidatController`, `StoreCandidatRequest`, `StatutJobEnum`, `candidats/create.blade.php`, `candidats/show.blade.php`, `CandidatFactory`, `CandidatTest`
- **Routes:** New nested routes under `offres/{offre}/candidats` in `routes/web.php`
- **Navigation:** Update offre show view with link to create/view candidats
- **Database:** Optional cast migration for `statut_job` enum column
