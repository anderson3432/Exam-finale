<?php
session_start();
include_once '../fonction/functions.php';

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

$page = 'categories';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if ($page == 'categories') {
    $titre = "Ventes par categorie";
    $lignes = get_ventes_par_categorie();
}

elseif ($page == 'produits') {
    $id_categorie = intval($_GET['id_categorie']);
    $categorie = get_categorie_by_id($id_categorie);
    $titre = "Ventes par produit - " . $categorie['nom_categorie'];
    $lignes = get_ventes_par_produit($id_categorie);
}

elseif ($page == 'membres') {
    $id_produit = intval($_GET['id_produit']);
    $produit = get_produit_by_id($id_produit);
    $titre = "Ventes par membre - " . $produit['nom'];
    $lignes = get_ventes_par_membre($id_produit);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSNACKS - Statistiques</title>
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
                    <li class="nav-item"><a class="nav-link active" href="statistiques.php"><i class="bi bi-bar-chart-line"></i>Statistiques</a></li>
                </ul>
                <div class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> Connecté : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong>
                </div>
                <a href="login.php" class="btn btn-outline-danger btn-logout btn-sm"><i class="bi bi-box-arrow-right"></i>Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">

        <div class="row mb-3">
            <div class="col">
                <h2 class="fw-bold"><i class="bi bi-bar-chart-line section-icon"></i><?= $titre ?></h2>

                <?php if ($page != 'categories') { ?>
                    <a href="statistiques.php" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour aux catégories
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="card card-app p-4">

            <?php if (empty($lignes)) { ?>

                <p class="text-muted text-center my-4"><i class="bi bi-inbox"></i> Aucune vente pour le moment.</p>

            <?php } else { ?>

                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <?php if ($page == 'categories') { ?>
                                <th>Catégorie</th>
                            <?php } elseif ($page == 'produits') { ?>
                                <th>Produit</th>
                            <?php } else { ?>
                                <th>Membre</th>
                            <?php } ?>
                            <th class="text-center">Quantité vendue</th>
                            <th class="text-end">Chiffre d'affaires</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($lignes as $ligne) { ?>
                            <tr>

                                <?php if ($page == 'categories') { ?>

                                    <td><?= htmlspecialchars($ligne['nom_categorie']) ?></td>
                                    <td class="text-center"><?= $ligne['total_quantite'] ?></td>
                                    <td class="text-end"><?= $ligne['total_ca'] ?> Ar</td>
                                    <td class="text-end">
                                        <a href="statistiques.php?page=produits&id_categorie=<?= $ligne['id_categorie'] ?>" class="btn btn-sm btn-app">
                                            Voir les produits
                                        </a>
                                    </td>

                                <?php } elseif ($page == 'produits') { ?>

                                    <td><?= htmlspecialchars($ligne['nom_produit']) ?></td>
                                    <td class="text-center"><?= $ligne['total_quantite'] ?></td>
                                    <td class="text-end"><?= $ligne['total_ca'] ?> Ar</td>
                                    <td class="text-end">
                                        <a href="statistiques.php?page=membres&id_produit=<?= $ligne['id_produit'] ?>" class="btn btn-sm btn-app">
                                            Voir les membres
                                        </a>
                                    </td>

                                <?php } else { ?>

                                    <td><?= htmlspecialchars($ligne['nom_membre']) ?></td>
                                    <td class="text-center"><?= $ligne['total_quantite'] ?></td>
                                    <td class="text-end"><?= $ligne['total_ca'] ?> Ar</td>
                                    <td></td>

                                <?php } ?>

                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

            <?php } ?>

        </div>

    </div>

</body>

</html>