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
            <!-- ensemble des balise du fomulaire -->
            <div class="info">
                <input type="text" class="name" name="nom" placeholder="nom"></input>
                <input type="text" class="name" name="prenom" placeholder="prénom"></input>
                <input type="password" class="name" name="password" placeholder="mot de passe"></input>
                <select name="salle" class="name">
                    <option value="">-salle-</option>
                    <option value="serveur">serveur</option>
                    <option value="ajout">ajouter salle</option>
                </select>
            </div>
            <div class="options">
                <input type="submit" class="changement" name="start" value="Connexion"></input>
            </div>
            <?php
            session_start();

            // information de la base de donnée

            $host = "localhost";
            $db = "salle_serveur";
            $user = "capteur";
            $pass = "password";

            // vérification de l'utilisateur dans la base de donnée avec les champs nom, prénom et mot de passe

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

                // vérification du mot de passe avec la fonction password_verify
                // si le mot de passe est correct, on redirige vers la page affichage.php
                if (!$row) {
                    echo "<p class='erreur'>Utilisateur non reconnu</p>";
                } else {
                    if ($_POST == "") {
                        echo "<p class='erreur'>Il faut choisir une salle</p>";
                    } elseif (password_verify($password, $row['PASSWORD_USER']) && $row['TYPE_USER'] == 'A' && $_POST['salle'] == "ajout") {
                        $_SESSION['salle'] = $_POST['salle'];
                        $_SESSION['utilisateur'] = $nom . " " . $prenom[0];
                        $_SESSION['type'] = $row['TYPE_USER'];
                        header("Location: ajout_salle.php");
                        exit();
                    } elseif (password_verify($password, $row['PASSWORD_USER']) && $row['TYPE_USER'] == 'S' && $_POST['salle'] = "ajout") {
                        echo "<p class='erreur'>Accés non autoriser !</p>";
                    } elseif (password_verify($password, $row['PASSWORD_USER']) && $row['TYPE_USER'] == 'S') {
                        $_SESSION['salle'] = $_POST['salle'];
                        $_SESSION['utilisateur'] = $nom . " " . $prenom[0];
                        $_SESSION['type'] = $row['TYPE_USER'];
                        header("Location: affichage_user.php");
                        exit();
                    } elseif (password_verify($password, $row['PASSWORD_USER']) && $row['TYPE_USER'] == 'A') {
                        $_SESSION['salle'] = $_POST['salle'];
                        $_SESSION['utilisateur'] = $nom . " " . $prenom[0];
                        $_SESSION['type'] = $row['TYPE_USER'];
                        header("Location: affichage_admin.php");
                        exit();
                    } else {
                        echo "<p class='erreur'>Nom d'utilisateur ou mot de passe incorrect</p>";
                    }
                }
            }
            ?>
        </div>
    </form>
</body>
</html>