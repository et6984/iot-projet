<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?version=<?php echo filemtime('css/style.css'); ?>">
    <title>Salle des Serveurs</title>
    <script src="js/script.js?version=<?php echo filemtime('js/script.js'); ?>"></script>
</head>
<body>
    <form method="post">
        <header>
            <input type="submit" id="bouton" name="deconnexion" value="DECONNEXION"></input>
            <?php if (isset($_POST['deconnexion'])){ header("Location: index.php");exit();} ?>
            <h1>Salle des Serveurs : <?php session_start(); echo $_SESSION['utilisateur'];?></h1>
        </header>
        <main id="affichage">
            <div id="div-valeur">
                <div class="valeur">
                    
                </div>
                <div class="valeur">
                    
                </div>
            </div>
            <div id="div-graphique">
                <canvas id="graphique-temp-humi"></canvas>
            </div>    
        </main>
    </form>
</body>
</html>