## Context

The `CompareCandidatesTool` already exists as an AI tool within `RhAssistantAgent` — it queries two `Analyse` records and returns a JSON comparison (scores, strengths, weaknesses, best profile). However, using it requires chatting with the AI agent. There is no direct UI for quickly comparing two candidates side-by-side. The offer detail page (`offres.show`) lists candidates with scores but offers no multi-select or comparison flow.

All data needed for comparison (matching scores, skills, recommendations, strengths, weaknesses) already exists in the `analyses` table. No new AI calls or database changes are needed — the comparison controller can load the same data directly.

## Goals / Non-Goals

**Goals:**
- Add a standalone comparison page at `/offres/{offre}/comparer` showing two candidates side-by-side
- Add checkbox selection on the offer detail page to pick two candidates and trigger comparison
- Display matching scores (with progress bars), skills, strengths, weaknesses, experience, and recommendation for both candidates
- Highlight the better candidate (higher matching score)

**Non-Goals:**
- Comparing more than 2 candidates at once (future enhancement)
- AI-powered comparison text (the tool already handles that via the agent)
- Real-time updates or WebSockets
- Modifying the database schema

## Decisions

1. **New `ComparerController` instead of reusing the AI tool**
   - Why: The AI tool returns JSON for the LLM. A controller can load the `Analyse` records directly with Eloquent, pass them to a Blade view, and avoid the JSON serialization overhead. The controller queries the same underlying data (`Analyse::whereIn('candidat_id', [...])`).

2. **GET route with query parameters for candidate IDs**
   - Why: The comparison is a read-only view. Using `?candidats[]=1&candidats[]=2` in the URL makes it bookmarkable and shareable. The controller validates that exactly two IDs are provided and that both belong to the offer.

3. **Checkbox selection on the offer detail page**
   - Why: Easy to implement with Alpine.js. Each candidate row gets a checkbox bound to an Alpine array (`selected`). When exactly 2 are selected, the "Comparer" button becomes active. This avoids complex form logic.

4. **Two-column grid layout for comparison**
   - Why: Tailwind's `grid grid-cols-2` provides a natural side-by-side comparison. Each column shows one candidate's analysis (scores, skills, etc.). A header row at the top highlights which candidate has the higher score.

## Risks / Trade-offs

- [Risk] User selects two candidates from different offers → Mitigation: Controller validates both candidates belong to the specified offer. Returns 422 if mismatch.
- [Risk] Candidate without analysis selected → Mitigation: Controller checks both have analyses. Redirects back with error message if not.
- [Risk] Selecting the same candidate twice → Mitigation: Controller filters duplicates from the input array.
