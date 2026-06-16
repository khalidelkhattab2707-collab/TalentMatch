## ADDED Requirements

### Requirement: Enregistrer un candidat

Le système SHALL permettre à un agent RH d'ajouter un candidat à une offre existante avec les champs : nom, texte du CV (textarea). Le statut initial SHALL être `en_attente`. Le candidat SHALL être associé à l'offre via `offre_id`.

#### Scenario: Création réussie
- **WHEN** l'utilisateur soumet le formulaire d'ajout de candidat avec des données valides pour une offre qu'il possède
- **THEN** le système crée le candidat avec `statut_job = en_attente` et `offre_id = ID de l'offre`
- **THEN** le système redirige vers la page de détail du candidat

#### Scenario: Validation échouée
- **WHEN** l'utilisateur soumet le formulaire avec un nom vide
- **THEN** le système affiche une erreur de validation
- **THEN** le système ne crée pas le candidat

#### Scenario: Candidat pour une offre non possédée
- **WHEN** un utilisateur tente d'ajouter un candidat à une offre qui ne lui appartient pas
- **THEN** le système retourne une erreur 403

### Requirement: Voir le détail d'un candidat

Le système SHALL afficher le détail d'un candidat : nom, texte du CV, statut, et l'analyse IA associée (si disponible).

#### Scenario: Accès au détail autorisé
- **WHEN** l'utilisateur clique sur un candidat depuis la liste des candidats de l'offre
- **THEN** le système affiche le nom, le CV texte, le statut, et les résultats d'analyse (matching score, compétences, etc.)

#### Scenario: Candidat inexistant
- **WHEN** l'utilisateur accède à `/offres/{offre}/candidats/999` (ID inexistant)
- **THEN** le système retourne une erreur 404

#### Scenario: Accès à un candidat d'une offre non possédée
- **WHEN** un utilisateur tente de voir un candidat d'une offre qui ne lui appartient pas
- **THEN** le système retourne une erreur 403
