## Why

The `llama-3.3-70b-versatile` model on Groq does NOT support the `json_schema` response format. The current `AnalyseCvSchema` uses `laravel/ai`'s `HasStructuredOutput` trait, which sends `response_format: { type: "json_schema", ... }` in the API call. This will cause a runtime 400 error in production. Additionally, the view currently requires a manual refresh to see analysis results — the user experience is poor when waiting for analysis.

## What Changes

1. **Fix Groq JSON schema compatibility** — Replace `json_schema` response format with `json_object` + client-side JSON validation against the schema contract. The structured output is still guaranteed, but enforced on our side after receiving the response.
2. **Add auto-polling for analysis status** — The candidate show page will automatically refresh (or poll via AJAX) while `statut_job` is `en_cours`, eliminating the need for manual refresh.
3. **Improve error visibility** — When analysis fails, display a clear error message on the candidate show page with a retry button.
4. **Add AnalyseCvJob retry from UI** — Allow the RH user to re-dispatch the job if it failed.

## Capabilities

### New Capabilities
*(none — this change modifies existing capabilities)*

### Modified Capabilities
- `analyse-cv`: Fix JSON schema handling for Groq API compatibility; add auto-polling for analysis status; add retry mechanism for failed analyses

## Impact

- `app/AI/Schemas/AnalyseCvSchema.php` — Replace structured output approach with `json_object` + manual validation
- `app/Jobs/AnalyseCvJob.php` — Update response parsing; add better error logging; add retry route
- `resources/views/candidats/show.blade.php` — Add auto-polling JS; show retry button on failure
- `app/Http/Controllers/CandidatController.php` — Add `retryAnalyse` method
- `routes/web.php` — Add retry route
- `tests/Feature/AnalyseCvJobTest.php` — Update to reflect new approach
