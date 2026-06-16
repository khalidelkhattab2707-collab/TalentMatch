## Context

The `laravel/ai` SDK provides built-in conversation management via `RemembersConversations` trait and `Conversational` interface. Migration for `agent_conversations` and `agent_conversation_messages` tables already exists. Current codebase has no agent, tools, controller, or view for conversations.

## Goals / Non-Goals

**Goals:**
- Create `RhAssistantAgent` with 3 tools and conversation memory
- Create `ConversationController` for starting conversations and sending messages
- Add conversation UI on the candidate detail page
- Support comparing two candidates via natural language

**Non-Goals:**
- Replacing the existing `AnalyseCvSchema` structured output
- Adding authentication/authorization beyond existing Gate checks
- Building complex conversation management (no conversation listing, deletion, etc.)
- Changing database schema (migration already exists)

## Decisions

1. **Use `RemembersConversations` trait** instead of manual `messages()` method
   - The SDK handles persistence automatically using the existing migration tables
   - Alternative considered: manual DB reads + `Conversational` interface — rejected for simplicity
   
2. **Pass candidat context via agent constructor**
   - `RhAssistantAgent` receives `Candidat` in constructor, stored as property
   - The system prompt includes candidate name, job title, and analysis results
   - Alternative considered: passing candidat_id as tool parameter — rejected because the agent is inherently scoped to a single candidate conversation

3. **Use `forUser` + `continue` for conversation lifecycle**
   - `ConversationController@store` creates a new conversation via `forUser($user)->prompt(...)`
   - `ConversationController@show` loads existing conversation via `continue($id, as: $user)`
   - `ConversationController@sendMessage` sends a message and returns the response

4. **Add `HasConversations` trait to User model** for relationship access

5. **Use `new AnalyseCvSchema` anonymous agent approach for tool implementation**
   - Tools fetch data from DB via Eloquent, return clean structured strings
   - `GetCandidateAnalysisTool` fetches Analyse record by candidat_id
   - `GetJobRequirementsTool` fetches Offre record
   - `CompareCandidatesTool` fetches two Analyse records and builds a comparison string

6. **Single conversation per candidate per user** — use `firstOrCreate` based on user_id + candidate context

## Risks / Trade-offs

- **[Risk] Groq tool-calling may be unreliable** → Mitigation: test with simple prompts first, limit tools to 3, keep descriptions clear
- **[Risk] Long conversations exceed context window** → Mitigation: `RemembersConversations` limits to 50 messages by default; model can handle 128k context
- **[Risk] No existing tests for agent/tools** → Mitigation: write focused feature tests with `RhAssistantAgent::fake()`
