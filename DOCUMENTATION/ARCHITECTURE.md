src/
│
├── Entity/                    ← NATURE : Classes PHP liées à la BDD
│   ├── Category.php           Représente la table "category"
│   ├── Product.php            Représente la table "product"
│   └── User.php               Représente la table "user"
│
├── Model/                     ← NATURE : Classes PHP simples (pas de BDD)
│   └── CartItem.php           Représente une ligne du panier en session
│
├── Repository/                ← NATURE : Requêtes vers la base de données
│   ├── CategoryRepository.php Sait chercher des catégories en BDD
│   └── ProductRepository.php  Sait chercher des produits en BDD
│
├── Service/                   ← NATURE : Logique métier
│   ├── CartService.php        Gère le panier (ajouter, retirer, total)
│   └── ProductService.php     Gère l'accès aux produits et catégories
│
├── Controller/                ← NATURE : Chefs d'orchestre
│   ├── HomeController.php     Redirige "/" vers la boutique
│   ├── SecurityController.php Gère la connexion/déconnexion
│   ├── ShopController.php     Affiche la boutique et les produits
│   ├── CartController.php     Gère les actions du panier
│   └── Admin/
│       ├── DashboardController.php    Page d'accueil admin
│       ├── CategoryCrudController.php Admin des catégories
│       └── ProductCrudController.php  Admin des produits
│
├── DataFixtures/              ← NATURE : Données de test
│   └── AppFixtures.php        Remplit la BDD avec des données de démo
│
templates/                     ← NATURE : Fichiers HTML (Twig)
│   ├── base.html.twig         Le squelette commun à toutes les pages
│   ├── security/
│   │   └── login.html.twig    Formulaire de connexion
│   ├── shop/
│   │   ├── index.html.twig    Liste des produits
│   │   └── product.html.twig  Détail d'un produit
│   ├── cart/
│   │   └── index.html.twig    Page du panier
│   └── admin/
│       └── dashboard.html.twig Page d'accueil admin
│
config/
│   └── packages/
│       └── security.yaml      Règles de sécurité (qui accède à quoi)
