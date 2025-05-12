<?php
session_start();
$host = "localhost";
$db = "projet-iot";
$user = "capteur";
$password = "password";

$temp = $_GET['temp'];
$humi = $_GET['humi'];
$id_salle = $_GET['id_salle'];

if (!empty($temp) && !empty($humi) && !empty($id_salle)) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("
            INSERT INTO `capteur`(`ID_SALLE`, `TYPE_CAPTEUR`, `MESURE`) 
            VALUES 
            (:id_salle, 'T', :temp),
            (:id_salle, 'H', :humi);
        ");
        $stmt->bindParam(':temp', $temp, PDO::PARAM_STR);
        $stmt->bindParam(':humi', $humi, PDO::PARAM_STR);
        $stmt->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        $stmt->execute();
        
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "Aucune donnée reçue.";
}
?>