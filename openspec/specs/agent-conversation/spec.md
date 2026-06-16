## Purpose

Provide a conversational AI assistant for RH users, accessible from any candidate detail page. The assistant (`RhAssistantAgent`) uses the `laravel/ai` SDK with Groq/llama-3.3-70b-versatile, has conversation memory via `RemembersConversations`, and can retrieve real candidate analysis data, job requirements, and compare candidates through dedicated tools.

## Requirements

### Requirement: Assistant conversationnel RH

Le système SHALL fournir un assistant IA conversationnel accessible depuis la page de détail d'un candidat. L'assistant SHALL être contextualisé sur le candidat et son analyse.

#### Scenario: Démarrer une conversation
- **WHEN** l'utilisateur clique sur "Discuter avec l'assistant" depuis la page du candidat
- **THEN** le système crée une nouvelle conversation
- **THEN** le système affiche l'interface de chat

#### Scenario: Reprendre une conversation existante
- **WHEN** l'utilisateur consulte un candidat avec lequel il a déjà discuté
- **THEN** le système affiche la conversation existante
- **THEN** le système affiche l'historique des messages

#### Scenario: Envoyer un message
- **WHEN** l'utilisateur envoie un message
- **THEN** le système transmet le message à l'assistant
- **THEN** l'assistant répond avec des données réelles issues de la base
- **THEN** les messages sont persistés dans la base de données

### Requirement: Outil de consultation d'analyse

Le système SHALL fournir un outil permettant à l'assistant de récupérer l'analyse complète d'un candidat depuis la base de données.

#### Scenario: Consultation d'analyse
- **WHEN** l'assistant a besoin du score, compétences, ou recommandation d'un candidat
- **THEN** l'outil `GetCandidateAnalysisTool` est appelé
- **THEN** il retourne les données structurées de l'analyse

### Requirement: Outil de consultation d'offre

Le système SHALL fournir un outil permettant à l'assistant de récupérer les critères d'une offre d'emploi.

#### Scenario: Consultation d'offre
- **WHEN** l'assistant a besoin des compétences requises ou de l'expérience minimum
- **THEN** l'outil `GetJobRequirementsTool` est appelé
- **THEN** il retourne les informations de l'offre

### Requirement: Outil de comparaison de candidats

Le système SHALL fournir un outil permettant à l'assistant de comparer deux candidats analysés sur la même offre.

#### Scenario: Comparaison de deux candidats
- **WHEN** l'utilisateur demande de comparer deux candidats
- **THEN** l'outil `CompareCandidatesTool` est appelé avec les deux IDs
- **THEN** il retourne les scores, forces, faiblesses et une comparaison

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

### Requirement: Réponses basées sur des données réelles

Le système SHALL garantir que l'assistant répond avec des données réelles, jamais inventées.

#### Scenario: Données invalides
- **WHEN** un outil ne trouve pas les données demandées
- **THEN** l'assistant indique que les données ne sont pas disponibles
- **THEN** l'assistant ne fabrique pas de valeurs inventées

#### Scenario: Appel systématique des outils
- **WHEN** l'utilisateur pose une question sur le score d'un candidat
- **THEN** l'assistant appelle l'outil avant de répondre
- **THEN** la réponse est basée sur les données retournées par l'outil
