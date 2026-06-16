## Why

The RH agent currently creates candidate analyses, but there's no way to ask follow-up questions, compare candidates, or get contextual answers about a specific candidate's analysis. An AI assistant with conversation memory and real-data tools enables richer decision-making without leaving the platform.

## What Changes

1. **RhAssistantAgent** — New AI agent class using `laravel/ai` SDK with Groq/llama-3.3-70b-versatile. Has conversation memory via `laravel/ai`'s built-in conversation system.
2. **GetCandidateAnalysisTool** — Tool that fetches full Analyse record from DB when the RH asks about a candidate's score, skills, or recommendation.
3. **GetJobRequirementsTool** — Tool that fetches Offre criteria when the agent needs requirements context.
4. **CompareCandidatesTool** — Tool that compares two analysed candidates on the same offer.
5. **ConversationController** — CRUD for conversations: create/start, show history, send message + get AI response.
6. **Conversation routes + view** — UI for chatting with the assistant on a candidate's detail page.
7. **"Chat with assistant" button** — On `candidats/show.blade.php`, link to start a conversation.

## Capabilities

### New Capabilities
- `agent-conversation`: Conversational RH assistant with tools and memory, scoped to a candidate context. Supports asking questions, comparing candidates, and browsing analysis data through natural language.

### Modified Capabilities
*(none — this is entirely new functionality)*

## Impact

- `app/AI/Agents/RhAssistantAgent.php` — New agent class
- `app/AI/Tools/GetCandidateAnalysisTool.php` — New tool
- `app/AI/Tools/GetJobRequirementsTool.php` — New tool
- `app/AI/Tools/CompareCandidatesTool.php` — New tool
- `app/Http/Controllers/ConversationController.php` — New controller
- `routes/web.php` — Add conversation routes
- `resources/views/conversations/show.blade.php` — New chat view
- `resources/views/candidats/show.blade.php` — Add "Chat" button (modified)
- `tests/Feature/ConversationTest.php` — New tests
