## Context

The `AnalyseCvJob` orchestrates the AI analysis pipeline: when a candidate is created, a queued job calls the Groq LLM (via `laravel/ai` SDK) with structured output to extract skills, compute a matching score, and generate a recommendation. The `analyses` table and `candidats/show.blade.php` view already exist, but the pipeline between them is missing: no job, no schema, no `RecommandationEnum`, and the `Analyse` model lacks casts and relationships.

## Goals / Non-Goals

**Goals:**
- Background AI analysis triggered automatically when a candidate is created
- Structured output guaranteed via `laravel/ai` schema-based generation
- Candidate status tracking through the analysis lifecycle (en_attente → en_cours → analyse/echec)
- Analysis results stored in the existing `analyses` table
- Graceful error handling (job retries, failure status, logging)

**Non-Goals:**
- Real-time/polling UI updates — page refresh shows results
- Agent conversation (separate feature)
- Comparing candidates (separate feature)
- PDF/CV file parsing — only text-based CV input

## Decisions

1. **`database` queue driver** — Already configured. No change needed. Matches the `config.yaml` spec.

2. **`groq` as AI provider** — Specified in `config.yaml` (`ai_provider: groq`, `ai_model: llama-3.3-70b-versatile`). The `laravel/ai` SDK handles the HTTP call. The `config/ai.php` already has groq configured — just needs `GROQ_API_KEY` in `.env`.

3. **Structured output via `laravel/ai` schemas** — The SDK guarantees JSON output matching the schema. Falls back to exception on schema violation, caught in `AnalyseCvJob::failed()`.

4. **Job retry + failure handling** — 3 retries with 120s timeout per the config spec. On final failure, sets `statut_job = echec` and logs the error.

5. **Prompt in the Schema class** — Keep the system prompt and JSON contract together in `AnalyseCvSchema` for maintainability. The job loads the schema, passes CV text + offre criteria, and saves results.

6. **`RecommandationEnum`** — Backed string enum matching the DB values (`convoquer`, `attente`, `rejeter`). Cast on the `Analyse` model, same pattern as `StatutOffreEnum`.

7. **Analyse model completion** — Add `belongsTo(Candidat)` relationship, all JSON casts, `RecommandationEnum` cast, `HasFactory` trait.

## Risks / Trade-offs

- **Risk:** Groq API timeout or failure → **Mitigation:** 3 retries with 120s timeout; job `failed()` sets `statut_job = echec` and logs.
- **Risk:** LLM returns invalid JSON → **Mitigation:** `laravel/ai` structured output validates schema; exception triggers job failure path.
- **Risk:** Long queue backlog if many CVs submitted at once → **Mitigation:** Database queue handles serial processing; acceptable for this scale.
- **Risk:** Existing `StatutJobEnum` has different cases than config.yaml expects → **Mitigation:** Update the enum to match the config spec (`en_attente`, `en_cours`, `analyse`, `echec`).
