## ADDED Requirements

### Requirement: Modèle Conversation applicatif

Le système SHALL fournir un modèle Eloquent `App\Models\Conversation` qui étend le modèle du SDK pour ajouter des fonctionnalités propres à l'application.

#### Scenario: Extension du modèle SDK
- **WHEN** le système utilise `App\Models\Conversation`
- **THEN** le modèle hérite de toutes les fonctionnalités de `Laravel\Ai\Models\Conversation`
- **THEN** le modèle utilise la même table `agent_conversations`

#### Scenario: Relation candidat
- **WHEN** l'application accède à `$conversation->candidat`
- **THEN** le système retourne le candidat associé à la conversation via le titre
- **THEN** la recherche est basée sur le format `"Candidat : {nom}"`

#### Scenario: Scope par utilisateur
- **WHEN** l'application utilise `Conversation::byUser($user)`
- **THEN** le système filtre les conversations par `user_id`

### Requirement: Modèle Message applicatif

Le système SHALL fournir un modèle Eloquent `App\Models\Message` qui étend `Laravel\Ai\Models\ConversationMessage`.

#### Scenario: Extension du modèle SDK
- **WHEN** le système utilise `App\Models\Message`
- **THEN** le modèle hérite de toutes les fonctionnalités de `Laravel\Ai\Models\ConversationMessage`
- **THEN** le modèle utilise la même table `agent_conversation_messages`

#### Scenario: Relation utilisateur
- **WHEN** l'application accède à `$message->user`
- **THEN** le système retourne l'utilisateur associé au message

#### Scenario: Accesseur résumé
- **WHEN** l'application appelle `$message->excerpt()`
- **THEN** le système retourne les 100 premiers caractères du contenu
