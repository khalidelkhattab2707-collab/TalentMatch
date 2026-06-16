## 1. Create AI Tools

- [x] 1.1 Create `GetCandidateAnalysisTool` — implements `Tool`, returns Analyse data as structured string when given candidat_id
- [x] 1.2 Create `GetJobRequirementsTool` — implements `Tool`, returns Offre details when given offre_id
- [x] 1.3 Create `CompareCandidatesTool` — implements `Tool`, returns comparison of two Analyse records when given two candidat IDs

## 2. Create RhAssistantAgent

- [x] 2.1 Create `RhAssistantAgent` with `#[Provider(Lab::Groq)]`, `#[Model('llama-3.3-70b-versatile')]`, `#[MaxSteps(10)]`
- [x] 2.2 Implement `Agent`, `Conversational`, `HasTools` interfaces, use `Promptable` + `RemembersConversations` traits
- [x] 2.3 Accept `Candidat` in constructor and `User` for `forUser()`
- [x] 2.4 Write system prompt instructing the agent to always use tools before answering
- [x] 2.5 Return the 3 tools from `tools()` method

## 3. Update User model

- [x] 3.1 Add `HasConversations` trait to `User` model

## 4. Create ConversationController

- [x] 4.1 Create `ConversationController` with `store` method: creates conversation via `(new RhAssistantAgent($candidat))->forUser($user)->prompt(...)`, redirects to show
- [x] 4.2 Add `show` method: loads conversation via `(new RhAssistantAgent($candidat))->continue($id, as: $user)`, returns view with history
- [x] 4.3 Add `sendMessage` method: posts new message, gets response, returns redirect to show

## 5. Add conversation routes

- [x] 5.1 Add route `POST /conversations` named `conversations.store`
- [x] 5.2 Add route `GET /conversations/{conversation}` named `conversations.show`
- [x] 5.3 Add route `POST /conversations/{conversation}/messages` named `conversations.message`

## 6. Create conversation view

- [x] 6.1 Create `resources/views/conversations/show.blade.php` with chat interface
- [x] 6.2 Display message history in a scrollable container
- [x] 6.3 Add message input form with CSRF
- [x] 6.4 Style messages with role-based styling (user vs assistant)

## 7. Add "Chat" entry point on candidate page

- [x] 7.1 Add "Discuter avec l'assistant" button on `candidats/show.blade.php` linking to conversation route
- [x] 7.2 Button only shows when analysis is complete (`statut_job === 'analyse'`)

## 8. Update tests

- [x] 8.1 Create `ConversationTest` with test for starting a conversation
- [x] 8.2 Add test for sending a message and getting a response
- [x] 8.3 Add test for tool usage (verify agent fakes correctly)
- [x] 8.4 Run all tests and fix any issues
