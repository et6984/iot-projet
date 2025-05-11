<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?version=<?php echo filemtime('css/style.css');?>">
</head>
<body>
    <?php
    session_start();

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
    <form method="POST" id="inscription">
        <div class="accueil">
            <h2>Inscription - Salle des Serveurs</h2>
            <!-- ensemble des balise du fomulaire -->
            <div class="info">
                <input type="text" class="name" name="nom" placeholder="nom"></input>
                <input type="text" class="name" name="prenom" placeholder="prénom"></input>
                <input type="password" class="name" name="password" placeholder="mot de passe"></input>
                <input type="password" class="name" name="re-password" placeholder="confirmer mot de passe"></input>
                <select name="type" class="name">
                    <option value="">-type d'utilisateur-</option>
                    <?php
                    // information de la base de donnée

                    $host = "localhost";
                    $db = "projet-iot";
                    $user = "capteur";
                    $pass = "password";

                    try {
                        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                        $stmt1 = $pdo->prepare("SELECT * FROM type_user;");
                        $stmt1->execute();
    
                        $row = $stmt1->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($row as $row1) {
                            // afficher les salles dans le select
                            echo "<option value=" . $row1['TYPE_USER'] . ">" . $row1['LIBELLE_TYPE_USER'] . "</option>";
                        }
                    } catch (PDOException $e) {
                        die("Erreur : " . $e->getMessage());
                    }
                    ?>
                </select>
                <select name="departement" class="name">
                    <option value="">-departement-</option>
                    <?php
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
            </div>
            <div class="options">
                <input type="submit" class="changement" name="start" value="Inscrire"></input>
                <input type="submit" class="changement" name="retour" value="Retour"></input>
            </div>
            <?php

            // fonctionement du bouton pour rediriger vers la page de connexion

            if (isset($_POST['retour'])){
                header("Location: affichage.php");
                exit();
            } 

            // création de l'utilisateur dans la base de donnée avec les champs nom, prénom et mot de passe
            // si le mot de passe et la confirmation du mot de passe sont identiques

            if (isset($_POST['start']) && !empty($_POST['nom']) &&  !empty($_POST['prenom']) && !empty($_POST['password']) && !empty($_POST['re-password']) && !empty($_POST['type']) & !empty($_POST['departement'])) {
                $nom = trim(htmlspecialchars($_POST['nom']));
                $prenom = trim(htmlspecialchars($_POST['prenom']));
                $password = trim(htmlspecialchars($_POST['password']));
                $re_pass = trim(htmlspecialchars($_POST['re-password']));
                $type = trim(htmlspecialchars($_POST['type']));
                $departement = trim(htmlspecialchars($_POST['departement']));

                if ($password != $re_pass) {
                    echo "<p>Les mots de passe ne correspondent pas</p>";
                    exit();
                } elseif ($type != 'A' && $type != 'S') {
                    echo "<p>Type d'utilisateur incorrect</p>";
                    exit();
                }

                $login = $prenom[0]. "." . $nom;

                // inserrtion de l'utilisateur dans la base de donnée

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $password_hashed= password_hash($password, PASSWORD_ARGON2I);

                    $stmt = $pdo->prepare("
                    INSERT INTO `user`(`TYPE_USER`, `ID_NOM_DEPARTEMENT`, `LOGIN_USER`, `NOM_USER`, `PRENOM_USER`, `PASSWORD_USER`) 
                    VALUES (:type, :departement ,:login , :nom, :prenom, :pass);
                    ");
                    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
                    $stmt->bindParam(':departement', $departement, PDO::PARAM_STR);
                    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
                    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $password_hashed, PDO::PARAM_STR);
                    $stmt->execute();

                    echo "<p>Inscription réussie</p>";
                } catch (PDOException $e) {
                    die("Erreur : " . $e->getMessage());
                }
            }
            ?>
        </div>
    </form>
</body>
</html>