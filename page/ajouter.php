<?php
session_start();
include_once '../fonction/functions.php';

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

$message_succes = "";
$message_erreur = "";

$mode_modif = false;
$id_produit_modif = 0;
$val_nom = "";
$val_categorie = "";
$val_prix = "";
$val_perime = 0;

if (isset($_GET['id_produit'])) {
    $mode_modif = false;

    $produit = get_produit_by_id(intval($_GET['id_produit']));

    if ($produit) {
        $mode_modif = true;
        $id_produit_modif = $produit['id_produit'];
        $val_nom = $produit['nom'];
        $val_categorie = $produit['id_categorie'];
        $val_prix = $produit['prix_reference'];
        $val_perime = $produit['perime'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = $_POST['nom'];
    $id_categorie = intval($_POST['id_categorie']);
    $prix_reference = floatval($_POST['prix_reference']);

    $perime = 0;
    if (isset($_POST['perime'])) {
        $perime = 1;
    }

    if ($nom != "" && $id_categorie > 0 && $prix_reference > 0) {

        if (isset($_POST['action_ajouter'])) {
            $ok = ajouter_produit($nom, $id_categorie, $prix_reference, $perime);
            if ($ok) {
                $message_succes = "Le produit a bien ete ajoute.";
            } else {
                $message_erreur = "Erreur lors de l'ajout du produit.";
            }
        }

        if (isset($_POST['action_modifier'])) {
            $id_produit = intval($_POST['id_produit']);
            $ok = modifier_produit($id_produit, $nom, $id_categorie, $prix_reference, $perime);
            if ($ok) {
                $message_succes = "Le produit a bien ete modifie.";
            } else {
                $message_erreur = "Erreur lors de la modification du produit.";
            }
        }

        $mode_modif = false;
        $val_nom = "";
        $val_categorie = "";
        $val_prix = "";
        $val_perime = 0;
    } else {
        $message_erreur = "Veuillez remplir correctement tous les champs obligatoires.";
    }
}

$categories = get_categories();
$produits = get_catalogue_produits();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSNACKS - Gestion des produits</title>
    <link rel="stylesheet" href="../Theme/Css/style.css">
    <link rel="stylesheet" href="../Theme/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Theme/bootstrap/font/bootstrap-icons.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-app navbar-expand-lg mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="accueil.php"><i class="bi bi-cpu"></i> ITSNACKS</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="accueil.php"><i class="bi bi-house-door"></i>Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendre.php"><i class="bi bi-shop"></i>Vendre</a></li>
                    <li class="nav-item"><a class="nav-link" href="ventes.php"><i class="bi bi-graph-up-arrow"></i>Mes Ventes</a></li>
                    <li class="nav-item"><a class="nav-link" href="statistiques.php"><i class="bi bi-bar-chart-line"></i>Statistiques</a></li>
                    <li class="nav-item"><a class="nav-link active" href="ajouter.php"><i class="bi bi-plus-circle"></i>Ajouter</a></li>
                </ul>
                <div class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> Connecté : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong>
                </div>
                <a href="login.php" class="btn btn-outline-danger btn-logout btn-sm"><i class="bi bi-box-arrow-right"></i>Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">

        <?php if ($message_succes != "") { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i><?= $message_succes ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>
        <?php if ($message_erreur != "") { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i><?= $message_erreur ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <!-- FORMULAIRE -->
        <div class="card card-app p-4 mb-4">

            <?php if ($mode_modif) { ?>
                <h3 class="fw-bold mb-3"><i class="bi bi-pencil-square section-icon"></i>Modifier le produit</h3>
            <?php } else { ?>
                <h3 class="fw-bold mb-3"><i class="bi bi-plus-circle section-icon"></i>Ajouter un produit</h3>
            <?php } ?>

            <form action="ajouter.php" method="POST">

                <?php if ($mode_modif) { ?>
                    <input type="hidden" name="action_modifier" value="1">
                    <input type="hidden" name="id_produit" value="<?= $id_produit_modif ?>">
                <?php } else { ?>
                    <input type="hidden" name="action_ajouter" value="1">
                <?php } ?>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du produit</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($val_nom) ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_categorie" class="form-label">Catégorie</label>
                        <select name="id_categorie" id="id_categorie" class="form-select" required>
                            <option value="" disabled>-- Choisir une catégorie --</option>

                            <?php foreach ($categories as $cat) { ?>

                                <?php if ($val_categorie == $cat['id_categorie']) { ?>
                                    <option value="<?= $cat['id_categorie'] ?>" selected><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                                <?php } else { ?>
                                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                                <?php } ?>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prix_reference" class="form-label">Prix de référence (Ar)</label>
                        <input type="number" name="prix_reference" id="prix_reference" class="form-control" min="1" value="<?= $val_prix ?>" required>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <?php if ($val_perime == 1) { ?>
                        <input type="checkbox" name="perime" id="perime" class="form-check-input" checked>
                    <?php } else { ?>
                        <input type="checkbox" name="perime" id="perime" class="form-check-input">
                    <?php } ?>
                    <label for="perime" class="form-check-label">Produit périmé</label>
                </div>

                <?php if ($mode_modif) { ?>
                    <button type="submit" class="btn btn-app"><i class="bi bi-save"></i> Enregistrer les modifications</button>
                    <a href="ajouter.php" class="btn btn-outline-secondary">Annuler</a>
                <?php } else { ?>
                    <button type="submit" class="btn btn-app"><i class="bi bi-plus-circle"></i> Ajouter le produit</button>
                <?php } ?>

            </form>
        </div>

        <!-- LISTE DES PRODUITS -->
        <div class="card card-app p-4">
            <h3 class="fw-bold mb-3"><i class="bi bi-list-ul section-icon"></i>Liste des produits</h3>

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix de référence</th>
                        <th class="text-center">Périmé</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $p) { ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nom']) ?></td>
                            <td><?= number_format($p['prix_reference'], 0, '.', ' ') ?> Ar</td>
                            <td class="text-center">
                                <?php if ($p['perime'] == 1) { ?>
                                    <span class="badge bg-danger">Périmé</span>
                                <?php } else { ?>
                                    <span class="badge bg-success">OK</span>
                                <?php } ?>
                            </td>
                            <td class="text-end">
                                <a href="ajouter.php?id_produit=<?= $p['id_produit'] ?>" class="btn btn-sm btn-app">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>