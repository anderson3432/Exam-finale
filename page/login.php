<?php
session_start();
include_once("../fonction/functions.php");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSNACKS - Connexion</title>
    <link rel="stylesheet" href="../Theme/Css/style.css">
    <link rel="stylesheet" href="../Theme/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Theme/bootstrap/font/bootstrap-icons.css">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card card-app p-4 mt-5">
                    <h3 class="fw-bold text-center mb-1"><i class="bi bi-cpu section-icon"></i>ITSNACKS</h3>
                    <p class="text-muted text-center small mb-4">
                        <?= isset($_GET['new']) ? "Créer votre compte" : "Connectez-vous à votre compte" ?>
                    </p>

                    <form action="../inc/traitement.php" method="post">
                        <?php
                        if (isset($_GET['new'])) { ?>
                            <div class="mb-3">
                                <label for="etu" class="form-label"><i class="bi bi-person-badge"></i> Numéro ETU</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="text" id="etu" name="etu" class="form-control" placeholder="ETU****" required value="<?= $_GET['etu'] ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nom" class="form-label"><i class="bi bi-person"></i> Nom</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" id="nom" name="nom" class="form-control" placeholder="Votre nom" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image_profil" class="form-label"><i class="bi bi-image"></i> Photo de profil (optionnel)</label>
                                <input type="file" id="image_profil" name="image_profil" class="form-control">
                            </div>

                            <input type="hidden" name="action" value="inscription">
                            <button type="submit" class="btn btn-app w-100"><i class="bi bi-person-plus"></i>Créer mon compte</button>

                        <?php } elseif (!isset($_GET['new'])) { ?>
                            <div class="mb-4">
                                <label for="etu" class="form-label"><i class="bi bi-person-badge"></i> Numéro ETU</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="text" id="etu" name="etu" class="form-control" value="ETU0001" required>
                                </div>
                            </div>

                            <input type="hidden" name="action" value="connexion">
                            <button type="submit" class="btn btn-app w-100"><i class="bi bi-box-arrow-in-right"></i>Se connecter</button>

                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>