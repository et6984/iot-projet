NOM : DEHONDT / ESSAN
Prénom : Gabin / Ange
Classe : B1 CIEL IR

<h2 style="texte-align:center">Mise en œuvre du MQTT avec un ESP8266</h2>

**1. Expression du besoin client**

L'entreprise a besoin d'un systeme pour contrôler la température et l'humidité d'une salle des serveurs. 
Pour résoudre se problème on utilisera un DHT11 et un ESP8266, tout cela en connection WIFI vers un Raspberry qui va récuperer les données (température et humidité) et les mettre en place par Node-Red.

**2. Schéma de principe**

<img src="image/schema-principe.png" widht="50%">

**3. Découverte du broker mosquitto**

<dd>
3.1 Câblage du Raspberry
    
- Adresse IP : 192.168.112.104

3.2 Installation de mosquitto

<img src="image/install-mosquitto.png">

3.3 Test de fonctionnement de mosquitto

<img src="image/publisher-mosquitto.png">
</dd>
<br>

**4. Test de fonctionnement ESP8266**

<img src="image/test-wifi-esp8266.png">

**5. Test de fonctionnement mqtt**

<div style="display:flex">
<img src="image/test-msqtt-esp8266.png" width="80%">
<img src="image/test-msqtto-esp8266_moniteur.png" width="50%">
</div>
<br>

**6. Affichage des valeurs avec Node-Red**

<img src="image/affichage_nodered.png">
<img src="image/nodeRed-configuration-temp.png">

**7. Câblage du DHT11**

<img src="image/cablage-esp-dht.jpg">

**8. Modification du programme de l’esp8266 pour transmettre la température et l’humidité**

<img src="image/modification-code-esp-node.png">

**9. Affichage de la température et de l’humidité avec Node-Red**

<img src="image/affichage_nodered_dht11.png">
<img src="image/nodeRed-configuration.png">

**10. Mise en place d'une interface web pour remplacer Node-Red**

- Inscription :

    <img src="image/page_inscrption.png" width="80%">
    - Création d'un utilisateur avec nom, prénom, mot de passe hasher en argon2.
    <br>

- Connexion : 

    <img src="image/page_connexion.png" width="80%">
    - Identification de l'utilisateur par la vérification du mot de passe, et recupération du nom ainsi que la première lettre du prénom pour afficher sur l'interface.
    <br>

- Interface : 

    <img src="image/page_interface.png" width="80%">
    - Affichage des données en direct de la température et l'humidité envoyé par l'ESP8266 à la base de donnée et recupérer par le PHP sur l'interface.
    - Affichage des données sur l'ensemble de l'année par la moyenne de chaque mois de l'année choix.
    <br>

