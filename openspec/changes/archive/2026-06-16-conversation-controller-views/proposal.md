## Why

The RhAssistantAgent was implemented with tools and conversation memory, but there is no controller or UI to interact with it. Users need a dedicated page to chat with the assistant, send messages, and see conversation history — all from within the candidate detail flow.

## What Changes

1. **ConversationController** — New controller with `store`, `show`, and `sendMessage` methods to manage conversation lifecycle.
2. **Conversation routes** — Three new routes for starting, viewing, and messaging conversations.
3. **Conversation view** — Chat UI with message history (scrollable), input form, and role-based styling (user vs assistant).
4. **"Chat" entry point** — Button on the candidate detail page that starts a conversation with the assistant.

## Capabilities

### New Capabilities
- `agent-conversation`: Conversation UI and controller for the RH assistant chat interface

### Modified Capabilities
- *(none — UI layer for existing agent capability)*

## Impact

- `app/Http/Controllers/ConversationController.php` — New controller
- `routes/web.php` — Add conversation routes
- `resources/views/conversations/show.blade.php` — New chat view
- `resources/views/candidats/show.blade.php` — Add "Discuter avec l'assistant" button
- `tests/Feature/ConversationTest.php` — New tests
