## ADDED Requirements

### Requirement: Sélection de deux candidats à comparer

Le système SHALL permettre à l'utilisateur de sélectionner exactement deux candidats sur la page de détail d'une offre pour les comparer côte à côte.

#### Scenario: Sélection de deux candidats
- **WHEN** l'utilisateur coche les cases de deux candidats dans le tableau de l'offre
- **THEN** le système active le bouton "Comparer"
- **WHEN** l'utilisateur clique sur "Comparer"
- **THEN** le système redirige vers la page de comparaison avec les deux IDs

#### Scenario: Validation du nombre de candidats
- **WHEN** l'utilisateur tente de comparer moins de 2 candidats
- **THEN** le système affiche un message d'erreur
- **WHEN** l'utilisateur tente de comparer plus de 2 candidats
- **THEN** le système affiche un message d'erreur

#### Scenario: Candidats sans analyse
- **WHEN** l'utilisateur sélectionne un candidat sans analyse
- **THEN** le système affiche un message indiquant que l'analyse est nécessaire
- **THEN** le système ne permet pas la comparaison

### Requirement: Page de comparaison côte à côte

Le système SHALL afficher une page de comparaison avec les deux candidats disposés en colonnes côte à côte.

#### Scenario: Affichage de la comparaison
- **WHEN** l'utilisateur accède à la page de comparaison avec deux IDs valides
- **THEN** le système affiche les deux candidats en colonnes côte à côte
- **THEN** chaque colonne affiche : nom, matching_score (avec barre de progression), recommandation, compétences extraites, années d'expérience, niveau d'études, points forts, lacunes, justification
- **THEN** le candidat avec le meilleur score est mis en évidence visuellement
- **THEN** l'écart de score est affiché entre les deux colonnes

#### Scenario: Accès à la comparaison depuis l'offre
- **WHEN** l'utilisateur clique sur "Comparer" depuis la page de détail de l'offre
- **THEN** le système redirige vers `/offres/{offre}/comparer?candidats[]=1&candidats[]=2`
- **THEN** le système affiche la comparaison

#### Scenario: Candidats d'une offre non possédée
- **WHEN** un utilisateur tente de comparer des candidats d'une offre qui ne lui appartient pas
- **THEN** le système retourne une erreur 403

### Requirement: Indicateurs visuels de comparaison

Le système SHALL mettre en évidence les différences entre les deux candidats avec des indicateurs visuels.

#### Scenario: Meilleur score mis en évidence
- **WHEN** un candidat a un matching_score plus élevé
- **THEN** son en-tête de colonne est surligné en vert
- **THEN** une étiquette "Meilleur profil" est affichée

#### Scenario: Barres de progression
- **WHEN** le système affiche les matching scores
- **THEN** chaque score est représenté par une barre de progression (largeur = score %)
- **THEN** la couleur suit le code : vert (≥70), orange (40-69), rouge (<40)

#### Scenario: Badge de recommandation
- **WHEN** le système affiche la recommandation de chaque candidat
- **THEN** `convoquer` est affiché avec un badge vert
- **THEN** `attente` est affiché avec un badge orange
- **THEN** `rejeter` est affiché avec un badge rouge
