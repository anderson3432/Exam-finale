<?php
session_start();
include_once("../fonction/functions.php");

$id_membre = $_SESSION['id_membre'];

$ventes = get_ventes_by_membre($id_membre);

$total = 0;
foreach ($ventes as $vente) {
    $total += $vente['sous_total'];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSNACKS - Mes ventes</title>
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
                    <li class="nav-item"><a class="nav-link active" href="ventes.php"><i class="bi bi-graph-up-arrow"></i>Mes Ventes</a></li>
                </ul>
                <div class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> Connecté : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong>
                </div>
                <a href="login.php" class="btn btn-outline-danger btn-logout btn-sm"><i class="bi bi-box-arrow-right"></i>Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold"><i class="bi bi-graph-up-arrow section-icon"></i>Mes ventes</h2>
                <p class="text-muted mb-0">Historique de vos produits vendus.</p>
            </div>
        </div>

        <div class="card card-app shadow-sm mb-4">
            <div class="card-body">
                <p class="fw-bold mb-0">
                    <i class="bi bi-wallet2"></i> Total des ventes : <span class="price-app"><?php echo htmlspecialchars($total); ?></span>
                </p>
            </div>
        </div>

        <div class="card card-app shadow-sm">
            <div class="card-body p-0">
                <table class="table table-app table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-calendar3"></i>Date</th>
                            <th><i class="bi bi-clock"></i>Heure</th>
                            <th><i class="bi bi-tag"></i>Produit</th>
                            <th><i class="bi bi-hash"></i>Quantité</th>
                            <th><i class="bi bi-cash-coin"></i>Prix unitaire</th>
                            <th><i class="bi bi-wallet2"></i>Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventes as $vente) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vente['date']); ?></td>
                                <td><?php echo htmlspecialchars($vente['heure']); ?></td>
                                <td><?php echo htmlspecialchars($vente['nom_produit']); ?></td>
                                <td><?php echo htmlspecialchars($vente['quantite']); ?></td>
                                <td><?php echo htmlspecialchars($vente['prix_vente']); ?></td>
                                <td><?php echo htmlspecialchars($vente['sous_total']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>