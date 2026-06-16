## Why

The current dashboard is a blank page with no useful information. RH agents have to manually navigate through each offer to see candidate analysis results (matching scores, recommendations). There is no global overview of all candidates across offers, no ranking or sorting, and no way to quickly identify the best candidates. This makes the platform inefficient for recruiters who need to prioritize candidates at a glance.

## What Changes

- Add a new **Dashboard page** (`/dashboard`) with key metrics and candidate ranking table
- Add a **candidate ranking view per offer** with sortable columns (matching score, recommendation, experience, name)
- Add **visual indicators** (color-coded badges, progress bars) for matching scores and recommendations
- Add **filtering** by offer, recommendation type, and search by candidate name
- Add a **global statistics section** (total offers, total candidates, average matching score, distribution of recommendations)
- Modify the existing `OffreController@show` view to include the ranking table instead of a flat list
- Add a new `DashboardController` for the dashboard page

## Capabilities

### New Capabilities
- `dashboard-stats`: Global dashboard with aggregate statistics across all offers and candidates
- `candidate-ranking`: Sortable, filterable ranking table of candidates by matching score per offer

### Modified Capabilities

<!-- No existing specs need requirement changes. Implementation details only. -->

## Impact

- **New controller**: `DashboardController` with index action
- **Modified views**: `dashboard.blade.php` (rewrite from blank), `offres/show.blade.php` (add ranking table)
- **New view partials**: `candidates/_ranking-table.blade.php`, `dashboard/_stats-cards.blade.php`
- **Routes**: Update `/dashboard` route in `web.php` to use `DashboardController`
- **Dependencies**: Chart.js or CSS-based progress bars for visual indicators (no new npm packages needed — use Tailwind + Alpine.js)
- **No database changes** — all data already exists in `analyses`, `candidats`, and `offres` tables
