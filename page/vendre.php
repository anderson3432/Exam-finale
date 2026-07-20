<?php
session_start();
include_once '../fonction/functions.php';

// Sécurité : si pas connecté, redirection immédiate
if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

$message_succes = "";
$message_erreur = "";

// 1. TRAITEMENT DE LA MISE EN VENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_vendre'])) {
    $id_produit = intval($_POST['id_produit']);
    $prix_vente = floatval($_POST['prix_vente']);
    $quantite = intval($_POST['quantite']);
    $date_dispo = $_POST['date_dispo'];
    $id_membre = $_SESSION['id_membre']; // L'étudiant actuellement connecté

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

// 2. RÉCUPÉRATION DU CATALOGUE POUR REMPLIR LE SELECT
$catalogue = get_catalogue_produits();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITU Market - Vendre un produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar commune -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="accueil.php">🎓 ITU Market</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link active" href="vendre.php">Vendre</a></li>
                    <li class="nav-item"><a class="nav-link" href="mes_ventes.php">Mes Ventes</a></li>
                </ul>
                <div class="navbar-text text-white me-3">
                    Connecté : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong>
                </div>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Messages d'alerte -->
        <?php if (!empty($message_succes)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✨ <?= $message_succes ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($message_erreur)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ⚠️ <?= $message_erreur ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0 p-4 mt-2">
                    <h3 class="fw-bold text-dark mb-1">Proposer un produit</h3>
                    <p class="text-muted small mb-4">Remplissez ce formulaire pour publier votre offre sur la plateforme.</p>
                    
                    <form action="vendre.php" method="POST">
                        <input type="hidden" name="action_vendre" value="1">
                        
                        <!-- Sélection du produit -->
                        <div class="mb-3">
                            <label for="id_produit" class="form-label fw-semibold">Choisir un produit dans le catalogue</label>
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
                            <!-- Prix de vente -->
                            <div class="col-md-6 mb-3">
                                <label for="prix_vente" class="form-label fw-semibold">Votre prix de vente (Ar)</label>
                                <input type="number" name="prix_vente" id="prix_vente" class="form-control" placeholder="Ex: 5500" min="1" step="100" required>
                            </div>
                            
                            <!-- Quantité disponible -->
                            <div class="col-md-6 mb-3">
                                <label for="quantite" class="form-label fw-semibold">Quantité à vendre</label>
                                <input type="number" name="quantite" id="quantite" class="form-control" placeholder="Ex: 5" min="1" required>
                            </div>
                        </div>

                        <!-- Date de disponibilité -->
                        <div class="mb-4">
                            <label for="date_dispo" class="form-label fw-semibold">Date de disponibilité</label>
                            <input type="date" name="date_dispo" id="date_dispo" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Bouton de validation -->
                        <button type="submit" class="btn btn-dark w-100 fw-bold py-2.5">
                            🚀 Publier mon offre
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>