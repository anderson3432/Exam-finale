<?php
include_once 'connection.php';

function get_all_lines($sql){
    //echo $sql;
    $req = mysqli_query(dbconnect(),$sql );
    if (!$req) {
        die('Erreur SQL : ' . mysqli_error(dbconnect()));
    }
    $result = array();
    while ($line = mysqli_fetch_assoc($req)) {
        $result[] = $line;
    }
    mysqli_free_result($req);
    return $result;
}

function get_one_line($sql){

    $req = mysqli_query(dbconnect(),$sql );
    if (!$req) {
        die('Erreur SQL : ' . mysqli_error(dbconnect()));
    }
    $result = mysqli_fetch_assoc($req);
    mysqli_free_result($req);
    return $result;
}
// ==========================================
// FONCTIONS DE GESTION DES MEMBRES & LOGIN
// ==========================================

/**
 * Récupère un membre par son numéro ETU
 */
function get_membre_by_etu($numero_etu) {
    $sql = "SELECT * FROM membre WHERE numero_etu = '$numero_etu'";
    return get_one_line($sql);
}

/**
 * Inscrit automatiquement un membre avec son numéro ETU uniquement
 */
function inscrire_membre_automatique($numero_etu,$nom) {
    $link = dbconnect();
    $sql = "INSERT INTO membre (nom, numero_etu) VALUES ('$nom', '$numero_etu')";
    
    if (mysqli_query($link, $sql)) {
        return mysqli_insert_id($link);
    }
    return false;
}

// ==========================================
// FONCTIONS D'ACCUEIL & GESTION DES VENTES
// ==========================================

/**
 * Récupère tous les produits actuellement en vente avec les détails nécessaires
 * (Nom du produit, prix, quantité dispo, et nom du vendeur)
 */
function get_produits_en_vente() {
    $sql = "SELECT 
                pm.id_produit_membre, 
                p.nom AS nom_produit, 
                c.nom_categorie,
                m.nom AS nom_vendeur, 
                pm.prix_vente, 
                pm.quantite_dispo, 
                pm.date_dispo
            FROM produit_membre pm
            JOIN produit p ON pm.id_produit = p.id_produit
            JOIN categorie c ON p.id_categorie = c.id_categorie
            JOIN membre m ON pm.id_membre = m.id_membre
            WHERE pm.quantite_dispo > 0
            ORDER BY pm.date_dispo DESC";
            
    return get_all_lines($sql);
}

/**
 * Récupère une offre de vente spécifique par son ID (pour vérifier le stock)
 */
function get_offre_by_id($id_produit_membre) {
    $sql = "SELECT * FROM produit_membre WHERE id_produit_membre = $id_produit_membre";
    return get_one_line($sql);
}

/**
 * Enregistre un achat direct
 * Gère la diminution du stock et l'insertion de l'historique de vente
 */
function acheter_produit_direct($id_produit_membre, $quantite_demandee) {
    $link = dbconnect();
    
    // 1. On récupère l'offre pour vérifier si le stock est suffisant
    $offre = get_offre_by_id($id_produit_membre);
    
    if (!$offre || $offre['quantite_dispo'] < $quantite_demandee) {
        return false; -- Pas assez de stock ou offre inexistante
    }
    
    // 2. Mettre à jour la quantité disponible dans produit_membre
    $nouveau_stock = $offre['quantite_dispo'] - $quantite_demandee;
    $sql_update = "UPDATE produit_membre SET quantite_dispo = $nouveau_stock WHERE id_produit_membre = $id_produit_membre";
    mysqli_query($link, $sql_update);
    
    // 3. Insérer la ligne dans la table vente
    // On utilise date('Y-m-d') et date('H:i:s') pour avoir l'heure exacte de l'achat en 2026
    $date_actuelle = date('Y-m-d');
    $heure_actuelle = date('H:i:s');
    
    $sql_insert = "INSERT INTO vente (date, heure, id_produit_membre, quantite) 
                   VALUES ('$date_actuelle', '$heure_actuelle', $id_produit_membre, $quantite_demandee)";
                   
    return mysqli_query($link, $sql_insert);
}


?>