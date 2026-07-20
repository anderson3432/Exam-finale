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


?>