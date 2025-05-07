<?php
session_start();
$host = "localhost";
$db = "salle_serveur";
$user = "capteur";
$password = "password";

$temp = $_GET['temp'];
$humi = $_GET['humi'];

if (!empty($temp) || !empty($humi)) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("
            INSERT INTO `capteur`(`TYPE_CAPTEUR`, `MESURE`) 
            VALUES 
            ('T', :temp),
            ('H', :humi)
        ");
        $stmt->bindParam(':temp', $temp, PDO::PARAM_STR);
        $stmt->bindParam(':humi', $humi, PDO::PARAM_STR);
        $stmt->execute();
        
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "Aucune donnée reçue.";
}
?>