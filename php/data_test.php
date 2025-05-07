<?php
date_default_timezone_set('Europe/Paris');
session_start();
$host = "localhost";
$db = "salle_serveur";
$user = "capteur";
$password = "password";

$temp = $_GET['temp'];
$humi = $_GET['humi'];

$jour = date("d");
$mois = date("m");
$anne = date("Y");
$heure = date("H");

if (!empty($temp) || !empty($humi)) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("
            INSERT INTO `capteur`(`TYPE_CAPTEUR`, `MESURE`, `ANNE`, `MOIS`, `JOUR`, `HEURE`) 
            VALUES 
            ('T', :temp, :annee, :mois, :jour, :heure),
            ('H', :humi, :annee, :mois, :jour, :heure)
        ");
        $stmt->bindParam(':temp', $temp, PDO::PARAM_STR);
        $stmt->bindParam(':humi', $humi, PDO::PARAM_STR);
        $stmt->bindParam(':jour', $jour, PDO::PARAM_STR);
        $stmt->bindParam(':mois', $mois, PDO::PARAM_STR);
        $stmt->bindParam(':annee', $anne, PDO::PARAM_STR);
        $stmt->bindParam(':heure', $heure, PDO::PARAM_STR);
        $stmt->execute();
        
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "Aucune donnée reçue.";
}
?>