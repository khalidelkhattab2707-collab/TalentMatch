## ADDED Requirements

### Requirement: Analyse automatique du CV

Le système SHALL analyser automatiquement le CV d'un candidat via une file d'attente (queue) dès que le candidat est créé. L'analyse SHALL être asynchrone — la page ne se fige pas.

#### Scenario: Analyse déclenchée à la création
- **WHEN** un candidat est créé via `CandidatController@store`
- **THEN** le système dispatch `AnalyseCvJob` en queue
- **THEN** le statut du candidat passe à `en_attente`
- **THEN** l'utilisateur est redirigé vers la page de détail du candidat sans attendre

#### Scenario: Analyse réussie
- **WHEN** le job `AnalyseCvJob` s'exécute avec succès
- **THEN** le statut du candidat passe à `en_cours` pendant l'analyse
- **THEN** le système appelle l'IA avec le prompt et le schéma structuré
- **THEN** une entrée `Analyse` est créée avec les résultats (compétences, score, recommandation)
- **THEN** le statut du candidat passe à `analyse`

#### Scenario: Analyse en échec
- **WHEN** l'appel API IA échoue après 3 tentatives
- **THEN** le statut du candidat passe à `echec`
- **THEN** l'erreur est journalisée

### Requirement: Contrat JSON structuré

Le système SHALL garantir que la réponse IA respecte un contrat JSON strict via `laravel/ai` structured output.

#### Scenario: Schéma respecté
- **WHEN** l'IA répond avec un JSON valide conforme au schéma
- **THEN** les données sont sauvegardées dans la table `analyses`
- **THEN** le matching_score est compris entre 0 et 100

#### Scenario: Schéma non respecté
- **WHEN** l'IA répond avec un JSON invalide ou hors schéma
- **THEN** le SDK lève une exception
- **THEN** le job est marqué comme échoué
- **THEN** `statut_job` passe à `echec`

### Requirement: Statut du candidat pendant l'analyse

Le système SHALL mettre à jour le statut du candidat tout au long du cycle d'analyse.

#### Scenario: Cycle de statut complet
- **WHEN** le candidat est créé → statut `en_attente`
- **WHEN** le job commence → statut `en_cours`
- **WHEN** l'analyse réussit → statut `analyse`
- **WHEN** l'analyse échoue → statut `echec`

### Requirement: Affichage des résultats d'analyse

Le système SHALL afficher les résultats d'analyse sur la page de détail du candidat quand ils sont disponibles.

#### Scenario: Analyse disponible
- **WHEN** l'utilisateur consulte un candidat avec une analyse terminée
- **THEN** le système affiche le score, compétences, points forts, lacunes, recommandation

#### Scenario: Analyse en cours
- **WHEN** l'utilisateur consulte un candidat sans analyse terminée
- **THEN** le système affiche un message "Analyse en cours"
