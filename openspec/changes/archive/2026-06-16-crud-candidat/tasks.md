## 1. Model & Enum

- [x] 1.1 Create `app/Enums/StatutJobEnum.php` with `en_attente`, `accepte`, `refuse` cases
- [x] 1.2 Update `app/Models/Candidat.php` — add `belongsTo(Offre)` relationship, `HasFactory` trait, `StatutJobEnum` cast on `statut_job`

## 2. Form Request

- [x] 2.1 Create `app/Http/Requests/StoreCandidatRequest.php` with validation rules for `nom` (required, string, max:255) and `cv_texte` (required, string)

## 3. Controller

- [x] 3.1 Create `app/Http/Controllers/CandidatController.php` with `create`, `store`, and `show` methods, using `Gate::authorize('view', $offre)` for authorization

## 4. Routes

- [x] 4.1 Add nested candidat routes under `offres/{offre}/candidats` in `routes/web.php` (create, store, show) inside the existing auth group

## 5. Views

- [x] 5.1 Create `resources/views/candidats/create.blade.php` — form with nom, cv_texte fields using x-app-layout
- [x] 5.2 Create `resources/views/candidats/show.blade.php` — display candidat details and linked analyse data
- [x] 5.3 Update `resources/views/offres/show.blade.php` — make candidat names clickable links to candidat show page, add "Ajouter un candidat" button

## 6. Factory

- [x] 6.1 Create `database/factories/CandidatFactory.php` with defaults for nom, cv_texte, offre_id, statut_job

## 7. Tests

- [x] 7.1 Create `tests/Feature/CandidatTest.php` with tests: create form renders, stores candidat, validates request, shows candidat, authorization (403 for other user's offre), redirects unauthenticated
