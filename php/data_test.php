<?php
date_default_timezone_set('Europe/Paris');
session_start();
$host = "localhost";
$db = "salle_serveur";
$user = "capteur";
$password = "password";

$temp = $_GET['temp'];
$humi = $_GET['humi'];

$jour = date ('d');
$mois = date ('m');
$anne = date ('Y');
$heure = date ('H');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO `capteur`(`TYPE_CAPTEUR`, `MESURE`, `ANNE`, `MOIS`, `JOUR`, `HEURE`) VALUES ('T', :temp, :annee, :mois, :jour, :heure),('H', :humi, :annee, :mois, :jour, :heure)");
    $stmt->bindParam(':temp', $temp, PDO::PARAM_STR);
    $stmt->bindParam(':humi', $humi, PDO::PARAM_STR);
    $stmt->bindParam(':jour', $jour, PDO::PARAM_STR);
    $stmt->bindParam(':mois', $mois-1, PDO::PARAM_STR);
    $stmt->bindParam(':annee', $anne, PDO::PARAM_STR);
    $stmt->bindParam(':heure', $heure, PDO::PARAM_STR);
    $stmt->execute();
     
    if ($heure-1 == -1) {
        $moyennne_temp_jour = $pdo->prepare("SELECT AVG(MESURE) AS moyenne_temp_jour FROM capteur WHERE TYPE_CAPTEUR = 'T' AND JOUR = :jour");
        $moyennne_humi_jour = $pdo->prepare("SELECT AVG(MESURE) AS moyenne_humi_jour FROM capteur WHERE TYPE_CAPTEUR = 'H' AND JOUR = :jour");
        if ($jour == 1) {
            if ($mois == 1 || $mois == 2 || $mois == 4 || $mois == 6 || $mois == 8 || $mois == 9 || $mois == 11) {
                $moyenne_temp_jour->execute([':jour' => 31]);
                $moyenne_humi_jour->execute([':jour' => 31]);
            } elseif ($mois == 5 || $mois == 7 || $mois == 10 || $mois == 12) {
                $moyenne_temp_jour->execute([':jour' => 30]);
                $moyenne_humi_jour->execute([':jour' => 30]);
            } else {
                if ($anne % 4 == 0 && ($anne % 100 != 0 || $anne % 400 == 0)) {
                    $moyenne_temp_jour->execute([':jour' => 29]);
                    $moyenne_humi_jour->execute([':jour' => 29]);   
                } else {
                    $moyenne_temp_jour->execute([':jour' => 28]);
                    $moyenne_humi_jour->execute([':jour' => 28]);
                }
            }
        } else {
            $moyenne_temp_jour->execute([':jour' => $jour-1]);
            $moyenne_humi_jour->execute([':jour' => $jour-1]);
        }
        $moyenne_temp_jour = $moyenne_temp_jour->fetch(PDO::FETCH_ASSOC)['moyenne_temp_jour'];
        $moyenne_humi_jour = $moyenne_humi_jour->fetch(PDO::FETCH_ASSOC)['moyenne_humi_jour'];

        $requete_insert_temp_jour = $pdo->prepare("INSERT INTO `historique_donne`(`anne`, `mois`, `jour`, `donne`, `type_capteur`) VALUES (:anne, :mois, :jour, :moyenne_temp_jour, 'T')");
        $requete_insert_humi_jour = $pdo->prepare("INSERT INTO `historique_donne`(`anne`, `mois`, `jour`, `donne`, `type_capteur`) VALUES (:anne, :mois, :jour, :moyenne_humi_jour, 'H')");
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
echo $heure; 

echo $heure - 1;
?>