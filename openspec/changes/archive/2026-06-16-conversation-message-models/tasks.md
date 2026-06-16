## 1. Create App\Models\Conversation

- [x] 1.1 Create `app/Models/Conversation.php` extending `Laravel\Ai\Models\Conversation`
- [x] 1.2 Add `candidat()` accessor (parses title to find Candidat by nom)
- [x] 1.3 Add `scopeByUser($query, User $user)` scope

## 2. Create App\Models\Message

- [x] 2.1 Create `app/Models/Message.php` extending `Laravel\Ai\Models\ConversationMessage`
- [x] 2.2 Add `user()` relationship method
- [x] 2.3 Add `excerpt(int $length = 100)` method returning truncated content

## 3. Update imports

- [x] 3.1 Update `ConversationController` imports from SDK to `App\Models\Conversation`
- [x] 3.2 Update `ConversationTest` imports to use `App\Models\Conversation`

## 4. Tests

- [x] 4.1 Verify Conversation model extends SDK and `candidat()` works
- [x] 4.2 Verify Message model extends SDK and `excerpt()` works
- [x] 4.3 Run all tests (existing + new) and fix issues
