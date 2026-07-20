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
// FONCTIONS DE VENTE & CATALOGUE (AJOUT)
// ==========================================

/**
 * Récupère tous les produits du catalogue général
 */
function get_catalogue_produits() {
    $sql = "SELECT * FROM produit ORDER BY nom ASC";
    return get_all_lines($sql);
}

/**
 * Insère une nouvelle offre de vente pour un membre
 */
function mettre_en_vente($id_produit, $id_membre, $prix_vente, $quantite, $date_dispo) {
    $sql = "INSERT INTO produit_membre (id_produit, id_membre, prix_vente, quantite_dispo, date_dispo) 
            VALUES ($id_produit, $id_membre, $prix_vente, $quantite, '$date_dispo')";
    return mysqli_query(dbconnect(), $sql);
}

/**
 * Récupère tous les produits actuellement en vente avec les détails nécessaires
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
 * Récupère une offre de vente spécifique par son ID
 */
function get_offre_by_id($id_produit_membre) {
    $sql = "SELECT * FROM produit_membre WHERE id_produit_membre = $id_produit_membre";
    return get_one_line($sql);
}

/**
 * Enregistre un achat direct et met à jour les stocks
 */
function acheter_produit_direct($id_produit_membre, $quantite_demandee) {
    $offre = get_offre_by_id($id_produit_membre);
    
    if (!$offre || $offre['quantite_dispo'] < $quantite_demandee) {
        return false;
    }
    
    $nouveau_stock = $offre['quantite_dispo'] - $quantite_demandee;
    $sql_update = "UPDATE produit_membre SET quantite_dispo = $nouveau_stock WHERE id_produit_membre = $id_produit_membre";
    mysqli_query(dbconnect(), $sql_update);
    
    $date_actuelle = date('Y-m-d');
    $heure_actuelle = date('H:i:s');
    
    $sql_insert = "INSERT INTO vente (date, heure, id_produit_membre, quantite) 
                   VALUES ('$date_actuelle', '$heure_actuelle', $id_produit_membre, $quantite_demandee)";
                   
    return mysqli_query(dbconnect(), $sql_insert);
}
?>