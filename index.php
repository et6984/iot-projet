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
                <input type="text" class="name" name="login" placeholder="identifiant"></input>
                <input type="password" class="name" name="password" placeholder="mot de passe"></input>
                <select name="departement" class="name">
                    <option value="">-departement-</option>
                    <?php
                    session_start();

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
            </div>
            <div class="options">
                <input type="submit" class="changement" name="start" value="Connexion"></input>
            </div>
            <?php
            // vérification de l'utilisateur dans la base de donnée avec les champs nom, prénom et mot de passe

            if (isset($_POST['start']) && !empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['departement'])) {
                $login = trim(htmlspecialchars($_POST['login']));
                $password = trim(htmlspecialchars($_POST['password']));

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->prepare("SELECT * FROM user WHERE LOGIN_USER = :login && ID_NOM_DEPARTEMENT = :departement;");
                    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
                    $stmt->bindParam(':departement', $_POST['departement'], PDO::PARAM_STR);
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
                    if (password_verify($password, $row['PASSWORD_USER'])) {
                        $_SESSION['utilisateur'] = $login;
                        $_SESSION['type'] = $row['TYPE_USER'];
                        $_SESSION['departement'] = $row['ID_NOM_DEPARTEMENT'];
                        header("Location: choix_salle.php");
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