## Why

The project currently uses SDK models (`Laravel\Ai\Models\Conversation` and `Laravel\Ai\Models\ConversationMessage`) directly. This limits the ability to add app-specific logic, custom scopes, formatted accessors, and explicit relationships to other domain models (Candidat, Offre). App-specific models provide a clean extension point without modifying vendor code.

## What Changes

1. **Conversation model** — Create `app/Models/Conversation.php` extending the SDK's `Conversation` model, adding a `candidat()` relationship via title parsing.
2. **Message model** — Create `app/Models/Message.php` extending the SDK's `ConversationMessage` model, adding a `user()` relationship and formatted content accessor.
3. **User model update** — Add `hasMany(Conversation::class)` relationship explicitly (replacing sole reliance on the SDK trait).
4. **Controller update** — Update `ConversationController` imports to use `App\Models\Conversation` instead of `Laravel\Ai\Models\Conversation`.
5. **Test update** — Update `ConversationTest` imports to use app models.

## Capabilities

### New Capabilities
- `conversation-models`: Application-specific Eloquent models for conversations and messages

### Modified Capabilities
- *(none — new capability, not modifying existing spec behavior)*

## Impact

- `app/Models/Conversation.php` — New model extending SDK Conversation
- `app/Models/Message.php` — New model extending SDK ConversationMessage
- `app/Models/User.php` — Add explicit `conversations()` relationship
- `app/Http/Controllers/ConversationController.php` — Update imports
- `tests/Feature/ConversationTest.php` — Update imports
