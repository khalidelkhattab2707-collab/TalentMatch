## Context

The current `AnalyseCvSchema` uses `laravel/ai`'s `HasStructuredOutput` trait with a JSON schema definition. This trait sends `response_format: { type: "json_schema", json_schema: { ... } }` to the AI provider. Groq API's `llama-3.3-70b-versatile` model does NOT support the `json_schema` response format — only `json_object` and `text` are supported. This will cause a 400 error at runtime.

Additionally, the candidate show view requires a manual browser refresh to see analysis results, and offers no retry mechanism when analysis fails.

## Goals / Non-Goals

**Goals:**
- Make the AI pipeline work with Groq API restrictions
- Guarantee structured output via client-side validation after receiving the response
- Add auto-polling on the candidate show page while analysis is in progress
- Add a retry mechanism for failed analyses

**Non-Goals:**
- Changing AI provider or model
- Changing the database schema
- Adding new database tables or columns
- Refactoring the entire AI pipeline architecture

## Decisions

1. **Use `json_object` instead of `json_schema`**
   - Replace the `HasStructuredOutput` approach with a manual Agent call using `response_format: { type: "json_object" }`
   - After receiving the response, validate the JSON against the schema contract using PHP validation
   - Alternative considered: switching to `text` format + prompt-based JSON extraction — rejected because `json_object` gives better guarantee of valid JSON output
   - Alternative considered: switching to OpenAI — rejected because Groq is a project requirement

2. **Client-side polling via meta refresh**
   - Use `<meta http-equiv="refresh">` with a 3-second interval when `statut_job` is `en_cours`
   - Alternative considered: AJAX polling with fetch/axios — simpler approach with meta refresh is sufficient and avoids adding JS complexity
   - The meta tag is only rendered when status is `en_cours`, so no polling overhead when analysis is complete

3. **Retry via dedicated controller method**
   - Add `CandidatController@retryAnalyse` that re-dispatches `AnalyseCvJob`
   - Route: `POST /offres/{offre}/candidats/{candidat}/retry-analyse`
   - Named: `candidats.retry-analyse`
   - Alternative considered: retry on GET request — rejected to follow POST for state-changing operations

## Risks / Trade-offs

- **[Risk] Groq may change API support** → Mitigation: the validation layer is provider-agnostic; switching providers only requires changing the Agent config
- **[Risk] `json_object` doesn't guarantee schema compliance** → Mitigation: client-side validation catches non-compliant responses; the test suite includes a test for invalid response handling
- **[Risk] Meta refresh may cause poor UX on slow connections** → Mitigation: 3-second interval is reasonable; user can navigate away freely
- **[Trade-off] Manual validation adds code complexity** → Acceptable: the validation logic is self-contained and testable
