<?php
session_start();
include_once("../fonction/functions.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="../inc/traitement.php" method="post">
        <div class="login">
            <?php
            if (isset($_GET['new'])) { ?>
                <label for="etu">Numéro ETU</label>
                <input type="text" id="etu" name="etu" placeholder="ETU****" required value="<?= $_GET['etu'] ?>">

                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                
                <label for="image_profil">Photo de profil (optionnel)</label>
                <input type="file" id="image_profil" name="image_profil">
                <input type="hidden" name="action" value="inscription">
                <button type="submit">Créer mon compte</button>

            <?php } elseif (!isset($_GET['new'])) { ?>
                <label for="etu">Numéro ETU</label>
                <input type="text" id="etu" name="etu" placeholder="ETU****" required>
                <input type="hidden" name="action" value="connexion">
                <button type="submit">Se connecter</button>

            <?php } ?>
        </div>
    </form>
</body>

</html>