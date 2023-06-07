### Localization for Waterhole Control Panel

title = Panneau de contrôle

## Dashboard

dashboard-title = Tableau de bord

configure-mail-message = Vous devez configurer un pilote de messagerie pour que Waterhole puisse envoyer des courriels de vérification et des notifications.
debug-mode-on-message = Le mode débogage est activé. Des valeurs de configuration sensibles peuvent être exposées.

getting-started-title = Démarrer avec Waterhole
getting-started-strategy-title = Lire la documentation
getting-started-strategy-description = Apprendre à bâtir une communauté avec Waterhole.
getting-started-structure-title = Mettre en place votre structure
getting-started-structure-description = Configurer les canaux et les pages qui constituent le squelette de votre communauté.
getting-started-groups-title = Définir les groupes d'utilisateurs
getting-started-groups-description = Créer des groupes pour les modérateurs, les membres de l'équipe et les super utilisateurs.
getting-started-design-title = Rejoindre la communauté de Waterhole
getting-started-design-description = Poser des questions, partager des conseils et apprendre à exploiter au mieux votre communauté.

dashboard-users-title = Utilisateurs
dashboard-posts-title = Messages
dashboard-comments-title = Commentaires

period-today = Aujourd'hui
period-last-7-days = Ces 7 derniers jours
period-last-4-weeks = Ces 4 dernières semaines
period-last-3-months = Ces 3 derniers mois
period-last-12-months = Ces 12 derniers mois
period-this-month = Ce mois
period-this-quarter = Ce trimestre
period-this-year = Cette année
period-all-time = Depuis toujours
period-current-heading = Période actuelle
preiod-previous-heading = Période antérieure

## Structure

structure-title = Structure

structure-channel-label = Canal
structure-page-label = Page
structure-link-label = Lien
structure-heading-label = Rubrique
structure-visibility-public-label = Public

structure-navigation-title = Navigation
structure-navigation-description = Déplacez les éléments ici pour les afficher dans le menu de navigation.

structure-unlisted-title = Non répertorié
structure-unlisted-description = Déplacez les éléments ici pour les masquer du menu de navigation.

delete-structure-confirm-message = Êtes-vous sûr de vouloir supprimer ce nœud ?

## Structure - Heading

edit-heading-title = Modifier la rubrique
create-heading-title = Créer une rubrique
heading-name-label = Nom

## Structure - Link

edit-link-title = Modifier le lien
create-link-title = Créer un lien
link-details-title = Informations
link-name-label = Nom
link-url-label = URL
link-permissions-title = Permissions

## Structure - Page

edit-page-title = Modifier la page
create-page-title = Créer une page
page-details-title = Informations
page-name-label = Nom
page-slug-label = Identifiant texte unique
page-slug-url-label = Cette page sera accessible sur :
page-body-label = Corps
page-permissions-title = Permissions

## Structure - Channel

edit-channel-title = Modifier le canal
create-channel-title = Créer un canal
channel-details-title = Informations
channel-name-label = Nom
channel-slug-label = Identifiant texte unique
channel-slug-url-label = Ce canal sera accessible sur :
channel-description-label = Description
channel-description-description = Une courte description de ce à quoi sert ce canal.
channel-options-title = Options
channel-visibility-label = Visibilité
channel-ignore-label = Ignoré par défaut
channel-ignore-description = Masque les messages de ce canal dans le fil d'actualité de tous les utilisateurs, à moins qu'ils ne le suivent explicitement.
channel-layout-title = Disposition
channel-layout-label = Disposition
channel-layout-show-author-label = Afficher l'auteur du message
channel-layout-show-excerpt-label = Afficher l'extrait du message
channel-filters-label = Filtres
channel-custom-filters-label = Utiliser des filtres personnalisés sur ce canal
channel-custom-filters-description = Remplace les filtres généraux de ce canal.
channel-permissions-title = Permissions
channel-features-title = Fonctionnalités
channel-reactions-label = Réactions
channel-reactions-posts-label = Messages
channel-reactions-comments-label = Commentaires
channel-taxonomies-label = Taxonomies
channel-answers-label = Réponses
channel-enable-answers-label = Activer les réponses sur ce canal
channel-enable-answers-description = Permet aux auteurs de messages de marquer un commentaire comme étant la réponse.
channel-posting-title = Publication
channel-instructions-label = Instructions de publication
channel-instructions-description = Affiche les instructions de publication aux utilisateurs lorsqu'ils rédigent des messages dans ce canal.
channel-similar-posts-title = Messages similaires
channel-show-similar-posts-label = Afficher les messages similaires de ce canal en fonction du titre

delete-channel-title = Supprimer le canal :
delete-channel-posts-label = Supprimer { $count } { $count ->
    [one] message
    *[other] messages
}
move-to-channel-posts-label = Déplacer { $count } { $count ->
    [one] message
    *[other] messages
} vers un autre canal

## Groups

groups-title = Groupes
create-group-button = Créer un groupe
group-user-count = { $count } { $count ->
    [one] utilisateur
    *[other] utilisateurs
}

edit-group-title = Modifier le groupe
create-group-title = Créer un groupe
group-details-title = Informations
group-name-label = Nom
group-appearance-label = Apparence
group-show-as-badge-label = Afficher ce groupe comme badge pour l'utilisateur
group-color-label = Couleur
group-icon-label = Icône
group-permissions-title = Permissions

delete-group-confirm-message = Êtes-vous sûr de vouloir supprimer ce groupe ?

## Users

users-title = Utilisateurs
users-filter-placeholder = Filtrer les utilisateurs
users-filter-group-description = Filtrer par groupe
create-user-button = Créer un utilisateur

users-name-column = Nom
users-email-column = Adresse de courriel
users-groups-column = Groupes
users-created-at-column = Création
users-last-seen-at-column = Dernière visite
users-empty-message = Aucun résultat n'a été trouvé

edit-user-title = Modifier l'utilisateur
create-user-title = Créer un utilisateur
user-account-title = Compte
user-name-label = Nom
user-email-label = Adresse de courriel
user-password-label = Mot de passe
user-set-password-label = Définir un nouveau mot de passe
user-groups-label = Groupes
user-profile-title = Profil
user-created-message = L'utilisateur a été créé.
user-saved-message = L'utilisateur a été enregistré.

delete-user-title = Supprimer { $count ->
    [one] l'utilisateur :
    *[other] { $count } utilisateurs
}
keep-user-content-label = Conserver le contenu et le marquer comme anonyme
delete-user-content-label = Supprimer définitivement le contenu
delete-user-success-message = L'utilisateur a été supprimé.

## Reactions

reactions-title = Réactions
reaction-sets-title = Ensembles de réactions
create-reaction-set-button = Créer un ensemble de réactions
edit-reaction-set-title = Modifier l'ensemble de réactions
create-reaction-set-title = Créer un ensemble de réactions
reaction-set-name-label = Nom
reaction-set-reactions-label = Réactions
delete-reaction-set-confirm-message = Êtes-vous sûr de vouloir supprimer cet ensemble de réactions ?
reaction-set-saved-message = L'ensemble de réactions a été enregistré.

edit-reaction-type-title = Modifier le type de réaction
create-reaction-type-title = Créer un type de réaction
reaction-type-name-label = Nom
reaction-type-score-label = Score
reaction-type-score-description = Le nombre de points que vaut cette réaction.
delete-reaction-type-confirm-message = Êtes-vous sûr de vouloir supprimer ce type de réaction ?
reaction-type-saved-message = Le type de réaction a été enregistré.

## Taxonomies

taxonomies-title = Taxonomies
create-taxonomy-button = Créer une taxonomie
create-taxonomy-title = Créer une taxonomie
edit-taxonomy-title = Modifier la taxonomie
taxonomy-details-title = Informations
taxonomy-permissions-title = Permissions
taxonomy-tags-title = Étiquettes
taxonomy-name-label = Nom
taxonomy-saved-message = La taxonomie a été enregistrée.
delete-taxonomy-confirm-message = Êtes-vous sûr de vouloir supprimer cette taxonomie ?

create-tag-title = Créer une étiquette
edit-tag-title = Modifier l'étiquette
tag-name-label = Nom
tag-saved-message = L'étiquette a été enregistrée.
delete-tag-confirm-message = Êtes-vous sûr de vouloir supprimer cette étiquette ?

## Licensing

license-error-message = Votre licence n'a pas pu être validée en raison d'une erreur de communication avec l'API de Waterhole. ({ $status })
license-invalid-message = Veuillez acheter ou saisir une clé de licence valide pour ce site afin de vous conformer à l'accord de licence.
license-expired-message = Vous n'êtes pas autorisé à utiliser cette version de Waterhole. Veuillez rétrograder votre version ou renouveler votre licence.
license-suspended-message = Votre licence Waterhole a été suspendue. Veuillez nous contacter pour plus d'informations.

trial-badge = Version d'essai
licensed-badge = Licence valide
unlicensed-badge = Sans licence
