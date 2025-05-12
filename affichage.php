<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?version=<?= file_exists('css/style.css') ? filemtime('css/style.css') : time(); ?>">
    <title>Tableau de Bord</title>
    <script src="js/script.js?version=<?= file_exists('js/script.js') ? filemtime('js/script.js') : time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
</head> 
<body>
    <?php
    session_start();

    // information de la base de donnée

    $host = "localhost";
    $db = "projet-iot";
    $user = "capteur";
    $pass = "password";

    if (!isset($_SESSION['utilisateur'])) {
        echo "Accès refusé. Veuillez vous connecter.";
        echo "<a href='index.php'>Retour</a>";
        exit();
    }
    date_default_timezone_set('Europe/Paris');
    $nb_mois = 12;

    $list_mois = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

    // Initialisation des variables pour les graphiques (jour et mois)
    // en fonction de la date actuelle ou de la date choisie par l'utilisateur 
    // ansi que le format du grphique (par jour ou par mois)

    if (isset($_POST['mois']) && !empty($_POST['anne_choix']) && empty($_POST['mois_choix']) && empty($_POST['jour_choix'])) {
        $_SESSION['anne_actuel'] = $_POST['anne_choix'];
        $_SESSION['mois_actuel'] = date('m');
        $_SESSION['jour_actuel'] = date('d');
    } elseif (isset($_POST['jour']) && !empty($_POST['anne_choix']) && !empty($_POST['mois_choix'])) {
        $_SESSION['anne_actuel'] = $_POST['anne_choix'];
        $_SESSION['mois_actuel'] = $_POST['mois_choix'];
        $_SESSION['jour_actuel'] = date('d');

    } elseif (isset($_POST['jour']) && empty($_POST['anne_choix']) && !empty($_POST['mois_choix'])) {
        $_SESSION['anne_actuel'] = date('Y');
        $_SESSION['mois_actuel'] = $_POST['mois_choix'];
        $_SESSION['jour_actuel'] = date('d');
    }else {    
        $_SESSION['anne_actuel'] = date('Y');
        $_SESSION['mois_actuel'] = date('m');
        $_SESSION['jour_actuel'] = date('d'); 
    }

    $jour_actuel = $_SESSION['jour_actuel'];
    $mois_actuel = $_SESSION['mois_actuel'];
    $anne_actuel = $_SESSION['anne_actuel'];

    // definition du nombre de jour en fonction du mois et de l'année

    if ($_SESSION['mois_actuel'] == '1' || $_SESSION['mois_actuel'] == '3' || $_SESSION['mois_actuel'] == '5' || $_SESSION['mois_actuel'] == '7' || $_SESSION['mois_actuel'] == '8' || $_SESSION['mois_actuel'] == '10' || $_SESSION['mois_actuel'] == '12') {
        $nb_jour = 31;
        $list_jour = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'];
    } elseif ($_SESSION['mois_actuel'] == '4' || $_SESSION['mois_actuel'] == '6' || $_SESSION['mois_actuel'] == '9' || $_SESSION['mois_actuel'] == '11') {
        $nb_jour = 30;
        $list_jour = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'];
    } else {
        if ($_SESSION['anne_actuel'] % 4 == 0 && ($_SESSION['anne_actuel'] % 100 != 0 || $_SESSION['anne_actuel'] % 400 == 0)) {
            $nb_jour = 29;
            $list_jour = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29'];
        } else {
            $nb_jour = 28;
            $list_jour = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28'];
        }
    }
    ?>
    <form method="post">
        <!-- en-tête : utilisateur et bouton de déconnexion -->
        <header>
            <div class="menu">
                <input type="submit" class="bouton-menu" name="deconnexion" value="DECONNEXION"></input>
                <?php 
                if (isset($_POST['deconnexion'])) { 
                    session_destroy();
                    header("Location: index.php");
                    exit();
                    } 
                if ($_SESSION['type'] == 'A') {
                    echo "<input type='submit' class='bouton-menu' name='inscription' value='INSCRIPTION'></input>";
                    if (isset($_POST['inscription'])) { 
                        header("Location: inscription.php");
                        exit();
                    } 
                }
                ?>
                <select name="salle" class="bouton-menu" id="salle">
                    <option value="">-choix-</option>
                    <?php
                    // afficher les salles dans le select

                    if ($_SESSION['departement'] == 'G') {
                        try {
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $stmt = $pdo->prepare("SELECT * FROM salle ORDER BY ID_NOM_DEPARTEMENT ASC;");
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

                            $stmt = $pdo->prepare("SELECT * FROM salle WHERE ID_NOM_DEPARTEMENT = :departement  ORDER BY TYPE_SALLE ASC;");
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
                    if ($_SESSION['type'] == 'A') {
                        echo "<option value='ajout'>ajouter salle</option>";
                    }
                    ?>
                </select>
                <input type="submit" class="bouton-menu" name="choix_salle" value="OK"></input>
                <?php
                if (isset($_POST['choix_salle']) && $_POST['salle'] == 'ajout' ) { 
                    header("Location: ajout_salle.php");
                    exit();
                } elseif (isset($_POST['choix_salle']) && $_POST['salle'] != '') {
                    $salle = $_POST['salle'];
                    try {
                        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                        $stmt4 = $pdo->prepare("SELECT * FROM salle WHERE ID_SALLE = $salle;");
                        $stmt4->execute();
    
                        $row4 = $stmt4->fetch();
                    } catch (PDOException $e) {
                        die("Erreur : " . $e->getMessage());
                    }
                    $_SESSION['salle'] = $row4['ID_NOM_DEPARTEMENT'] . "-" . $row4['NOM_SALLE'];
                    $_SESSION['id_salle'] = $row4['ID_SALLE'];
                    header("Location: affichage.php");
                    exit();
                } 
                ?>
            </div>
            <h2>Salle <?php echo $_SESSION['salle'] . " : " . $_SESSION['utilisateur'] . " / " .  $_SESSION['type'];?></h2>
        </header>
    <?php

    // Recuperation des données de la base de donnée pour les graphiques et les jauges
    $id_salle = $_SESSION['id_salle'];
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Température et humidité actuelles pour les jauges
        $reqTemp = $pdo->prepare("
            SELECT MESURE 
            FROM capteur C 
            JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
            WHERE TC.TYPE_CAPTEUR = 'T'
            AND ID_SALLE = $id_salle 
            ORDER BY ID_CAPTEUR DESC 
            LIMIT 1;
        ");
        $reqHumi = $pdo->prepare("
            SELECT MESURE 
            FROM capteur C 
            JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
            WHERE TC.TYPE_CAPTEUR = 'H'
            AND ID_SALLE = $id_salle 
            ORDER BY ID_CAPTEUR DESC 
            LIMIT 1;
        ");
    
        $reqTemp->execute();
        $reqHumi->execute();
    
        $temp = $reqTemp->fetchColumn() ?? 0;
        $humi = $reqHumi->fetchColumn() ?? 0;
    
        // Graphiques

        if (isset($_POST['jour'])) {
            // Par jour
            for ($i = 0; $i < $nb_jour; $i++) {
                $reqJourTemp = $pdo->prepare("
                    SELECT AVG(MESURE) 
                    AS moyenne_mesure
                    FROM capteur C 
                    JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
                    WHERE TC.TYPE_CAPTEUR = 'T'   
                    AND DATE_FORMAT(DATE_HEURE, '%Y%m%d') = :date 
                    AND ID_SALLE = :salle;
                ");
                $reqJourHumi = $pdo->prepare("
                    SELECT AVG(MESURE) 
                    AS moyenne_mesure
                    FROM capteur C 
                    JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
                    WHERE TC.TYPE_CAPTEUR = 'H'   
                    AND DATE_FORMAT(DATE_HEURE, '%Y%m%d') = :date
                    AND ID_SALLE = :salle;
                ");
    
                $params = ['date' => $_SESSION['anne_actuel'] . $_SESSION['mois_actuel'] . $list_jour[$i], 'salle' => $_SESSION['id_salle']];
    
                $reqJourTemp->execute($params);
                $reqJourHumi->execute($params);

                if ($reqJourTemp->rowCount() == 0) {
                } else {
                    $list_graphi_temp[] = $reqJourTemp->fetchColumn();
                }

                if ($reqJourHumi->rowCount() == 0) {
                } else {
                    $list_graphi_humi[] = $reqJourHumi->fetchColumn();
                }
            }
        } else {
            // Moyennes mensuelles
            for ($i = 0; $i < $nb_mois; $i++) {
                $reqMoisTemp = $pdo->prepare("
                    SELECT AVG(MESURE) 
                    AS moyenne_mesure
                    FROM capteur C 
                    JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
                    WHERE TC.TYPE_CAPTEUR = 'T'   
                    AND DATE_FORMAT(DATE_HEURE, '%Y%m') = :date
                    AND ID_SALLE = :salle;
                ");
                $reqMoisHumi = $pdo->prepare("
                    SELECT AVG(MESURE) 
                    AS moyenne_mesure
                    FROM capteur C 
                    JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
                    WHERE TC.TYPE_CAPTEUR = 'H'   
                    AND DATE_FORMAT(DATE_HEURE, '%Y%m') = :date
                    AND ID_SALLE = :salle;
                ");
    
                $params = ['date' => $_SESSION['anne_actuel'] . $list_mois[$i], 'salle' => $_SESSION['id_salle']];
    
                $reqMoisTemp->execute($params);
                $reqMoisHumi->execute($params);
    
                if ($reqMoisTemp->rowCount() == 0) {
                } else {
                    $list_graphi_temp[] = $reqMoisTemp->fetchColumn();
                }
                if ($reqMoisHumi->rowCount() == 0) {
                } else {
                    $list_graphi_humi[] = $reqMoisHumi->fetchColumn();
                }
            }
        }
    
    } catch (PDOException $e) {
        die("Erreur PDO : " . $e->getMessage());
    }    
    ?>
        <main id="affichage">   
            <!-- organisation des jauges taille et emplacement dans le page -->
            <div id="div-valeur">
                <div class="valeur">
                    <canvas id="jauge-temp" width="275" height="275"></canvas>
                </div>
                <div class="valeur">
                    <canvas id="jauge-humi" width="275" height="275"></canvas>
                </div>
            </div>
            <!-- organisation du graphique taille et emplacement dans le page -->
            <div id="div-graphique">
                <canvas id="graphique-temp-humi" width="1000" height="500"></canvas>
                <div id="bouton-graphique">
                    <input type="submit" class="choix-graphique" name="mois" value="par mois"></input>
                    <input type="text" class="choix-valeur" name="anne_choix" placeholder=<?php echo $anne_actuel; ?>></input>
                    <input type="submit" class="choix-graphique" name="jour" value="par jour"></input>
                    <input type="text" class="choix-valeur" name="mois_choix" placeholder=<?php echo $mois_actuel; ?>></input>
                </div>
            </div>    
        </main>
    </form>
    <script>
    // DOM pour le graphique (données et affichage)
    // Utilisation de Chart.js pour créer un graphique linéaire
    document.addEventListener("DOMContentLoaded", function () {
        let list_graphi_temp = <?php echo json_encode($list_graphi_temp); ?>;
        let list_graphi_humi = <?php echo json_encode($list_graphi_humi); ?>;
        let taille_list = list_graphi_temp.length;
        let Labels = [];
        if (taille_list == 31) {
            Labels = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
        } else if (taille_list == 30) {
            Labels = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30];
        } else if (taille_list == 28) {
            Labels = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28];
        } else if (taille_list == 29) {
            Labels = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29];
        } else {
            Labels = ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"];
        }
        console.log(Labels);
        let ctxGraph = document.getElementById("graphique-temp-humi").getContext("2d");

        new Chart(ctxGraph, {
            type: "line",
            data: {
                labels: Labels,
                datasets: [
                    {
                        label: "Température (°C)",
                        data: list_graphi_temp,
                        borderColor: "red",
                        backgroundColor: "rgba(255, 0, 0, 0.2)",
                        fill: true,
                        yAxisID: 'y',
                        tension: 0.1
                    },
                    {
                        label: "Humidité (%)",
                        data: list_graphi_humi,
                        borderColor: "blue",
                        backgroundColor: "rgba(0, 0, 255, 0.2)",
                        fill: true,
                        yAxisID: 'y1',
                        tension: 0.1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Température (°C)',
                            color: '#FFFFFF'
                        }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Humidité (%)',
                            color: '#FFFFFF'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });
    // DOM pour les jauges (données et affichage)
    // Utilisation de Chart.js pour créer des jauges circulaires
    document.addEventListener("DOMContentLoaded", function () {
        let temperature = <?php if ($temp == 0) { echo "null"; } else { echo json_encode($temp);}  ?>;
        let humidity = <?php if ($humi == 0) { echo "null"; } else { echo json_encode($humi);} ?>;
        let maxTemp = 50;
        let maxHumi = 100;

        if (temperature === null || isNaN(temperature)) temperature = 0;
        if (humidity === null || isNaN(humidity)) humidity = 0;

        if (temperature > maxTemp) maxTemp = temperature + 10;
        
        let tempColor = temperature < 15 ? "#28A745" : temperature < 30 ? "#FFC107" : "#DC3545";
        let humiColor = humidity < 30 ? "#ADD8E6" : humidity < 70 ? "#007BFF" : "#00008B";

        let ctxTemp = document.getElementById("jauge-temp").getContext("2d");
        let ctxHumi = document.getElementById("jauge-humi").getContext("2d");

        const valuePlugin_t = {
            id: "valuePlugin",
            beforeDraw(chart) {
                const { ctx, chartArea: { width, height } } = chart;
                ctx.save();
                ctx.font = "bold 20px Courier New";
                ctx.fillStyle = "#FFF"; 
                ctx.textAlign = "center";   
                ctx.textBaseline = "middle";
                const value = chart.data.datasets[0].data[0] + '°C'; 
                ctx.fillText(value, width / 2, height / 1.5); 
                ctx.restore();
            }
        };

        const valuePlugin_h = {
            id: "valuePlugin",
            beforeDraw(chart) {
                const { ctx, chartArea: { width, height } } = chart;
                ctx.save();
                ctx.font = "bold 20px Courier New";
                ctx.fillStyle = "#FFF";
                ctx.textAlign = "center";   
                ctx.textBaseline = "middle";
                const value = chart.data.datasets[0].data[0] + '%';
                ctx.fillText(value, width / 2, height / 1.5);
                ctx.restore();
            }
        };

        new Chart(ctxTemp, {
            type: "doughnut",
            data: {
                labels: ["Température", "Reste"],
                datasets: [{
                    data: [temperature, maxTemp - temperature],
                    backgroundColor: [tempColor, "#000"],
                    borderWidth: 2,
                    borderColor: tempColor
                }]
            },
            options: {
                rotation: -90,
                circumference: 180,
                cutout: "70%",
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            },
            plugins: [valuePlugin_t]
        });

        new Chart(ctxHumi, {
            type: "doughnut",
            data: {
                labels: ["Humidité", "Reste"],
                datasets: [{
                    data: [humidity, maxHumi - humidity],
                    backgroundColor: [humiColor, "#000"],
                    borderWidth: 2,
                    borderColor: humiColor
                }]
            },
            options: {
                rotation: -90,
                circumference: 180,
                cutout: "70%",
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            },
            plugins: [valuePlugin_h]
        });
    });
    </script>
</body>
</html>