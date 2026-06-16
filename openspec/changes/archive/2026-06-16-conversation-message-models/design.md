## Context

Currently the application uses `Laravel\Ai\Models\Conversation` and `Laravel\Ai\Models\ConversationMessage` from the SDK directly. The `ConversationController` imports these SDK models. While functional, this approach makes it harder to add app-specific behavior like:

- A `candidat()` relationship on Conversation (currently candidat context is stored in the `title` string and parsed manually)
- A formatted `excerpt()` accessor on Message
- Custom query scopes (e.g., `byUser()`, `recent()`)
- Explicit `user()` relationship on Message (exists in SDK but not typed at app level)

The SDK models are not designed to be extended — they use `$guarded = []` and have minimal logic. Creating app-level models that extend the SDK models provides a clean extension point.

## Goals / Non-Goals

**Goals:**
- Create `App\Models\Conversation` extending `Laravel\Ai\Models\Conversation` with a `candidat()` relationship and `scopeByUser()`
- Create `App\Models\Message` extending `Laravel\Ai\Models\ConversationMessage` with a `user()` relationship and `excerpt()` accessor
- Update `ConversationController` to use `App\Models\Conversation`
- Update `ConversationTest` to use `App\Models\Conversation`
- All existing tests continue passing

**Non-Goals:**
- Changing the database schema or migration files
- Adding new database tables
- Modifying the RhAssistantAgent or any AI-related code
- Adding conversation listing, deletion, or admin features

## Decisions

1. **Extend SDK models** rather than wrapping or replacing them — SDK models already handle table config, UUID keys, casting, and relationships. Extending preserves all behavior while allowing additions.

2. **Conversation.candidat() relationship derived from title** — The title format `"Candidat : {nom}"` is parsed to find the matching Candidat. This avoids a schema migration to add `candidat_id`. Documented as a known trade-off.

3. **Message extends ConversationMessage** — Use the full SDK model name to avoid naming conflicts. The SDK model is `ConversationMessage`, so we name our app model `Message` for brevity and clarity.

4. **Update all imports** — Only `ConversationController` and `ConversationTest` import the SDK Conversation model. No other files need changes.

## Risks / Trade-offs

- **[Risk] Extending SDK models may break on SDK version upgrades** — Mitigation: pin `laravel/ai` version in composer.json; the extended models only add methods, they don't override internal behavior
- **[Risk] Title-based candidat() relationship is fragile** — Mitigation: keep the parsing logic simple and well-documented; acceptable for current scope
- **[Trade-off] Message model name `App\Models\Message` may conflict** — Mitigation: check for existing `Message` model (none exists); the name is clear in context
