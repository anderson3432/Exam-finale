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
function inscrire_membre_automatique($numero_etu) {
    $link = dbconnect();
    // On insère juste le numéro ETU, le nom reste vide pour l'instant
    $sql = "INSERT INTO membre (nom, numero_etu) VALUES ('', '$numero_etu')";
    
    if (mysqli_query($link, $sql)) {
        // Renvoie le dernier ID généré (id_membre) pour savoir qui on vient de créer
        return mysqli_insert_id($link);
    }
    return false;
}

/**
 * Met à jour le nom d'un membre
 */
function update_nom_membre($id_membre, $nom) {
    $link = dbconnect();
    $sql = "UPDATE membre SET nom = '$nom' WHERE id_membre = $id_membre";
    return mysqli_query($link, $sql);
}

?>