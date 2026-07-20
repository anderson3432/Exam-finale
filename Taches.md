
## FONCTION
    functions.php
            -creer des fonction:
            .get_membre_by_etu (pour avoir l'etu qui met fans le login) (ETU5021)
            .inscrire_membre_automatique(creer une nouveau membre qui n'existe pas encore)(ETU5021)
            .get_catalogue_produits(ETU4663)
            .mettre_en_vente (ETU4663)(V2 MODIFIER ETU4663)
            .get_produits_en_vente(prendre les produits dans le bases) (ETU 004663) (V2 ETU4663)
            .get_offre_by_id(ETU4663)
            .acheter_produit_direct(ETU4663)
            .get_ventes_by_membre(ETU5021)
            .get_ventes_par_categorie, get_ventes_par_produit, get_ventes_par_membre (statistiques)(ETU5021)
            .ajouter_produit, modifier_produit (avec champ perime)(ETU5021)
    -connection.php
        -pour se connecter a sql (ETU 004663)
## PAGE
    -login.php(ETU5021)
        .Une page pour y entrer dans le projet qui necessite une numeros etu
    -accueil.php(ETU4663)
            .une page qui met tous les listes des produits en vente qui affiche ces prix et les quantités à acheter (ETU 004663) 
            .affiche la photo de l'offre (photo par defaut si absente)
        -filtre par categorie et par produit (EN COURS)(ETU4663)
    -vendre.php(ETU4663)
            .page iray izay ahafahana mivarotra produit izay namboarin'ny membre iray(selction de produits, vidiny, quantité hamidy, date ahafahan'ny olona mahita azy) (ETU 004663)
            .ajout d'une photo au plat, photo par defaut si non fournie
    -vente.php(ETU5021)
            .une page qui affiche les details de la vente fais par les membre avec des dates et etc..(ETU5021)
    -ajouter.php(ETU5021)
            .page pour ajouter et modifier un produit, avec case a cocher "perime"
    -statistiques.php(ETU5021)
        .affiche les ventes par categorie, avec lien vers ventes par produit puis ventes par membre(ETU5021)
## INC
    -traitement.php(ETU5021)
        .pour traiter les etu enter dans le utilisateurs 
## SQL
    -CREATION DU BASE:
        .categorie,membre,produit,produit_membre,vente(ETU 004663)
        .manisy valeurs ao anatiny(ETU 004663)
        .manisy sary tao anaty base sy naka(ETU4663)


