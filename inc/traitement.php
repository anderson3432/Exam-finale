<?php
    session_start();
    include_once ("../fonction/functions.php");

    $action = $_POST['action'] ?? '';

    if ($action == 'connexion') {

        $numero_etu = $_POST['etu'];
        $membre = get_membre_by_etu($numero_etu);

        if ($membre) {
            $_SESSION['id_membre'] = $membre['id_membre'];
            $_SESSION['nom'] = $membre['nom'];
            header("Location: ../page/accueil.php");
            exit;
        } else {
            header("Location: ../page/login.php?new=1&etu=" . urlencode($numero_etu));
            exit;
        }

    } elseif ($action == 'inscription') {

        $numero_etu = $_POST['etu'];
        $nom = $_POST['nom'];

        $id_membre = inscrire_membre_automatique($numero_etu, $nom);

        if ($id_membre) {
            $_SESSION['id_membre'] = $id_membre;
            $_SESSION['nom'] = $nom;
            header("Location: ../page/accueil.php");
            exit;
        } else {
            echo "Erreur lors de l'inscription.";
        }

    } else {
        header("Location: ../page/login.php");
        exit;
    }
?>