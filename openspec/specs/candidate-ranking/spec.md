## Purpose

Provide a sortable, filterable ranking table of candidates per job offer, replacing the flat candidate list with visual indicators (progress bars, badges) and client-side sorting for quick candidate evaluation.

## Requirements

### Requirement: Classement des candidats par offre

Le système SHALL afficher un tableau de classement des candidats triable sur la page de détail d'une offre, remplaçant la liste plate actuelle.

#### Scenario: Classement par score décroissant (défaut)
- **WHEN** l'utilisateur consulte le détail d'une offre (`offres.show`)
- **THEN** le système affiche les candidats triés par matching_score décroissant
- **THEN** les candidats sans analyse apparaissent en fin de liste
- **THEN** le classement est mis à jour visuellement avec un numéro de rang

#### Scenario: Tri par colonne (Alpine.js)
- **WHEN** l'utilisateur clique sur l'en-tête de colonne "Nom"
- **THEN** le système trie les candidats par ordre alphabétique (A→Z)
- **THEN** un second clic inverse le tri (Z→A)
- **WHEN** l'utilisateur clique sur l'en-tête "Score"
- **THEN** le système trie par matching_score croissant ou décroissant
- **WHEN** l'utilisateur clique sur l'en-tête "Recommandation"
- **THEN** le système trie par ordre de priorité : convoquer > attente > rejeter > sans analyse
- **WHEN** l'utilisateur clique sur l'en-tête "Expérience"
- **THEN** le système trie par annees_experience croissant ou décroissant

#### Scenario: Indicateur de tri actif
- **WHEN** une colonne est utilisée pour le tri actif
- **THEN** le système affiche une flèche directionnelle (↑ ou ↓) à côté du nom de la colonne

### Requirement: Filtrage des candidats par offre

Le système SHALL permettre à l'utilisateur de filtrer les candidats affichés dans le tableau de classement.

#### Scenario: Filtre par recommandation
- **WHEN** l'utilisateur sélectionne un filtre de recommandation
- **THEN** le système filtre les candidats pour n'afficher que ceux correspondant à la recommandation choisie
- **WHEN** l'utilisateur sélectionne "Tous"
- **THEN** le système affiche tous les candidats de l'offre

#### Scenario: Recherche textuelle
- **WHEN** l'utilisateur tape dans le champ de recherche
- **THEN** le système filtre les candidats dont le nom contient le texte saisi
- **THEN** la recherche est insensible à la casse

### Requirement: Indicateurs visuels dans le tableau de classement

Le système SHALL enrichir le tableau de classement avec des indicateurs visuels pour faciliter la lecture rapide.

#### Scenario: Badge de recommandation coloré
- **WHEN** le système affiche une recommandation dans le tableau
- **THEN** `convoquer` est affiché avec un badge vert (`bg-green-100 text-green-800`)
- **THEN** `attente` est affiché avec un badge orange (`bg-yellow-100 text-yellow-800`)
- **THEN** `rejeter` est affiché avec un badge rouge (`bg-red-100 text-red-800`)

#### Scenario: Barre de progression du score
- **WHEN** le système affiche le matching_score
- **THEN** le score est représenté par une barre de progression horizontale
- **THEN** la largeur de la barre correspond au score en pourcentage
- **THEN** la couleur de la barre suit le code : vert (≥70), orange (40-69), rouge (<40)
- **THEN** le score numérique est affiché à droite de la barre
