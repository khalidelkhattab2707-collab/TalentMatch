## 1. Fix AnalyseCvSchema — remove json_schema dependency

- [x] 1.1 Remove `HasStructuredOutput` interface and `use Promptable` trait from `AnalyseCvSchema`
- [x] 1.2 Remove `schema()` method and `JsonSchema` import
- [x] 1.3 Keep `#[Provider(Lab::Groq)]`, `#[Model('llama-3.3-70b-versatile')]`, `#[Temperature(0.3)]`, `#[Timeout(120)]` attributes
- [x] 1.4 Update instructions to explicitly require a JSON response with the expected schema

## 2. Add JSON response validation

- [x] 2.1 Create a static `validateResponse(array $data): array` method on `AnalyseCvSchema` that checks all required fields, types, and value ranges (matching_score 0-100, recommandation enum, etc.)
- [x] 2.2 Throw `AnalyseIAException` with detailed message when validation fails

## 3. Update AnalyseCvJob

- [x] 3.1 Parse the agent response string as JSON (`json_decode($response, true)`)
- [x] 3.2 Call `AnalyseCvSchema::validateResponse()` to validate the parsed data
- [x] 3.3 Create Analyse record with validated data
- [x] 3.4 Update `failed()` method to log the actual error message from the exception

## 4. Add auto-polling via meta refresh

- [x] 4.1 In `candidats/show.blade.php`, add `<meta http-equiv="refresh" content="3">` inside `<head>` when `$candidat->statut_job->value === 'en_cours'`
- [x] 4.2 Show animated spinner + "Analyse en cours — actualisation automatique" text while polling

## 5. Add retry mechanism for failed analyses

- [x] 5.1 Add `retryAnalyse(Offre $offre, Candidat $candidat): RedirectResponse` method to `CandidatController`
- [x] 5.2 Method dispatches a new `AnalyseCvJob`, sets `statut_job` to `en_attente`, redirects back with flash
- [x] 5.3 Add route `POST /offres/{offre}/candidats/{candidat}/retry-analyse` named `offres.candidats.retry-analyse`
- [x] 5.4 In the view, when `statut_job` is `echec`, show error message + "Relancer l'analyse" button

## 6. Update tests

- [x] 6.1 Update `AnalyseCvJobTest` to reflect the new approach (no fake schema, mock the response)
- [x] 6.2 Add test for `validateResponse()` with valid data
- [x] 6.3 Add test for `validateResponse()` with invalid data (missing field, bad score)
- [x] 6.4 Add test for retry route
- [x] 6.5 Run all tests and fix any issues
