<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Création Salle</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?version=<?php echo filemtime('css/style.css');?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php
    session_start();

    // Vérification de la session utilisateur
    if (!isset($_SESSION['utilisateur'])) {
        echo "Accès refusé. Veuillez vous connecter.";
        echo "<a href='index.php'>Retour</a>";
        exit();
    } elseif ($_SESSION['type'] != 'A') {
        echo "Accès refusé. Vous n'êtes pas administrateur.";
        echo "<a href='index.php'>Retour</a>";
        exit();
    } 
    ?>
    <form method="POST" id="connexion">
        <div class="accueil">
            <h2>Création d'une salle</h2>
            <!-- ensemble des balise du fomulaire -->
            <div class="formulaire">
                <input type="text" class="name" name="nom-salle" placeholder="nom"></input>
                <select name="departement" class="name">
                    <option value="">-departement-</option>
                    <?php
                    // information de la base de donnée
        
                    $host = "localhost";
                    $db = "projet-iot";
                    $user = "capteur";
                    $pass = "password";

                    try {
                        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                        $stmt = $pdo->prepare("SELECT * FROM departement;");
                        $stmt->execute();
    
                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($row as $row1) {
                            // afficher les departements dans le select
                            echo "<option value=" . $row1['ID_NOM_DEPARTEMENT'] . ">" . $row1['NOM_DEPARTEMENT'] . "</option>";
                        }
                    } catch (PDOException $e) {
                        die("Erreur : " . $e->getMessage());
                    }
                    ?>
                </select>
                <select name="type-salle" class="name">
                    <option value="">-type salle-</option>
                    <?php
                    // ajouter les types de salle dans le select d'apres la base de donnée
                    try {
                        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                        $stmt1 = $pdo->prepare("SELECT * FROM type_salle;");
                        $stmt1->execute();
    
                        $row1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($row1 as $row2) {
                            // afficher les departements dans le select
                            echo "<option value=" . $row2['TYPE_SALLE'] . ">" . $row2['LIBELLE_TYPE_SALLE'] . "</option>";
                        }
                    } catch (PDOException $e) {
                        die("Erreur : " . $e->getMessage());
                    }
                    ?>
                </select>
            </div>
            <div class="options">
                <input type="submit" class="changement" name="ajout" value="Ajouter" style="width: auto;padding: 0% 1%;"></input>
                <input type="submit" class="changement" name="tableau" value="Tableau de bord" style="width: auto;padding: 0% 1%;"></input>
            </div>
            <?php
            if (isset($_POST['ajout']) && !empty($_POST['nom-salle']) && !empty($_POST['departement'])) {
                // Vérification de l'existence de la salle dans la base de données
                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt3 = $pdo->prepare("INSERT INTO `salle`(`NOM_SALLE`, `TYPE_SALLE`, `ID_NOM_DEPARTEMENT`) VALUES (:nom_salle, :type_salle, :departement);");
                    $stmt3->bindParam(':nom_salle', $_POST['nom-salle'], PDO::PARAM_STR);
                    $stmt3->bindParam(':departement', $_POST['departement'], PDO::PARAM_STR);
                    $stmt3->bindParam(':type_salle', $_POST['type-salle'], PDO::PARAM_STR);
                    $stmt3->execute();
                } catch (PDOException $e) {
                    die("Erreur : " . $e->getMessage());
                }
                // Redirection vers la page d'affichage
                header("Location: ajout_salle.php");
            }

            if (isset($_POST['tableau']) && $_SESSION['type'] == 'A'){
                // Redirection vers la page d'affichage
                header("Location: affichage.php");
                exit();
            }
            ?>
        </div>
    </form>
</body>
</html>