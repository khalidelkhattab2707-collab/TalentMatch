## Why

Les agents RH ont besoin de gérer leurs offres d'emploi (création, consultation, modification, suppression) pour centraliser le recrutement. Actuellement, aucune fonctionnalité de gestion d'offres n'existe dans l'application.

## What Changes

- Création du modèle `Offre` avec ses relations, casts, et scopes
- Création de l'enum `StatutOffreEnum` (active / archivée)
- Création du controller `OffreController` avec les 7 actions CRUD (index, create, store, show, edit, update, destroy)
- Création de la Form Request `StoreOffreRequest` avec validation
- Création des vues Blade pour la gestion des offres (index, create, show, edit)
- Migration de la table `offres` avec clé étrangère vers `users`
- Routes CRUD sous `/offres` protégées par authentification

## Capabilities

### New Capabilities
- `offre-crud`: CRUD complet des offres d'emploi — création, liste, détail, modification, suppression, avec validation et scopes

### Modified Capabilities
*Aucune — première implémentation*

## Impact

- `app/Models/Offre.php` — nouveau modèle Eloquent
- `app/Enums/StatutOffreEnum.php` — nouvel enum backed string
- `app/Http/Controllers/OffreController.php` — nouveau controller
- `app/Http/Requests/StoreOffreRequest.php` — nouvelle form request
- `database/migrations/*_create_offres_table.php` — nouvelle migration
- `resources/views/offres/` — 4 nouvelles vues (index, create, show, edit)
- `routes/web.php` — nouvelles routes groupées sous `/offres`
- `app/Models/User.php` — ajout de la relation `hasMany` vers `Offre`
