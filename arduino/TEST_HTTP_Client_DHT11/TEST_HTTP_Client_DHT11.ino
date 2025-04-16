#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include "DHT.h"

/************ Définition des constantes ******************/
#define DHTTYPE DHT11   // Type du capteur DHT
#define DHTPIN D6       // Broche de connexion du capteur

DHT dht(DHTPIN, DHTTYPE); // Déclaration du capteur

// Identifiants WiFi
#ifndef STASSID
#define STASSID "ET"
#define STAPSK "Funipops/051122"
#endif

// Adresse du serveur et fichier PHP
String HOST_NAME = "http://172.20.10.4:80"; // Adresse IP de ton serveur
String PHP_FILE_NAME = "/iot-projet/data_test"; // Nom du fichier PHP

void setup() {
  Serial.begin(115200);
  Serial.println("\n\nDémarrage de l'ESP8266...");

  // Initialisation du capteur DHT
  dht.begin();
  
  // Connexion au WiFi
  WiFi.mode(WIFI_STA);
  WiFi.begin(STASSID, STAPSK);
  
  Serial.print("test wifi : ");
  Serial.println(WiFi.status());

  Serial.print("Connexion au WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nConnecté !");
  Serial.print("Adresse IP : ");
  Serial.println(WiFi.localIP());
}

void loop() {
  // Lecture des données du capteur
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();

  // Vérification si la lecture est valide
  if (isnan(temperature) || isnan(humidity)) {
    Serial.println("Échec de lecture du capteur DHT !");
  } else {
    Serial.print("Température : ");
    Serial.print(temperature);
    Serial.println("°C");

    Serial.print("Humidité : ");
    Serial.print(humidity);
    Serial.println("%");

    // Création de l'URL avec les valeurs de température et d'humidité
    String valeur = "?temp=" + String(temperature) + "&humi=" + String(humidity); 
    String server = HOST_NAME + PHP_FILE_NAME + valeur;
    
    Serial.print("Envoi des données à : ");
    Serial.println(server);
    
    // Création de la connexion HTTP
    WiFiClient client;
    HTTPClient http;
    
    Serial.print("client : ");
    Serial.println(client);

    http.begin(client, server);  // Début de la requête HTTP
    http.setTimeout(5000); // Timeout de 5 secondes

    // Envoi de la requête GET
    int httpCode = http.GET();
    Serial.print("Code HTTP : ");
    Serial.println(httpCode);

    // Vérification de la réponse du serveur
    if (httpCode > 0) {
      if (httpCode == HTTP_CODE_OK) {
        String payload = http.getString();
        Serial.println("Réponse du serveur : " + payload);
      } else {
        Serial.printf("Erreur HTTP GET, code : %d\n", httpCode);
      }
    } else {
      Serial.printf("Échec de la requête HTTP, erreur : %s\n", http.errorToString(httpCode).c_str());
    }

    http.end(); // Fermeture de la connexion HTTP
  }

  delay(20000);  // Délai entre chaque envoi de données (20 secondes)
}