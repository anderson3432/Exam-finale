<?php
include_once 'connection.php';

function get_all_lines($sql)
{
    $req = mysqli_query(dbconnect(), $sql);
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

function get_one_line($sql)
{

    $req = mysqli_query(dbconnect(), $sql);
    if (!$req) {
        die('Erreur SQL : ' . mysqli_error(dbconnect()));
    }
    $result = mysqli_fetch_assoc($req);
    mysqli_free_result($req);
    return $result;
}

function get_membre_by_etu($numero_etu)
{
    $sql = "SELECT * FROM membre WHERE numero_etu = '$numero_etu'";
    return get_one_line($sql);
}


function inscrire_membre_automatique($numero_etu, $nom)
{
    $link = dbconnect();
    $sql = "INSERT INTO membre (nom, numero_etu) VALUES ('$nom', '$numero_etu')";

    if (mysqli_query($link, $sql)) {
        return mysqli_insert_id($link);
    }
    return false;
}


function get_catalogue_produits()
{
    $sql = "SELECT * FROM produit ORDER BY nom ASC";
    return get_all_lines($sql);
}


function mettre_en_vente($id_produit, $id_membre, $prix_vente, $quantite, $date_dispo, $photo_offre = null)
{
    $photo_value = $photo_offre ? "'$photo_offre'" : "NULL";

    $sql = "INSERT INTO produit_membre (id_produit, id_membre, prix_vente, quantite_dispo, date_dispo, photo_offre) 
            VALUES ($id_produit, $id_membre, $prix_vente, $quantite, '$date_dispo', $photo_value)";

    return mysqli_query(dbconnect(), $sql);
}

function get_produits_en_vente()
{
    $sql = "SELECT 
                pm.id_produit_membre, 
                p.nom AS nom_produit, 
                c.nom_categorie,
                m.nom AS nom_vendeur, 
                pm.prix_vente, 
                pm.quantite_dispo, 
                pm.date_dispo,
                pm.photo_offre
            FROM produit_membre pm
            JOIN produit p ON pm.id_produit = p.id_produit
            JOIN categorie c ON p.id_categorie = c.id_categorie
            JOIN membre m ON pm.id_membre = m.id_membre
            WHERE pm.quantite_dispo > 0
            ORDER BY pm.date_dispo DESC";

    return get_all_lines($sql);
}


function get_offre_by_id($id_produit_membre)
{
    $sql = "SELECT * FROM produit_membre WHERE id_produit_membre = $id_produit_membre";
    return get_one_line($sql);
}


function acheter_produit_direct($id_produit_membre, $quantite_demandee)
{
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
function get_ventes_by_membre($id_membre)
{
    $sql = "SELECT v.id_vente, v.date, v.heure, v.quantite,
                   p.nom AS nom_produit, pm.prix_vente,
                   (v.quantite * pm.prix_vente) AS sous_total
            FROM vente v
            JOIN produit_membre pm ON v.id_produit_membre = pm.id_produit_membre
            JOIN produit p ON pm.id_produit = p.id_produit
            WHERE pm.id_membre = '$id_membre'
            ORDER BY v.date DESC, v.heure DESC";

    return get_all_lines($sql);
}

function get_ventes_par_categorie()
{
    $sql = "SELECT c.id_categorie, c.nom_categorie,
                   SUM(v.quantite) AS total_quantite,
                   SUM(v.quantite * pm.prix_vente) AS total_ca
            FROM vente v
            JOIN produit_membre pm ON v.id_produit_membre = pm.id_produit_membre
            JOIN produit p ON pm.id_produit = p.id_produit
            JOIN categorie c ON p.id_categorie = c.id_categorie
            GROUP BY c.id_categorie, c.nom_categorie
            ORDER BY total_ca DESC";
    return get_all_lines($sql);
}

function get_ventes_par_produit($id_categorie)
{
    $sql = "SELECT p.id_produit, p.nom AS nom_produit,
                   SUM(v.quantite) AS total_quantite,
                   SUM(v.quantite * pm.prix_vente) AS total_ca
            FROM vente v
            JOIN produit_membre pm ON v.id_produit_membre = pm.id_produit_membre
            JOIN produit p ON pm.id_produit = p.id_produit
            WHERE p.id_categorie = $id_categorie
            GROUP BY p.id_produit, p.nom
            ORDER BY total_ca DESC";
    return get_all_lines($sql);
}

function get_ventes_par_membre($id_produit)
{
    $sql = "SELECT m.id_membre, m.nom AS nom_membre,
                   SUM(v.quantite) AS total_quantite,
                   SUM(v.quantite * pm.prix_vente) AS total_ca
            FROM vente v
            JOIN produit_membre pm ON v.id_produit_membre = pm.id_produit_membre
            JOIN membre m ON pm.id_membre = m.id_membre
            WHERE pm.id_produit = $id_produit
            GROUP BY m.id_membre, m.nom
            ORDER BY total_ca DESC";
    return get_all_lines($sql);
}

function get_categorie_by_id($id_categorie)
{
    $sql = "SELECT * FROM categorie WHERE id_categorie = $id_categorie";
    return get_one_line($sql);
}

function get_produit_by_id($id_produit)
{
    $sql = "SELECT * FROM produit WHERE id_produit = $id_produit";
    return get_one_line($sql);
}
function get_categories()
{
    $sql = "SELECT * FROM categorie ORDER BY nom_categorie ASC";
    return get_all_lines($sql);
}

function ajouter_produit($nom, $id_categorie, $prix_reference, $perime)
{
    $sql = "INSERT INTO produit (nom, id_categorie, prix_reference, perime) 
            VALUES ('$nom', $id_categorie, $prix_reference, $perime)";
    return mysqli_query(dbconnect(), $sql);
}

function modifier_produit($id_produit, $nom, $id_categorie, $prix_reference, $perime)
{
    $sql = "UPDATE produit 
            SET nom = '$nom', id_categorie = $id_categorie, prix_reference = $prix_reference, perime = $perime
            WHERE id_produit = $id_produit";
    return mysqli_query(dbconnect(), $sql);
}
