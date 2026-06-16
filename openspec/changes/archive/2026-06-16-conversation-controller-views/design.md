## Context

The RhAssistantAgent and its 3 tools already exist in `app/AI/`. The `laravel/ai` SDK's conversation tables (`agent_conversations`, `agent_conversation_messages`) are migrated. What's missing is the HTTP controller and Blade views to let users interact with the assistant from the browser. The candidate detail page (`candidats/show.blade.php`) already displays analysis results and needs a "Chat" entry point.

## Goals / Non-Goals

**Goals:**
- Create `ConversationController` with store, show, sendMessage methods
- Add Blade chat view with message history and input form
- Add "Discuter avec l'assistant" button on the candidate page (visible when analysis is complete)
- Wire up routes for the 3 controller actions
- Write feature tests for the conversation lifecycle

**Non-Goals:**
- Modifying the RhAssistantAgent, tools, or AI configuration
- Adding authentication beyond existing Gate checks on Candidat
- Listing all conversations, deleting conversations, or admin UI
- Changing the database schema (tables already exist)

## Decisions

1. **Single conversation per candidate per user** — Use `firstOrCreate` on user_id + title to avoid duplicate conversations. If a conversation already exists, redirect to the existing one.

2. **Store candidat context in conversation title** — Title format: `"Candidat : {nom}"`. The `sendMessage` method parses the title to find the associated Candidat and pass it to the agent. Simple, no extra pivot table needed.

3. **Generate UUID for conversation ID** — The SDK's `Conversation` model uses string primary keys. SQLite doesn't auto-generate UUIDs, so the controller generates one via `Str::uuid()` before creating the conversation.

4. **Role-based message styling** — User messages right-aligned (indigo), assistant messages left-aligned (gray). Matches common chat UI conventions.

5. **Use `continue()` for existing conversations** — The `store` method creates a conversation and prompts the agent. The `sendMessage` method uses `continue($conversationId, as: $user)` to resume the existing conversation.

## Risks / Trade-offs

- **[Risk] Parsing conversation title to find candidat is fragile** → Mitigation: maintain exact format `"Candidat : {nom}"`, validate before parsing
- **[Risk] Storing candidat ID in title is not normalized** → Mitigation: simple approach avoids schema changes; acceptable for current scope
