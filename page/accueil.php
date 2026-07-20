<?php
session_start();
// Inclusion correcte vers ton dossier fonction
include_once '../fonction/functions.php';

// Sécurité : Si l'étudiant n'est pas connecté (on vérifie ton id_membre en session)
if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

$message_succes = "";
$message_erreur = "";

// 1. TRAITEMENT DE L'ACHAT DIRECT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_achat'])) {
    $id_produit_membre = intval($_POST['id_produit_membre']);
    $quantite_demandee = intval($_POST['quantite']);
    
    if ($quantite_demandee > 0) {
        // Exécution de l'achat
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

// 2. RÉCUPÉRATION DES PRODUITS EN VENTE
$produits = get_produits_en_vente();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITU Market - Accueil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar commune utilisant tes variables de session -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="accueil.php">🎓 ITU Market</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="accueil.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendre.php">Vendre</a></li>
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

        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold">Place du Marché Étu</h2>
                <p class="text-muted">Achetez directement les produits disponibles.</p>
            </div>
        </div>

        <!-- Grille des offres -->
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (empty($produits)): ?>
                <div class="col-12 text-center my-5">
                    <div class="p-5 bg-white rounded shadow-sm border">
                        <p class="text-muted fs-5 mb-0">Rien à vendre pour le moment.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($produits as $p): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary"><?= htmlspecialchars($p['nom_categorie']) ?></span>
                                    <small class="text-muted">Dispo le : <?= htmlspecialchars($p['date_dispo']) ?></small>
                                </div>
                                <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($p['nom_produit']) ?></h5>
                                <p class="card-text text-muted small mb-3">Par : <strong><?= htmlspecialchars($p['nom_vendeur']) ?></strong></p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fs-4 fw-bold text-success"><?= number_format($p['prix_vente'], 0, '.', ' ') ?> Ar</span>
                                        <span class="badge bg-secondary">Stock : <?= $p['quantite_dispo'] ?></span>
                                    </div>
                                    
                                    <form action="accueil.php" method="POST" class="row g-2 align-items-center">
                                        <input type="hidden" name="id_produit_membre" value="<?= $p['id_produit_membre'] ?>">
                                        <input type="hidden" name="action_achat" value="1">
                                        
                                        <div class="col-4">
                                            <input type="number" name="quantite" class="form-control text-center" value="1" min="1" max="<?= $p['quantite_dispo'] ?>" required>
                                        </div>
                                        <div class="col-8">
                                            <button type="submit" class="btn btn-dark w-100 fw-bold">🛒 Acheter</button>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>