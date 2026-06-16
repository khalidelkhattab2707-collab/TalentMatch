## ADDED Requirements

### Requirement: Lister les offres

Le système SHALL afficher la liste des offres d'emploi de l'utilisateur connecté, triées par date de création (plus récente d'abord), avec le nombre de candidats par offre.

#### Scenario: Accès à la liste
- **WHEN** un utilisateur authentifié accède à `/offres`
- **THEN** le système affiche la liste de ses offres avec le compteur de candidats

#### Scenario: Utilisateur non authentifié
- **WHEN** un visiteur non connecté accède à `/offres`
- **THEN** le système redirige vers `/login`

### Requirement: Créer une offre

Le système SHALL permettre à un agent RH de créer une offre d'emploi avec les champs : titre, description, compétences requises (array), expérience minimum (années). Le statut initial SHALL être "active". L'offre SHALL être associée à l'utilisateur connecté.

#### Scenario: Création réussie
- **WHEN** l'utilisateur soumet le formulaire de création avec des données valides
- **THEN** le système crée l'offre avec `statut = active` et `user_id = auth()->id()`
- **THEN** le système redirige vers la page de détail de l'offre

#### Scenario: Validation échouée
- **WHEN** l'utilisateur soumet le formulaire avec un titre vide
- **THEN** le système affiche une erreur de validation
- **THEN** le système ne crée pas l'offre

#### Scenario: Validation compétences requises
- **WHEN** l'utilisateur soumet le formulaire sans compétences requises
- **THEN** le système affiche une erreur : au moins 1 compétence requise

### Requirement: Voir le détail d'une offre

Le système SHALL afficher le détail d'une offre avec la liste de ses candidats et leur analyse (si disponible).

#### Scenario: Accès au détail
- **WHEN** l'utilisateur clique sur une offre dans la liste
- **THEN** le système affiche le titre, la description, les compétences, l'expérience minimum, le statut, et la liste des candidats avec leur matching_score

#### Scenario: Offre inexistante
- **WHEN** l'utilisateur accède à `/offres/999` (ID inexistant)
- **THEN** le système retourne une erreur 404

### Requirement: Modifier une offre

Le système SHALL permettre à l'agent RH propriétaire de l'offre de modifier ses champs (titre, description, compétences, expérience, statut).

#### Scenario: Modification réussie
- **WHEN** l'utilisateur soumet le formulaire d'édition avec des données valides
- **THEN** le système met à jour l'offre et redirige vers la page de détail

#### Scenario: Modification d'une offre non possédée
- **WHEN** un utilisateur tente de modifier une offre qui ne lui appartient pas
- **THEN** le système retourne une erreur 403

### Requirement: Supprimer une offre

Le système SHALL permettre à l'agent RH propriétaire de supprimer une offre.

#### Scenario: Suppression réussie
- **WHEN** l'utilisateur clique sur "Supprimer" sur sa propre offre
- **THEN** le système supprime l'offre et redirige vers la liste avec un message de confirmation

#### Scenario: Suppression d'une offre non possédée
- **WHEN** un utilisateur tente de supprimer une offre qui ne lui appartient pas
- **THEN** le système retourne une erreur 403

### Requirement: Statut d'une offre

Le système SHALL gérer deux statuts pour une offre : `active` et `archivee`. Un scope `active()` SHALL filtrer les offres actives.

#### Scenario: Filtre actif
- **WHEN** le scope `active()` est appelé sur le modèle Offre
- **THEN** il retourne uniquement les offres avec `statut = active`
