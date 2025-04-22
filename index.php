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
            <h2>Connexion - Salle des Serveurs</h2>
            <div class="info">
                <input type="text" class="name" name="nom" placeholder="nom"></input>
                <input type="text" class="name" name="prenom" placeholder="prénom"></input>
                <input type="password" class="name" name="password" placeholder="mot de passe"></input>
            </div>
            <div class="options">
                <input type="submit" class="changement" name="start" value="Connexion"></input>
                <input type="submit" class="changement" name="creation" value="Inscription"></input>
            </div>
            <?php
            session_start();

            $host = "localhost";
            $db = "salle_serveur";
            $user = "capteur";
            $pass = "password";

            if (isset($_POST['creation'])){
                header("Location: inscription.php");
                exit();
            }
            if (isset($_POST['start']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['password'])) {
                $nom = trim(htmlspecialchars($_POST['nom']));
                $prenom = trim(htmlspecialchars($_POST['prenom']));
                $password = trim(htmlspecialchars($_POST['password']));

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->prepare("SELECT * FROM user WHERE NOM_USER = :nom && PRENOM_USER = :prenom");
                    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                    $stmt->execute();

                    $row = $stmt->fetch();
                } catch (PDOException $e) {
                    die("Erreur : " . $e->getMessage());
                }
                if (password_verify($password, $row['PASSWORD_USER'])) {
                    $_SESSION['utilisateur'] = $nom . " " . $prenom[0];
                    header("Location: affichage.php");
                exit();
                } else {
                    echo "<p class='erreur'>Nom d'utilisateur ou mot de passe incorrect</p>";
                }
            }
            ?>
        </div>
    </form>
</body>
</html>