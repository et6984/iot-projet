<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selection de la salle</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?version=<?php echo filemtime('css/style.css');?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php
    session_start();
    
    if (!isset($_SESSION['utilisateur'])) {
        echo "Accès refusé. Veuillez vous connecter.";
        echo "<a href='index.php'>Retour</a>";
        exit();
    }
    ?>
    <form method="POST" id="connexion">
        <div id="choix-salle">
            <h2>Selection de la Salle</h2>
            <?php
            // information de la base de donnée

            $host = "localhost";
            $db = "projet-iot";
            $user = "capteur";
            $pass = "password";
            ?>
            <div id="boite-selection">
                <p class="selection">utilisateur : <?php echo $_SESSION['utilisateur'] . "/" . $_SESSION['type'] . "/" . $_SESSION['departement']; ?></p>
                <select name="salle" class="selection">
                    <option value="">-salle-</option>
                    <?php
                    // afficher les salles dans le select

                    if ($_SESSION['departement'] == 'G') {
                        try {
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $stmt = $pdo->prepare("SELECT * FROM salle;");
                            $stmt->execute();

                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($row as $row1) {
                                // afficher les salles dans le select
                                echo "<option value=" . $row1['ID_SALLE'] . ">" . $row1['TYPE_SALLE'] . "-" . $row1['NOM_SALLE'] . "-" . $row1['ID_NOM_DEPARTEMENT'] . "</option>";
                            }
                        } catch (PDOException $e) {
                            die("Erreur : " . $e->getMessage());
                        }
                    } else {
                        // on recupere le nom de la salle
                        $departement = $_SESSION['departement'];
                        try {
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $stmt = $pdo->prepare("SELECT * FROM salle WHERE ID_NOM_DEPARTEMENT = :departement;");
                            $stmt->bindParam(':departement', $departement, PDO::PARAM_STR);
                            $stmt->execute();

                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($row as $row1) {
                                // afficher les salles dans le select
                                echo "<option value=" . $row1['ID_SALLE'] . ">" . $row1['TYPE_SALLE'] . "-" . $row1['NOM_SALLE'] . "</option>";
                            }
                        } catch (PDOException $e) {
                            echo "Erreur : " . $e->getMessage();
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="options">
                <input type="submit" class="selection" name="start" value="Connexion"></input>
            </div>
            <?php
            if (isset($_POST['start']) && !empty($_POST['salle'])) {
                // on verifie si la salle est selectioner
                $_SESSION['id_salle'] = $_POST['salle'];
                $salle = $_SESSION['id_salle'];
                // on recupere le nom de la salle
                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt2 = $pdo->prepare("SELECT * FROM salle WHERE ID_SALLE = $salle;");
                    $stmt2->execute();

                    $row2 = $stmt2->fetch();
                } catch (PDOException $e) {
                    die("Erreur : " . $e->getMessage());
                }
                $_SESSION['salle'] = $row2['ID_NOM_DEPARTEMENT'] . "-" . $row2['NOM_SALLE'];
                header("Location: affichage.php");
                exit();
            } else if (isset($_POST['start']) && empty($_POST['salle'])) {
                echo "<p class='erreur'>Selectioner une salle</p>";
            }
            ?>
        </div>
    </form>
</body>
</html>