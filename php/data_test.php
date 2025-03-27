<?php
session_start();
$host = "localhost";
$db = "salle_serveur";
$user = "capteur";
$password = "password";

$temp = $_GET['temp'];
$humi = $_GET['humi'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO `capteur` (`TYPE_CAPTEUR`, `MESURE`) VALUES ('T', :temp), ('H', :humi)");
    $stmt->bindParam(':temp', $temp, PDO::PARAM_STR);
    $stmt->bindParam(':humi', $humi, PDO::PARAM_STR);
    $stmt->execute();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt1 = $pdo->prepare("SELECT MESURE FROM capteur C JOIN type_capteur TC ON C.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='T' ORDER BY CAPTEUR_DATE_HEURE DESC LIMIT 1;");
    $stmt2 = $pdo->prepare("SELECT MESURE FROM capteur C JOIN type_capteur TC ON C.TYPE_CAPTEUR=TC.TYPE_CAPTEUR WHERE TC.TYPE_CAPTEUR='H' ORDER BY CAPTEUR_DATE_HEURE DESC LIMIT 1;");
    $stmt1->execute();
    $stmt2->execute();

    $_SESSION['temp'] = $stmt1->fetch();
    $_SESSION['humi'] = $stmt2->fetch();
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
?>