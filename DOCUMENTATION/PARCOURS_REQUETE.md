Le visiteur clique "Ajouter au panier" sur le clavier (id=1)
     │
     │  Le navigateur envoie POST /cart/add/1
     │
     ▼
┌─────────────────────────────────────────────────────┐
│  SYMFONY KERNEL                                     │
│  Lit la route /cart/add/1                           │
│  Trouve : CartController → méthode add($id = 1)     │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│  CART CONTROLLER                                    │
│  Reçoit id = 1                                      │
│  Appelle $this->cartService->addItem(1)             │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│  CART SERVICE                                       │
│                                                     │
│  1. Demande au ProductRepository :                  │
│     "Donne-moi le produit id=1"                     │
│          │                                          │
│          ▼                                          │
│     ProductRepository fait :                        │
│     SELECT * FROM product WHERE id = 1              │
│          │                                          │
│          ▼                                          │
│     Retourne l'Entity Product                       │
│     (name="Clavier mécanique", price=8999, stock=15)│
│                                                     │
│  2. Vérifie : stock > 0 ? Oui → on continue         │
│                                                     │
│  3. Lit la session : le panier est vide []          │
│                                                     │
│  4. Crée un CartItem :                              │
│     CartItem(productId=1,                           │
│              productName="Clavier mécanique",       │
│              price=8999)                            │
│                                                     │
│  5. Sauvegarde en session :                         │
│     [1 => CartItem]                                 │
│                                                     │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│  CART CONTROLLER (suite)                            │
│                                                     │
│  Ajoute un message flash "Produit ajouté !"         │
│  Redirige vers /shop                                │
│                                                     │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│  NAVIGATEUR                                         │
│  Recharge /shop                                     │
│  Affiche le message flash vert                      │
└─────────────────────────────────────────────────────┘
