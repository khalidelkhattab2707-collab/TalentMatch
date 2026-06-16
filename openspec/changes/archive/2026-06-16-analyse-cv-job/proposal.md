## Why

Candidates are created but never analyzed automatically. The AI analysis pipeline (queue job + structured output) needs to be implemented so that when a CV is submitted, it triggers an asynchronous AI analysis that extracts skills, computes a matching score, and generates a recommendation — all without blocking the UI.

## What Changes

- Create `AnalyseCvJob` queued job that orchestrates the AI analysis pipeline
- Create `AnalyseCvSchema` class using `laravel/ai` structured output
- Complete the `Analyse` model with proper casts, relationships, and `RecommandationEnum` cast
- Create `RecommandationEnum` (convoquer, attente, rejeter)
- Update `StatutJobEnum` to include analysis flow statuses (en_cours, analyse, echec)
- Update `StoreCandidatRequest` with `min:50` rule on `cv_texte`
- Update `CandidatController@store` to dispatch `AnalyseCvJob` after creating candidate
- Update `candidats/show.blade.php` to show "Analysis in progress" when no analyse yet
- Add `min:50` validation rule to `StoreCandidatRequest`
- Write tests for the job and analysis flow

## Capabilities

### New Capabilities
- `analyse-cv`: Asynchronous AI-powered CV analysis via queued job with structured output, candidate status tracking, and analysis results display

### Modified Capabilities
- `candidat-crud`: `CandidatController@store` will now dispatch `AnalyseCvJob`; `StoreCandidatRequest` gains `min:50` rule on `cv_texte`; `StatutJobEnum` gains new cases for analysis flow

## Impact

- **New files:** `app/Jobs/AnalyseCvJob.php`, `app/AI/Schemas/AnalyseCvSchema.php`, `app/Enums/RecommandationEnum.php`
- **Modified models:** `app/Models/Analyse.php` — casts, relationships, `RecommandationEnum`; `app/Models/Candidat.php` — add `belongsTo(Offre)` already done, ensure `StatutJobEnum` has analysis flow cases
- **Modified controller:** `app/Http/Controllers/CandidatController.php` — dispatch job on store
- **Modified request:** `app/Http/Requests/StoreCandidatRequest.php` — add `min:50` on `cv_texte`
- **Modified enum:** `app/Enums/StatutJobEnum.php` — add `en_cours`, `analyse`, `echec` cases
- **Dependencies:** `laravel/ai` already installed, `groq` provider configured
- **Queue:** Uses `database` driver (already configured)
