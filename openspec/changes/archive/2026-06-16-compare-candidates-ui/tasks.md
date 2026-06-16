## 1. Controller & Route

- [x] 1.1 Create `ComparerController` with `compare` action that loads two `Analyse` records by candidat_id, validates they belong to the offer, and returns the comparison view
- [x] 1.2 Add `GET /offres/{offre}/comparer` route with `ComparerController@compare`
- [x] 1.3 Validate exactly 2 candidat IDs in query params, both with existing analyses, both belonging to the offer

## 2. Comparison View

- [x] 2.1 Create `resources/views/offres/comparer.blade.php` with two-column grid layout
- [x] 2.2 Display each candidate's name, matching score (with progress bar), recommendation (with badge), competences extraites, annees_experience, niveau_etudes, points_forts, lacunes, justification
- [x] 2.3 Highlight the better candidate column (higher matching_score) with green border and "Meilleur profil" label
- [x] 2.4 Display score gap between the two candidates

## 3. Offer Page — Checkbox Selection

- [x] 3.1 Add Alpine.js checkbox selection to the ranking table on `offres/show.blade.php`
- [x] 3.2 Add "Comparer" button that enables when exactly 2 candidates are selected
- [x] 3.3 Add form that submits selected candidate IDs to the comparison route

## 4. Testing

- [x] 4.1 Write feature test for comparison page: valid comparison, missing analyses, wrong offer, unauthenticated
- [x] 4.2 Write feature test for checkbox selection on offer show page

## 5. Code Quality

- [x] 5.1 Run `vendor/bin/pint --format agent`
- [x] 5.2 Run full test suite to ensure no regressions
