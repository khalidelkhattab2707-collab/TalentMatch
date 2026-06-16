## 1. Dashboard Controller & Route

- [x] 1.1 Create `DashboardController` with `__invoke` method that queries aggregate stats (total offres, total candidats, analysed count, avg matching score, recommendation distribution) scoped to `auth()->id()`
- [x] 1.2 Update `/dashboard` route in `routes/web.php` from closure to `DashboardController`
- [x] 1.3 Add search + filter GET parameters handling to `DashboardController` (`?recommandation=`, `?search=`)
- [x] 1.4 Add pagination (25 per page) to the global candidate list query in `DashboardController`

## 2. Dashboard View — Stats Cards

- [x] 2.1 Create `resources/views/dashboard/_stats-cards.blade.php` partial with stat cards (total offers, total candidates, analysed count, avg score)
- [x] 2.2 Create `resources/views/dashboard/_recommendation-distribution.blade.php` partial showing convoquer/attente/rejeter badge counters
- [x] 2.3 Rewrite `resources/views/dashboard.blade.php` to include stats partials, candidate ranking table, search/filter form, and empty state

## 3. Ranking Table Partial (Shared)

- [x] 3.1 Create `resources/views/candidates/_ranking-table.blade.php` with columns: rang, nom, offre (dashboard only), matching_score (progress bar), recommandation (badge), experience
- [x] 3.2 Implement matching score progress bar using Tailwind: green (`bg-green-500`) for score >= 70, orange (`bg-yellow-500`) for 40-69, red (`bg-red-500`) for < 40
- [x] 3.3 Implement recommendation badges: green for `convoquer`, yellow for `attente`, red for `rejeter`

## 4. Per-Offer Ranking with Alpine.js Sorting

- [x] 4.1 Update `OffreController@show` to return sorted candidates (default: matching_score desc, nulls last)
- [x] 4.2 Replace flat candidate table in `offres/show.blade.php` with `candidates/_ranking-table.blade.php` partial
- [x] 4.3 Add Alpine.js `x-data` component for client-side column sorting (name, score, recommendation, experience)
- [x] 4.4 Add sort direction indicators (↑/↓ arrows) on active column headers
- [x] 4.5 Add filter dropdown by recommendation on offer show page
- [x] 4.6 Add search input by candidate name on offer show page

## 5. Testing

- [x] 5.1 Write feature test for `DashboardController`: asserts stats display, empty state, search/filter
- [x] 5.2 Write feature test for ranking on offer show page: asserts ordering by score, filtering by recommendation
- [x] 5.3 Write feature test for unauthenticated access (redirect to `/login`)

## 6. Code Quality

- [x] 6.1 Run `vendor/bin/pint --format agent` to fix code style
- [x] 6.2 Run full test suite to ensure no regressions
