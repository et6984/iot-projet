<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?version=<?php echo filemtime('css/style.css');?>">
</head>
<body>
    <form method="POST" id="inscription">
        <div class="accueil">
            <h2>Inscription - Salle des Serveurs</h2>
            <div class="info">
                <input type="text" class="name" name="nom" placeholder="nom"></input>
                <input type="text" class="name" name="prenom" placeholder="prénom"></input>
                <input type="password" class="name" name="password" placeholder="mot de passe"></input>
                <input type="password" class="name" name="re-password" placeholder="confirmer mot de passe"></input>
            </div>
            <div class="options">
                <input type="submit" class="changement" name="start" value="Inscription"></input>
                <input type="submit" class="changement" name="connexion" value="Connexion"></input>
            </div>
            <?php
            session_start();

            if (isset($_POST['connexion'])){
                header("Location: index.php");
                exit();
            }

            $host = "localhost";
            $db = "salle_serveur";
            $user = "capteur";
            $pass = "password";

            if (isset($_POST['start']) && !empty($_POST['nom']) &&  !empty($_POST['prenom']) && !empty($_POST['password']) && !empty($_POST['re-password'])) {
                $nom = trim(htmlspecialchars($_POST['nom']));
                $prenom = trim(htmlspecialchars($_POST['prenom']));
                $password = trim(htmlspecialchars($_POST['password']));
                $re_pass = trim(htmlspecialchars($_POST['re-password']));

                if ($password != $re_pass) {
                    echo "<p>Les mots de passe ne correspondent pas</p>";
                    exit();
                }
                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $password_hashed= password_hash($password, PASSWORD_ARGON2I);

                    $stmt = $pdo->prepare("INSERT INTO user (NOM_USER, PRENOM_USER, PASSWORD_USER) VALUES (:nom, :prenom, :pass)");
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