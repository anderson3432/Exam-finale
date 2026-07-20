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

    $nom_photo = null;

    if (isset($_FILES['photo_plat']) && $_FILES['photo_plat']['error'] === 0) {
        $dossier_destination = '../images/';

        if (!is_dir($dossier_destination)) {
            mkdir($dossier_destination, 0777, true);
        }

        $extension = pathinfo($_FILES['photo_plat']['name'], PATHINFO_EXTENSION);
        $nom_photo = 'offre_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        $chemin_complet = $dossier_destination . $nom_photo;

        if (!move_uploaded_file($_FILES['photo_plat']['tmp_name'], $chemin_complet)) {
            $nom_photo = null;
        }
    }

    if ($id_produit > 0 && $prix_vente > 0 && $quantite > 0 && !empty($date_dispo)) {
        $insertion_reussie = mettre_en_vente($id_produit, $id_membre, $prix_vente, $quantite, $date_dispo, $nom_photo);

        if ($insertion_reussie) {
            $message_succes = "Votre plat a bien été mis en vente avec sa photo !";
        } else {
            $message_erreur = "Erreur lors de la mise en vente.";
        }
    } else {
        $message_erreur = "Veuillez remplir correctement tous les champs requis.";
    }
}

$sql_catalogue = "SELECT * FROM produit ORDER BY nom ASC";
$catalogue = get_all_lines($sql_catalogue);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSNACKS - Vendre</title>
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
        <?php if (!empty($message_succes)): ?>
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill"></i> <?= $message_succes ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if (!empty($message_erreur)): ?>
            <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle-fill"></i> <?= $message_erreur ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-app shadow-sm p-4">
                    <h3 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> Mettre un plat en vente</h3>

                    <form action="vendre.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action_vendre" value="1">

                        <div class="mb-3">
                            <label for="id_produit" class="form-label fw-bold">Choisir le produit</label>
                            <select name="id_produit" id="id_produit" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionner --</option>
                                <?php foreach ($catalogue as $item): ?>
                                    <option value="<?= $item['id_produit'] ?>"><?= htmlspecialchars($item['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="prix_vente" class="form-label fw-bold">Prix (Ar)</label>
                                <input type="number" name="prix_vente" id="prix_vente" class="form-control" min="1" required value="3000">
                            </div>
                            <div class="col-6">
                                <label for="quantite" class="form-label fw-bold">Quantité</label>
                                <input type="number" name="quantite" id="quantite" class="form-control" min="1" required value="2">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="date_dispo" class="form-label fw-bold">Date de disponibilité</label>
                            <input type="date" name="date_dispo" id="date_dispo" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="photo_plat" class="form-label fw-bold">Photo du plat <span class="text-muted small fw-normal">(Optionnel)</span></label>
                            <input type="file" name="photo_plat" id="photo_plat" class="form-control" accept="image/*">
                            <small class="text-muted">La photo générique du catalogue sera utilisée si vous laissez ce champ vide.</small>
                        </div>

                        <button type="submit" class="btn btn-app w-100"><i class="bi bi-cloud-arrow-up"></i> Publier l'offre</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>