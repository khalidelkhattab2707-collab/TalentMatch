## MODIFIED Requirements

### Requirement: Contrat JSON structuré

Le système SHALL garantir que la réponse IA respecte un contrat JSON strict. Comme Groq ne supporte pas `json_schema` response format, le système SHALL utiliser `json_object` et valider le contrat côté client après réception.

#### Scenario: Réponse valide reçue
- **WHEN** l'IA répond avec un JSON valide
- **THEN** le système valide que tous les champs requis sont présents
- **THEN** le système valide que `matching_score` est un entier entre 0 et 100
- **THEN** le système valide que `recommandation` est `convoquer`, `attente`, ou `rejeter`
- **THEN** les données sont sauvegardées dans la table `analyses`

#### Scenario: Réponse invalide
- **WHEN** l'IA répond avec un JSON invalide, ou un champ manquant, ou une valeur hors contrat
- **THEN** le système lève une exception avec le message d'erreur détaillé
- **THEN** le job est marqué comme échoué
- **THEN** `statut_job` passe à `echec`
- **THEN** l'erreur est journalisée

### Requirement: Affichage des résultats d'analyse

Le système SHALL afficher les résultats d'analyse sur la page de détail du candidat quand ils sont disponibles, et SHALL auto-actualiser la page quand l'analyse est en cours.

#### Scenario: Analyse disponible
- **WHEN** l'utilisateur consulte un candidat avec une analyse terminée
- **THEN** le système affiche le score, compétences, points forts, lacunes, recommandation

#### Scenario: Analyse en cours avec auto-polling
- **WHEN** l'utilisateur consulte un candidat avec `statut_job` = `en_cours`
- **THEN** le système affiche un message "Analyse en cours" avec un spinner animé
- **THEN** le système auto-actualise la page toutes les 3 secondes
- **THEN** le polling s'arrête quand `statut_job` n'est plus `en_cours`

#### Scenario: Analyse en échec avec retry
- **WHEN** l'utilisateur consulte un candidat avec `statut_job` = `echec`
- **THEN** le système affiche un message d'erreur clair
- **THEN** le système affiche un bouton "Relancer l'analyse"
- **WHEN** l'utilisateur clique sur "Relancer l'analyse"
- **THEN** le système dispatch `AnalyseCvJob` à nouveau
- **THEN** le `statut_job` repasse à `en_attente`
