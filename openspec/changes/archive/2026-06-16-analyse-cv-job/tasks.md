## 1. Enum & Model Updates

- [x] 1.1 Create `app/Enums/RecommandationEnum.php` with `convoquer`, `attente`, `rejeter` cases
- [x] 1.2 Update `app/Enums/StatutJobEnum.php` — add `en_cours`, `analyse`, `echec` cases (keep `en_attente`, remove `accepte`/`refuse`)
- [x] 1.3 Update `app/Models/Analyse.php` — add `belongsTo(Candidat)` relationship, all JSON casts, `RecommandationEnum` cast, `HasFactory` trait
- [x] 1.4 Update `app/Models/Candidat.php` — ensure `statut_job` cast uses updated enum

## 2. AI Schema

- [x] 2.1 Create `app/AI/Schemas/AnalyseCvSchema.php` — structured output schema with system prompt and JSON contract (competences, score, langues, etc.)

## 3. Job

- [x] 3.1 Create `app/Jobs/AnalyseCvJob.php` — queued job that calls Groq via laravel/ai SDK, saves Analyse record, updates candidat status, handles retries and failure
- [x] 3.2 Run `php artisan queue:table` if jobs table migration doesn't exist; run migrations

## 4. Controller & Request Updates

- [x] 4.1 Update `app/Http/Requests/StoreCandidatRequest.php` — add `min:50` rule on `cv_texte`
- [x] 4.2 Update `app/Http/Controllers/CandidatController.php` — dispatch `AnalyseCvJob` after creating candidat, add success flash message

## 5. View Updates

- [x] 5.1 Update `resources/views/candidats/show.blade.php` — show "Analyse en cours" message when no analyse yet

## 6. Tests

- [x] 6.1 Create `tests/Feature/AnalyseCvJobTest.php` with tests: job dispatched on candidat store, job creates analyse record, job handles failure, candidat status updates through the analysis lifecycle
