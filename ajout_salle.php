<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?version=<?php echo filemtime('css/style.css');?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <form method="POST" id="connexion">
        <div class="accueil">
            <h2>Création d'une salle</h2>
            <!-- ensemble des balise du fomulaire -->
            <div class="formulaire">
                <input type="text" class="name" name="nom-salle" placeholder="nom"></input>
                <input type="text" class="name" name="temp" placeholder="température"></input>
                <input type="text" class="name" name="humi" placeholder="humidité"></input>
            </div>
            <div class="options">
                <input type="submit" class="changement" name="start" value="Connexion"></input>
            </div>
        </div>
    </form>
</body>
</html>