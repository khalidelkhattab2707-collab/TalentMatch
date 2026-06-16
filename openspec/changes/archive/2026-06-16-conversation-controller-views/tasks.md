## 1. Create ConversationController

- [x] 1.1 Create `ConversationController` with `store` method: creates conversation with UUID, initializes agent, redirects to show
- [x] 1.2 Add `show` method: loads conversation with messages, returns chat view
- [x] 1.3 Add `sendMessage` method: validates input, resumes conversation via `continue()`, redirects

## 2. Add conversation routes

- [x] 2.1 Add route `POST /conversations` named `conversations.store`
- [x] 2.2 Add route `GET /conversations/{conversation}` named `conversations.show`
- [x] 2.3 Add route `POST /conversations/{conversation}/messages` named `conversations.message`

## 3. Create conversation view

- [x] 3.1 Create `resources/views/conversations/show.blade.php` with chat interface
- [x] 3.2 Display message history in a scrollable container with role-based styling
- [x] 3.3 Add message input form with CSRF and validation error display

## 4. Add chat entry point on candidate page

- [x] 4.1 Add "Discuter avec l'assistant" button on `candidats/show.blade.php`
- [x] 4.2 Button only shows when analysis is complete (`statut_job === 'analyse'`)

## 5. Tests

- [x] 5.1 Create `ConversationTest` with test for starting a conversation
- [x] 5.2 Add test for sending a message
- [x] 5.3 Add test for message validation
- [x] 5.4 Run all tests and fix issues
