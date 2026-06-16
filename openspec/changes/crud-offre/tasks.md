## 1. Model & Migration

- [x] 1.1 Create `StatutOffreEnum` (active, archivee) in `app/Enums/`
- [x] 1.2 Create migration for `offres` table (user_id, titre, description, competences_requises, experience_minimum, statut)
- [x] 1.3 Create `Offre` model with fillable, casts, relations (belongsTo User, hasMany Candidat), scopeActive()
- [x] 1.4 Run migration

## 2. Validation

- [x] 2.1 Create `StoreOffreRequest` with validation rules (titre, description, competences_requises, experience_minimum)

## 3. Controller

- [x] 3.1 Create `OffreController` with index, create, store, show, edit, update, destroy
- [x] 3.2 Implement ownership check in edit/update/destroy

## 4. Routes

- [x] 4.1 Add CRUD routes for `/offres` in `routes/web.php` (auth middleware)

## 5. Views

- [x] 5.1 Create `offres/index.blade.php` (tableau des offres avec compteur candidats)
- [x] 5.2 Create `offres/create.blade.php` (formulaire de création)
- [x] 5.3 Create `offres/show.blade.php` (détail d'une offre avec candidats)
- [x] 5.4 Create `offres/edit.blade.php` (formulaire d'édition)

## 6. User Model

- [x] 6.1 Add `hasMany(Offre::class)` relation to User model

## 7. Navigation

- [x] 7.1 Add link to "Offres" in the navigation layout

## 8. Tests

- [x] 8.1 Write feature tests for CRUD operations (index, create, store, show, edit, update, destroy)
- [x] 8.2 Write feature test for ownership policy
- [x] 8.3 Write feature test for validation rules
- [x] 8.4 Run tests and verify all pass
