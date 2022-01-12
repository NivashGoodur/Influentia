"# Influentia" 
# Test Technique - Influentia - Full-Stack

## Contexte

L'entreprise Influentia souhaite mettre en place un blog permettant d'échanger sur des astuces et des informations avec
le monde extérieur. Ce blog permettra également d'avoir des articles premium, qui seront accessibles soit par paiement à
l'unité, soit par un abonnement mensuel permettant d'accéder à l'ensemble de manière illimitée.

## Déroulé du projet (suggestion)

1. Gestion des utilisateurs et des rôles avec Symfony Security Component
   (Page de login + Inscription, Rôle admin et utilisateur).
2. CRUD des articles (pour les admins) avec le Bundle EasyAdmin.
3. Affichage des articles paginés et d'un article avec Knp Paginator
   (pour les visiteurs et les personnes inscrites, ne pas afficher les articles premium aux personnes n'ayant pas payé)
4. Ajout du tunnel d'achat d'un article premium avec Stripe.

## Niveau de difficultés (impact sur le temps nécessaire)
### Moyen
Faire le site sans api, et n'implémenter que le système de vente d'articles premium.

## Technologies utilisées

Git, Symfony 5, Bootstrap, Stripe, Fixtures.
