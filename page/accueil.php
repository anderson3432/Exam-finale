<?php
session_start();
include_once '../fonction/functions.php';

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

$message_succes = "";
$message_erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_achat'])) {
    $id_produit_membre = intval($_POST['id_produit_membre']);
    $quantite_demandee = intval($_POST['quantite']);

    if ($quantite_demandee > 0) {
        $achat_reussi = acheter_produit_direct($id_produit_membre, $quantite_demandee);
        if ($achat_reussi) {
            $message_succes = "Achat effectué avec succès !";
        } else {
            $message_erreur = "Erreur : Stock insuffisant.";
        }
    } else {
        $message_erreur = "Veuillez saisir une quantité supérieure à 0.";
    }
}

$produits = get_produits_en_vente();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSNACKS - Accueil</title>
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
                    <li class="nav-item"><a class="nav-link active" href="accueil.php"><i class="bi bi-house-door"></i>Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendre.php"><i class="bi bi-shop"></i>Vendre</a></li>
                    <li class="nav-item"><a class="nav-link" href="ventes.php"><i class="bi bi-graph-up-arrow"></i>Mes Ventes</a></li>
                </ul>
                <div class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> Connecté : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong>
                </div>
                <a href="login.php" class="btn btn-outline-danger btn-logout btn-sm"><i class="bi bi-box-arrow-right"></i>Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (!empty($message_succes)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($message_succes) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($message_erreur)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($message_erreur) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold"><i class="bi bi-shop-window section-icon"></i>Place du Marché ITSNACKS</h2>
                <p class="text-muted">Achetez directement les produits disponibles.</p>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (empty($produits)): ?>
                <div class="col-12 text-center my-5">
                    <div class="card card-app p-5">
                        <p class="text-muted fs-5 mb-0"><i class="bi bi-inbox"></i> Rien à vendre pour le moment.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($produits as $p): ?>
                    <div class="col">
                        <div class="card card-app h-100 shadow-sm overflow-hidden">
                            
                            <!-- 🌟 ZONE IMAGE MISE À JOUR (SANS COLONNE PHOTO_DEFAUT) 🌟 -->
                            <div style="height: 160px; overflow: hidden; background-color: #e9ecef;">
                                <?php 
                                // Priorité 1 : La photo spécifique associée à l'offre
                                if (!empty($p['photo_offre'])) {
                                    $image_a_afficher = $p['photo_offre'];
                                } else {
                                    // Priorité 2 : Si pas de photo d'offre, on tente une image nommée comme le produit
                                    // (ex: "Minesao Poulet" devient "minesao_poulet.jpg")
                                    $nom_clean = strtolower(trim($p['nom_produit']));
                                    $nom_clean = str_replace(' ', '_', $nom_clean);
                                    $image_a_afficher = $nom_clean . '.jpg';
                                }
                                ?>
                                <img src="../images/<?= htmlspecialchars($image_a_afficher) ?>" 
                                     class="w-100 h-100 object-fit-cover" 
                                     alt="<?= htmlspecialchars($p['nom_produit']) ?>"
                                     onerror="this.src='../images/default_food.jpg';">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary"><i class="bi bi-tag"></i><?= htmlspecialchars($p['nom_categorie']) ?></span>
                                    <small class="text-muted"><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($p['date_dispo']) ?></small>
                                </div>
                                <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($p['nom_produit']) ?></h5>
                                <p class="card-text text-muted small mb-3"><i class="bi bi-person"></i> Par : <strong><?= htmlspecialchars($p['nom_vendeur']) ?></strong></p>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fs-4 price-app"><i class="bi bi-cash-coin"></i><?= number_format($p['prix_vente'], 0, '.', ' ') ?> Ar</span>
                                        <span class="badge bg-secondary"><i class="bi bi-box-seam"></i>Stock : <?= $p['quantite_dispo'] ?></span>
                                    </div>

                                    <form action="accueil.php" method="POST" class="row g-2 align-items-center">
                                        <input type="hidden" name="id_produit_membre" value="<?= $p['id_produit_membre'] ?>">
                                        <input type="hidden" name="action_achat" value="1">

                                        <div class="col-4">
                                            <input type="number" name="quantite" class="form-control text-center" value="1" min="1" max="<?= $p['quantite_dispo'] ?>" required>
                                        </div>
                                        <div class="col-8">
                                            <button type="submit" class="btn btn-app w-100"><i class="bi bi-cart-check"></i>Acheter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>