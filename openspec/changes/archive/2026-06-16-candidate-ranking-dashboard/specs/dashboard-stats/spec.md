## ADDED Requirements

### Requirement: Global dashboard with aggregate statistics

Le système SHALL fournir un tableau de bord global (`/dashboard`) affichant des statistiques agrégées sur l'ensemble des offres et candidats de l'utilisateur connecté.

#### Scenario: Affichage des statistiques
- **WHEN** un utilisateur authentifié accède à `/dashboard`
- **THEN** le système affiche le nombre total d'offres de l'utilisateur
- **THEN** le système affiche le nombre total de candidats soumis
- **THEN** le système affiche le nombre de candidats analysés (statut_job = analyse)
- **THEN** le système affiche le score matching moyen (arrondi à l'entier)
- **THEN** le système affiche la distribution des recommandations (convoquer / attente / rejeter) sous forme de compteurs

#### Scenario: Accès non authentifié
- **WHEN** un visiteur non connecté accède à `/dashboard`
- **THEN** le système redirige vers `/login`

#### Scenario: Aucune donnée
- **WHEN** un utilisateur n'a ni offres ni candidats
- **THEN** le système affiche des valeurs à zéro pour tous les compteurs
- **THEN** le système affiche un message invitant à créer une première offre

### Requirement: Liste globale des candidats classés par score

Le système SHALL afficher sur le dashboard la liste de tous les candidats de l'utilisateur, classés par matching_score décroissant.

#### Scenario: Affichage du classement global
- **WHEN** l'utilisateur consulte le dashboard
- **THEN** le système affiche un tableau des candidats avec les colonnes : nom, offre, matching_score, recommandation
- **THEN** le classement est trié par matching_score décroissant
- **THEN** chaque ligne est cliquable et mène à la page de détail du candidat

#### Scenario: Filtre par recommandation
- **WHEN** l'utilisateur sélectionne un filtre de recommandation (convoquer / attente / rejeter)
- **THEN** le système filtre la liste pour n'afficher que les candidats avec cette recommandation

#### Scenario: Recherche par nom
- **WHEN** l'utilisateur saisit un terme de recherche dans le champ de recherche
- **THEN** le système filtre la liste pour n'afficher que les candidats dont le nom contient le terme saisi
- **THEN** la recherche est insensible à la casse

#### Scenario: Pagination
- **WHEN** l'utilisateur a plus de 25 candidats
- **THEN** le système pagine les résultats (25 par page)
- **THEN** le système affiche les contrôles de pagination

### Requirement: Indicateurs visuels pour les scores et recommandations

Le système SHALL utiliser des indicateurs visuels (badges de couleur, barres de progression) pour représenter les matching scores et les recommandations.

#### Scenario: Barre de progression pour le matching score
- **WHEN** le système affiche un matching score
- **THEN** le système affiche une barre de progression stylisée (largeur = score %)
- **THEN** la barre est verte si score >= 70, orange si score >= 40 et < 70, rouge si score < 40

#### Scenario: Badge coloré pour la recommandation
- **WHEN** le système affiche une recommandation
- **THEN** `convoquer` est affiché avec un badge vert
- **THEN** `attente` est affiché avec un badge orange
- **THEN** `rejeter` est affiché avec un badge rouge
