<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?version=<?= file_exists('css/style.css') ? filemtime('css/style.css') : time(); ?>">
    <title>Salle des Serveurs</title>
    <script src="js/script.js?version=<?= file_exists('js/script.js') ? filemtime('js/script.js') : time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
</head> 
<body>
    <?php
    date_default_timezone_set('Europe/Paris');
    $nb_mois = 12;

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

    if ($_SESSION['mois_actuel'] == '1' || $_SESSION['mois_actuel'] == '3' || $_SESSION['mois_actuel'] == '5' || $_SESSION['mois_actuel'] == '7' || $_SESSION['mois_actuel'] == '8' || $_SESSION['mois_actuel'] == '10' || $_SESSION['mois_actuel'] == '12') {
        $nb_jour = 31;
    } elseif ($_SESSION['mois_actuel'] == '4' || $_SESSION['mois_actuel'] == '6' || $_SESSION['mois_actuel'] == '9' || $_SESSION['mois_actuel'] == '11') {
        $nb_jour = 30;
    } else {
        if ($_SESSION['anne_actuel'] % 4 == 0 && ($_SESSION['anne_actuel'] % 100 != 0 || $_SESSION['anne_actuel'] % 400 == 0)) {
            $nb_jour = 29;
        } else {
            $nb_jour = 28;
        }
    }

    $host = "localhost";
    $db = "salle_serveur";
    $user = "capteur";
    $pass = "password";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Température et humidité actuelles
        $reqTemp = $pdo->prepare("
            SELECT MESURE 
            FROM capteur C 
            JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
            WHERE TC.TYPE_CAPTEUR = 'T' 
            ORDER BY ID_CAPTEUR DESC 
            LIMIT 1;
        ");
        $reqHumi = $pdo->prepare("
            SELECT MESURE 
            FROM capteur C 
            JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
            WHERE TC.TYPE_CAPTEUR = 'H' 
            ORDER BY ID_CAPTEUR DESC 
            LIMIT 1;
        ");
    
        $reqTemp->execute();
        $reqHumi->execute();
    
        $temp = $reqTemp->fetchColumn() ?? 0;
        $humi = $reqHumi->fetchColumn() ?? 0;
    
        if (isset($_POST['jour'])) {
            // Par jour
            for ($i = 1; $i <= $nb_jour; $i++) {
                $reqJourTemp = $pdo->prepare("
                    SELECT AVG(MESURE)
                    AS moyenne_mesure
                    FROM capteur C 
                    JOIN type_capteur TC 
                    ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
                    WHERE TC.TYPE_CAPTEUR = 'T' 
                    AND jour = :jour
                    AND mois = :mois
                    AND anne = :anne;
                ");
                $reqJourHumi = $pdo->prepare("
                    SELECT AVG(MESURE)
                    AS moyenne_mesure
                    FROM capteur C 
                    JOIN type_capteur TC 
                    ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
                    WHERE TC.TYPE_CAPTEUR = 'H' 
                    AND jour = :jour
                    AND mois = :mois
                    AND anne = :anne;
                ");
    
                $params = ['jour' => $i, 'mois' => $_SESSION['mois_actuel'], 'anne' => $_SESSION['anne_actuel']];
    
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
            for ($i = 1; $i <= $nb_mois; $i++) {
                $reqMoisTemp = $pdo->prepare("
                    SELECT AVG(MESURE) 
                    AS moyenne_mesure
                    FROM capteur C 
                    JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
                    WHERE TC.TYPE_CAPTEUR = 'T' 
                    AND mois = :mois 
                    AND anne = :anne;
                ");
                $reqMoisHumi = $pdo->prepare("
                    SELECT AVG(MESURE) 
                    AS moyenne_mesure
                    FROM capteur C 
                    JOIN type_capteur TC ON C.TYPE_CAPTEUR = TC.TYPE_CAPTEUR 
                    WHERE TC.TYPE_CAPTEUR = 'H' 
                    AND mois = :mois 
                    AND anne = :anne;
                ");
    
                $params = ['mois' => $i, 'anne' => $_SESSION['anne_actuel']];
    
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
    <form method="post">
        <header>
            <input type="submit" id="bouton" name="deconnexion" value="DECONNEXION"></input>
            <?php if (isset($_POST['deconnexion'])){ header("Location: index.php");exit();} ?>
            <h1>Salle des Serveurs : <?php session_start(); echo $_SESSION['utilisateur'];?></h1>
        </header>
        <main id="affichage">
            <div id="div-valeur">
                <div class="valeur">
                    <canvas id="jauge-temp" width="275" height="275"></canvas>
                </div>
                <div class="valeur">
                    <canvas id="jauge-humi" width="275" height="275"></canvas>
                </div>
            </div>
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
    document.addEventListener("DOMContentLoaded", function () {
        let temperature = <?php echo json_encode($temp); ?>;
        let humidity = <?php echo json_encode($humi); ?>;
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