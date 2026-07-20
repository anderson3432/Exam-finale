<?php
session_start();
include_once '../fonction/functions.php';

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

$message_succes = "";
$message_erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_vendre'])) {
    $id_produit = intval($_POST['id_produit']);
    $prix_vente = floatval($_POST['prix_vente']);
    $quantite = intval($_POST['quantite']);
    $date_dispo = $_POST['date_dispo'];
    $id_membre = $_SESSION['id_membre'];

    if ($id_produit > 0 && $prix_vente > 0 && $quantite > 0 && !empty($date_dispo)) {
        $insertion_reussie = mettre_en_vente($id_produit, $id_membre, $prix_vente, $quantite, $date_dispo);

        if ($insertion_reussie) {
            $message_succes = "Votre produit a bien été mis en vente ! Il est maintenant visible sur l'accueil.";
        } else {
            $message_erreur = "Erreur lors de la mise en vente du produit.";
        }
    } else {
        $message_erreur = "Veuillez remplir correctement tous les champs obligatoires.";
    }
}

$catalogue = get_catalogue_produits();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSNACKS - Vendre un produit</title>
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
                    <li class="nav-item"><a class="nav-link active" href="vendre.php"><i class="bi bi-shop"></i>Vendre</a></li>
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
                <i class="bi bi-check-circle-fill"></i><?= $message_succes ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($message_erreur)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i><?= $message_erreur ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-app p-4 mt-2">
                    <h3 class="fw-bold mb-1"><i class="bi bi-plus-circle section-icon"></i>Proposer un produit</h3>
                    <p class="text-muted small mb-4">Remplissez ce formulaire pour publier votre offre sur la plateforme.</p>

                    <form action="vendre.php" method="POST">
                        <input type="hidden" name="action_vendre" value="1">

                        <div class="mb-3">
                            <label for="id_produit" class="form-label"><i class="bi bi-list-ul"></i> Choisir un produit dans le catalogue</label>
                            <select name="id_produit" id="id_produit" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez votre produit --</option>
                                <?php foreach ($catalogue as $item): ?>
                                    <option value="<?= $item['id_produit'] ?>">
                                        <?= htmlspecialchars($item['nom']) ?> (Réf: <?= number_format($item['prix_reference'], 0, '.', ' ') ?> Ar)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prix_vente" class="form-label"><i class="bi bi-cash-coin"></i> Votre prix de vente (Ar)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-currency-exchange"></i></span>
                                    <input type="number" name="prix_vente" id="prix_vente" class="form-control" placeholder="Ex: 5500" min="1" step="100" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantite" class="form-label"><i class="bi bi-box-seam"></i> Quantité à vendre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="number" name="quantite" id="quantite" class="form-control" placeholder="Ex: 5" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="date_dispo" class="form-label"><i class="bi bi-calendar3"></i> Date de disponibilité</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="date_dispo" id="date_dispo" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-app w-100">
                            <i class="bi bi-send-check"></i>Publier mon offre
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>