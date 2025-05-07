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
            <!-- ensemble des balise du fomulaire -->
            <div class="info">
                <input type="text" class="name" name="nom" placeholder="nom"></input>
                <input type="text" class="name" name="prenom" placeholder="prénom"></input>
                <input type="password" class="name" name="password" placeholder="mot de passe"></input>
                <input type="password" class="name" name="re-password" placeholder="confirmer mot de passe"></input>
                <select name="type" class="name">
                    <option value="">--type d'utilisateur--</option>
                    <option value="A">administateur</option>
                    <option value="S">superviseur</option>
                </select>
            </div>
            <div class="options">
                <input type="submit" class="changement" name="start" value="Inscrire"></input>
                <input type="submit" class="changement" name="retour" value="Retour"></input>
            </div>
            <?php
            session_start();
            
            // fonctionement du bouton pour rediriger vers la page de connexion

            if (isset($_POST['retour'])){
                header("Location: affichage_admin.php");
                exit();
            } 

            // information de la base de donnée

            $host = "localhost";
            $db = "salle_serveur";
            $user = "capteur";
            $pass = "password";

            // création de l'utilisateur dans la base de donnée avec les champs nom, prénom et mot de passe
            // si le mot de passe et la confirmation du mot de passe sont identiques

            if (isset($_POST['start']) && !empty($_POST['nom']) &&  !empty($_POST['prenom']) && !empty($_POST['password']) && !empty($_POST['re-password']) && !empty($_POST['type'])) {
                $nom = trim(htmlspecialchars($_POST['nom']));
                $prenom = trim(htmlspecialchars($_POST['prenom']));
                $password = trim(htmlspecialchars($_POST['password']));
                $re_pass = trim(htmlspecialchars($_POST['re-password']));
                $type = trim(htmlspecialchars($_POST['type']));

                if ($password != $re_pass) {
                    echo "<p>Les mots de passe ne correspondent pas</p>";
                    exit();
                } else if ($type != 'A' && $type != 'S') {
                    echo "<p>Type d'utilisateur incorrect</p>";
                    exit();
                }

                // inserrtion de l'utilisateur dans la base de donnée

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $password_hashed= password_hash($password, PASSWORD_ARGON2I);

                    $stmt = $pdo->prepare("INSERT INTO user (TYPE_USER, NOM_USER, PRENOM_USER, PASSWORD_USER) VALUES (:type ,:nom, :prenom, :pass)");
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