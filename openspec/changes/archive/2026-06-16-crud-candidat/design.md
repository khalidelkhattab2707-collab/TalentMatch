## Context

The Candidat model exists but is incomplete: it lacks the `belongsTo(Offre)` relationship, the `StatutJobEnum` cast, and the `HasFactory` trait. No controller, routes, views, or tests exist for managing candidates. The existing Offre CRUD provides a proven pattern to follow: manual controller methods, form requests, implicit route-model binding, authorization via policies, and Blade views using Tailwind + x-app-layout.

## Goals / Non-Goals

**Goals:**
- Enable users to create and view candidates (candidats) nested under job offers (offres)
- Complete the Candidat model with proper relationships, enum casting, and factory support
- Follow the existing architectural patterns (Form Requests, policies, Blade views)
- Write tests covering the new functionality

**Non-Goals:**
- Edit, update, or delete candidats (out of scope per the existing openspec config)
- AI analysis functionality — that is a separate capability
- Uploading CV files (CV text is input via textarea, not file upload)
- API endpoints — web-only for now

## Decisions

1. **Nested routes under `/offres/{offre}/candidats`** — Candidats are always scoped to an offre. Nested routes provide clear URL hierarchy and enforce the relationship at the routing level. Matches the existing openspec config specification.

2. **Manual controller methods (not `Route::resource`)** — Follows the existing OffreController pattern. Only 3 methods are needed (create, store, show), making a resource controller overly broad.

3. **Authorization via owning the parent Offre** — A user can only create/view candidats for offres they own. The CandidatController will use `Gate::authorize('view', $offre)` from the parent offre's policy, avoiding a separate CandidatPolicy.

4. **`StatutJobEnum` with `en_attente` / `accepte` / `refuse` statuses** — Follows the existing `StatutOffreEnum` pattern. The default is `en_attente`. An enum provides type safety over a plain string.

5. **Blade views matching offre CRUD pattern** — Use `x-app-layout`, `x-input-label`, `x-text-input`, `x-primary-button` components and dark mode support consistent with existing views.

6. **CandidatFactory for tests** — Follows the OffreFactory pattern, creating a factory with sensible defaults and linking to existing Offre/User factories.

## Risks / Trade-offs

- **Risk:** Adding relationships to the Candidat model could break existing code that relies on the model's current shape. → **Mitigation:** The current model has only an `analyse()` relation and no usage in controllers. The migration is additive (adding `belongsTo` won't break existing queries).
- **Risk:** Nested routes can be verbose (`/offres/{offre}/candidats/{candidat}`). → **Mitigation:** The depth is acceptable for this use case and matches the defined spec. Only 3 routes are needed.
- **Risk:** Hard-coding route prefixes creates tight coupling. → **Mitigation:** This follows the established convention in the project. Named routes provide a level of indirection.
