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
    $mois = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    $jour = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
    $heure = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24'];

    if (isset($_POST['mois']) && !empty($_POST['annee_choix']) && empty($_POST['mois_choix']) && empty($_POST['jour_choix'])) {
        $anne_actuel = $_POST['anne_choix'];
        $mois_actuel = date('m');
        $jour_actuel = date('d');
        echo gettype($anne_actuel);
    } elseif (isset($_POST['jour']) && !empty($_POST['annee_choix']) && !empty($_POST['mois_choix']) && empty($_POST['jour_choix'])) {
        $anne_actuel = $_POST['anne_choix'];
        $mois_actuel = $_POST['mois_choix'];
        $jour_actuel = date('d');
    } elseif (isset($_POST['heure']) && !empty($_POST['annee_choix']) && !empty($_POST['mois_choix']) && !empty($_POST['jour_choix'])) {
        $anne_actuel = $_POST['anne_choix'];
        $mois_actuel = $_POST['mois_choix'];
        $jour_actuel = $_POST['jour_choix'];
    } else {    
        $anne_actuel = date('y');
        $mois_actuel = date('m');
        $jour_actuel = date('d'); 
    }

    $host = "localhost";
    $db = "salle_serveur";
    $user = "capteur";
    $pass = "password";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $temperature = $pdo->prepare("SELECT MESURE FROM capteur C JOIN type_capteur TC ON C.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='T' ORDER BY CAPTEUR_DATE_HEURE DESC LIMIT 1;");
        $humidite = $pdo->prepare("SELECT MESURE FROM capteur C JOIN type_capteur TC ON C.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='H' ORDER BY CAPTEUR_DATE_HEURE DESC LIMIT 1;");

        $temperature->execute();
        $humidite->execute();

        $temp = $temperature->fetchColumn();
        $humi = $humidite->fetchColumn();

        if (isset($_POST['heure'])) {
            for ($i = 0; $i < count($heure); $i++) {
                $graphique_temp = $pdo->prepare("SELECT donne FROM historique_donne H JOIN type_capteur TC ON H.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='T' && heure=$heure[$i] && jour=$jour_actuel && mois=$mois_actuel && annee=$anne_actuel;");
                $graphique_humi = $pdo->prepare("SELECT donne FROM historique_donne H JOIN type_capteur TC ON H.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='H' && heure=$heure[$i] && jour=$jour_actuel && mois=$mois_actuel && annee=$anne_actuel;");
                $graphique_temp->execute();
                $graphique_humi->execute();
    
                $graphi_temp = $graphique_temp->fetch();
                $graphi_humi = $graphique_humi->fetch();
    
                $list_graphi_temp[] = $graphi_temp[0];
                $list_graphi_humi[] = $graphi_humi[0];
            }
        } elseif (isset($_POST['jour'])) {
            for ($i = 0; $i < count($jour); $i++) {
                $graphique_temp = $pdo->prepare("SELECT donne FROM historique_donne H JOIN type_capteur TC ON H.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='T' && jour=$jour[i] && mois=$mois_actuel && annee=$anne_actuel;");
                $graphique_humi = $pdo->prepare("SELECT donne FROM historique_donne H JOIN type_capteur TC ON H.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='H' && jour=$jour[i] && mois=$mois_actuel && annee=$anne_actuel;");
                $graphique_temp->execute();
                $graphique_humi->execute();
    
                $graphi_temp = $graphique_temp->fetch();
                $graphi_humi = $graphique_humi->fetch();
    
                $list_graphi_temp[] = $graphi_temp[0];
                $list_graphi_humi[] = $graphi_humi[0];
            }
        } else {
            for ($i = 0; $i < count($mois); $i++) {
                $graphique_temp = $pdo->prepare("SELECT donne FROM historique_donne H JOIN type_capteur TC ON H.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='T' && mois=$mois[$i] && annee=$anne_actuel;");
                $graphique_humi = $pdo->prepare("SELECT donne FROM historique_donne H JOIN type_capteur TC ON H.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='H' && mois=$mois[$i] && annee=$anne_actuel;");
                $graphique_temp->execute();
                $graphique_humi->execute();

                $graphi_temp = $graphique_temp->fetch();
                $graphi_humi = $graphique_humi->fetch();

                $list_graphi_temp[] = $graphi_temp[0];
                $list_graphi_humi[] = $graphi_humi[0];
            }
        } 
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
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
                    <input type="text" class="choix-valeur" name="annee_choix" placeholder="année"></input>
                    <input type="submit" class="choix-graphique" name="jour" value="par jour"></input>
                    <input type="text" class="choix-valeur" name="mois_choix" placeholder="mois"></input>
                    <input type="submit" class="choix-graphique" name="heure" value="par heure"></input>
                    <input type="text" class="choix-valeur" name="jour_choix" placeholder="jour"></input>
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
        if (taille_list == 24) {
            Labels = ["0h","1h","2h","3h","4h","5h","6h","7h","8h","9h","10h","11h","12h","13h","14h","15h","16h","17h","18h","19h","20h","21h","22h","23h"];
        } else if (taille_list == 31) {
            Labels = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
        } else if (taille_list == 30) {
            Labels = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30];
        } else if (taille_list == 28) {
            Labels = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28];
        } else if (taille_list == 29) {
            Labels = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29];
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