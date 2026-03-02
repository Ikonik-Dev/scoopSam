┌──────────────────────────────────────────────────────────────┐
│                                                              │
│   ENTITY (les données)                                       │
│   ─────────────────────                                      │
│   Ce sont les PLANS des tables en base de données.           │
│   Elles ne font RIEN. Elles décrivent à quoi ressemble       │
│   une donnée.                                                │
│                                                              │
│   Category → a un id et un name                              │
│   Product  → a un id, name, description, price, stock        │
│              et appartient à une Category                    │
│   User     → a un email, un password, des rôles              │
│                                                              │
│   Analogie : le PLAN d'un meuble IKEA.                       │
│              Il décrit le meuble mais ce n'est pas le meuble.│
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│   MODEL (données hors BDD)                                   │
│   ────────────────────────                                   │
│   Même principe qu'une Entity mais sans base de données.     │
│   Vit uniquement en mémoire (session).                       │
│                                                              │
│   CartItem → a un productId, productName, price, quantity    │
│              sait calculer son sous-total                    │
│                                                              │
│   Analogie : un POST-IT sur lequel tu notes                  │
│              "3 croissants à 1,20€"                          │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│   REPOSITORY (accès aux données)                             │
│   ──────────────────────────────                             │
│   Ils savent CHERCHER des données en base.                   │
│   Ils parlent SQL (via le QueryBuilder de Doctrine).         │
│   Ils ne prennent AUCUNE décision.                           │
│                                                              │
│   ProductRepository                                          │
│     → findAvailable()            : produits en stock         │
│     → findAvailableByCategory()  : produits d'une catégorie  │
│     → find($id)                  : un produit par son id     │
│                                                              │
│   CategoryRepository                                         │
│     → findAll()                  : toutes les catégories     │
│                                                              │
│   Analogie : le BIBLIOTHÉCAIRE.                              │
│              Tu lui demandes un livre, il sait où chercher.  │
│              Il ne décide pas si tu as le droit de le lire.  │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│   SERVICE (logique métier)                                   │
│   ────────────────────────                                   │
│   Ils contiennent les RÈGLES du projet.                      │
│   Ils coordonnent les Repositories et les données.           │
│   Chaque service a UNE responsabilité.                       │
│                                                              │
│   CartService                                                │
│     → addItem($id)     : vérifie le stock puis ajoute        │
│     → removeItem($id)  : retire un article                   │
│     → clear()          : vide tout                           │
│     → getCart()         : lit le panier en session           │
│     → getTotal()       : calcule le total                    │
│     UTILISE : RequestStack (session) + ProductRepository     │
│                                                              │
│   ProductService                                             │
│     → getAvailableProducts()      : liste les produits       │
│     → getProductsByCategory($id)  : filtre par catégorie     │
│     → getProductById($id)         : un seul produit          │
│     → getAllCategories()           : toutes les catégories   │
│     UTILISE : ProductRepository + CategoryRepository         │
│                                                              │
│   Analogie : le CHEF CUISINIER.                              │
│              Il dit au commis (repository) quoi aller        │
│              chercher, puis il assemble le plat.             │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│   CONTROLLER (chef d'orchestre)                              │
│   ─────────────────────────────                              │
│   Il reçoit la requête du navigateur.                        │
│   Il appelle le bon Service.                                 │
│   Il passe les données au bon Template.                      │
│   Il retourne la réponse au navigateur.                      │
│   Il ne contient PAS de logique métier.                      │
│                                                              │
│   ShopController                                             │
│     → index()  : demande les produits à ProductService       │
│                   puis envoie au template shop/index         │
│     → show()   : demande UN produit à ProductService         │
│                   puis envoie au template shop/product       │
│     UTILISE : ProductService                                 │
│                                                              │
│   CartController                                             │
│     → index()  : demande le panier à CartService             │
│                   puis envoie au template cart/index         │
│     → add()    : dit à CartService d'ajouter un article      │
│     → remove() : dit à CartService de retirer un article     │
│     → clear()  : dit à CartService de tout vider             │
│     UTILISE : CartService                                    │
│                                                              │
│   SecurityController                                         │
│     → login()  : affiche le formulaire de connexion          │
│     → logout() : Symfony s'en occupe automatiquement         │
│     UTILISE : AuthenticationUtils (fourni par Symfony)       │
│                                                              │
│   Analogie : le SERVEUR au restaurant.                       │
│              Il prend ta commande (requête),                 │
│              la transmet au chef (service),                  │
│              et t'apporte le plat (réponse).                 │
│              Il ne cuisine JAMAIS lui-même.                  │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│   TEMPLATE TWIG (affichage)                                  │
│   ─────────────────────────                                  │
│   Transforme les données en HTML lisible par le navigateur.  │
│   Ne contient PAS de logique métier.                         │
│   Reçoit des variables du Controller.                        │
│                                                              │
│   base.html.twig       → navbar + structure commune          │
│   shop/index.html.twig → boucle sur les produits             │
│   cart/index.html.twig → boucle sur les articles du panier   │
│   login.html.twig      → formulaire email/mot de passe       │
│                                                              │
│   Analogie : la VITRINE du magasin.                          │
│              Elle montre les produits mais                   │
│              ne décide pas lesquels afficher.                │
│                                                              │
└──────────────────────────────────────────────────────────────┘
