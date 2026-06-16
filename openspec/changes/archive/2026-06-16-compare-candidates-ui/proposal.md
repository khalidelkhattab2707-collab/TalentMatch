## Why

Candidate comparison is currently only available through the conversational AI agent. There's no standalone UI for the RH agent to select two candidates and see a side-by-side comparison of scores, skills, strengths, and weaknesses. This makes it hard to quickly decide between top candidates without typing a chat message.

## What Changes

- Add a new **Comparison page** (`/offres/{offre}/comparer`) with a side-by-side view of two candidates
- Add **checkbox selection** on the offer detail page to select two candidates for comparison
- Add a `ComparerController` that loads two candidate analyses and passes them to the comparison view
- Reuse existing `Analyse` model data — no new database tables or AI calls needed

## Capabilities

### New Capabilities
- `compare-candidates`: Side-by-side candidate comparison UI with scores, skills, strengths, weaknesses, and a recommendation

### Modified Capabilities

<!-- No existing specs need requirement changes. The CompareCandidatesTool already exists as an AI tool. -->

## Impact

- **New controller**: `ComparerController` with `compare` action
- **New route**: `GET /offres/{offre}/comparer?candidats[]=1&candidats[]=2`
- **New view**: `offres/comparer.blade.php` — side-by-side comparison layout
- **Modified view**: `offres/show.blade.php` — add checkbox column + "Comparer" button
- **Dependencies**: No new packages — uses existing Tailwind grid layout
- **No database changes** — all data comes from existing `analyses` table
