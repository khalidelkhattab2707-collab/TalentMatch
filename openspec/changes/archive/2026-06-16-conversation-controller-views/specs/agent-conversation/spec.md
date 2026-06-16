## ADDED Requirements

### Requirement: Interface de conversation

Le système SHALL fournir une interface de conversation en temps réel permettant à l'utilisateur d'échanger avec l'assistant RH.

#### Scenario: Bouton d'entrée
- **WHEN** l'utilisateur consulte un candidat avec une analyse terminée (`statut_job` = `analyse`)
- **THEN** le système affiche un bouton "Discuter avec l'assistant"
- **WHEN** l'utilisateur clique sur le bouton
- **THEN** le système crée ou récupère la conversation associée

#### Scenario: Affichage de l'historique
- **WHEN** l'utilisateur ouvre une conversation existante
- **THEN** le système charge et affiche l'ensemble des messages (utilisateur et assistant)
- **THEN** les messages sont affichés dans un conteneur scrollable

#### Scenario: Envoi d'un message
- **WHEN** l'utilisateur soumet le formulaire avec un message non vide
- **THEN** le système envoie le message à l'assistant
- **THEN** la réponse de l'assistant est sauvegardée et affichée
- **THEN** l'utilisateur est redirigé vers la conversation mise à jour

#### Scenario: Validation du formulaire
- **WHEN** l'utilisateur soumet un message vide
- **THEN** le système affiche une erreur de validation sur le champ `contenu`

### Requirement: Contrôleur de conversation

Le système SHALL exposer un contrôleur dédié à la gestion des conversations.

#### Scenario: Création de conversation
- **WHEN** une requête POST est envoyée à `conversations.store`
- **THEN** le contrôleur crée une nouvelle conversation avec UUID
- **THEN** le contrôleur initialise l'assistant avec le contexte du candidat
- **THEN** le système retourne une redirection vers l'interface de chat

#### Scenario: Reprise de conversation
- **WHEN** une requête GET est envoyée à `conversations.show`
- **THEN** le contrôleur charge les messages de la conversation
- **THEN** le système retourne la vue de chat avec l'historique

#### Scenario: Envoi d'un message via le contrôleur
- **WHEN** une requête POST est envoyée à `conversations.message`
- **THEN** le contrôleur valide le message
- **THEN** le contrôleur transmet le message à l'assistant via `continue()`
- **THEN** le système redirige vers la vue de la conversation
