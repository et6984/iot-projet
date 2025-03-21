NOM : DEHONDT
Prénom : Gabin
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