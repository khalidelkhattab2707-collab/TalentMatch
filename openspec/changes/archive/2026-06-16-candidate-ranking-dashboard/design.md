## Context

The TalentMatch application currently has a blank dashboard (`resources/views/dashboard.blade.php`) that only shows "You're logged in!". The job offer detail page (`offres/show.blade.php`) lists candidates in a flat table with name, score, and recommendation — but no sorting, filtering, or visual hierarchy exists. All the data needed for a rich dashboard (analyses with matching scores, recommendations, candidate counts per offer) already exists in the database. No new tables or columns are needed.

The existing `offres.show` view eager-loads `candidats.analyse`, so the relationship data is already available. The dashboard route currently points to a closure in `web.php` that returns the blank view.

## Goals / Non-Goals

**Goals:**
- Replace the blank dashboard with a useful overview page showing key metrics and recent activity
- Add a ranked, sortable candidate table to the offer detail page (replacing the flat list)
- Provide visual indicators (color-coded badges, progress bars) for matching scores and recommendations
- Add filtering capability (by offer, recommendation type, candidate name search) to the ranking
- Keep all data loading within authenticated user's scope (user can only see their own offers/candidates)

**Non-Goals:**
- Real-time or WebSocket-based updates (page refresh is sufficient)
- Export to PDF/CSV (separate future feature)
- Charts or graphs requiring Chart.js (use pure CSS + Tailwind progress bars to avoid new dependencies)
- Modifying the database schema
- Adding new API endpoints

## Decisions

1. **Single `DashboardController` for dashboard data**
   - Why: Keeps the dashboard logic separate from existing controllers. Clean SRP. The controller queries aggregate stats (total offers, total candidates with analyses, average matching score, recommendation distribution) using Eloquent queries with `where('user_id', auth()->id())` scope.

2. **Ranking table lives on offer detail page, not a separate page**
   - Why: The proposal originally mentioned a "candidate ranking view per offer" — the most natural location is on the existing offer show page (`offres/{offre}`), replacing the current flat candidate list. Adding filtering/sorting to the existing table is simpler than creating a new route/view.

3. **Client-side sorting via Alpine.js instead of server-side**
   - Why: The candidate lists per offer are small (tens, not thousands). Alpine.js `x-data` with `x-sort` provides instant sorting without additional HTTP requests. Server-side sorting would require query parameters and re-querying.

4. **Filtering via GET parameters + controller**
   - Why: Unlike sorting, filtering by recommendation type or searching by name benefits from server-side filtering to reduce payload. The controller accepts optional `?recommandation=convoquer&search=john` parameters and applies scoped queries.

5. **Tailwind progress bars for matching score, not Chart.js**
   - Why: Zero additional dependencies. A simple `<div>` with width percentage and color transition (green/yellow/red based on score tier) provides clear visual feedback. Matches the existing Tailwind-based UI.

6. **Dashboard stats as Blade partials**
   - Why: `dashboard/_stats-cards.blade.php` and `candidates/_ranking-table.blade.php` are reusable partials. The ranking table partial can be included in both the dashboard (global view) and the offer detail page (per-offer view) with different data.

## Risks / Trade-offs

- **Risk: Dashboard query performance with many offers/candidates** → Mitigation: Use `withCount` and aggregate queries (no N+1). Paginate the global candidate list on the dashboard (25 per page).
- **Risk: Alpine.js sorting breaks with paginated results** → Mitigation: Client-side sort only on non-paginated per-offer view. Dashboard uses server-side sorting since data is paginated.
- **Risk: Recommendation enum values change** → Mitigation: Use the existing `RecommandationEnum` PHP enum for display logic. Badge colors are mapped to enum cases, not hardcoded strings.
