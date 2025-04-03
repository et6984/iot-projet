#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include "DHT.h"

/************ Global State (you don't need to change this!) ******************/
#define DHTTYPE DHT11   // DHT 11
#define DHTPIN D6         // broche ou l'on a branche le capteur
DHT dht(DHTPIN, DHTTYPE); //déclaration du capteur


#ifndef STASSID
#define STASSID "ET"
#define STAPSK "Funipops/051122"
#endif

String HOST_NAME = "http://172.20.10.4:80"; // REPLACE WITH YOUR PC's IP ADDRESS
String PHP_FILE_NAME   = "/iot-projet/php/data_test.php";  //REPLACE WITH YOUR PHP FILE NAME
String tempQuery = "?temperature=15";   // TEST 


void setup() {

  Serial.begin(115200);

  Serial.println();
  Serial.println();
  Serial.println();
  
  WiFi.mode(WIFI_STA);
  
  WiFi.begin(STASSID, STAPSK);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected! IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  float temperature = dht.readTemperature();
  float humidity  = dht.readHumidity();
  Serial.print("Température: ");
  Serial.println(temperature);
  Serial.print("Humidité: ");
  Serial.println(humidity);

  String valeur = "?temp=" + String(temperature) + "&humi=" + String(humidity); 
  String server = HOST_NAME + PHP_FILE_NAME + valeur;
  
  Serial.print("Tentative d'envoi HTTP à : ");
  Serial.println(server);
  
  HTTPClient http;
  WiFiClient client;
  
  http.begin(client, server);  // HTTP
  http.addHeader("Content-Type", "application/json");
  
  http.setTimeout(5000); 

  int httpCode = http.GET();
  Serial.print("Code HTTP : ");
  Serial.println(httpCode);

  if (httpCode > 0) {
    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println(payload);
    } else {
      Serial.printf("HTTP GET... code: %d\n", httpCode);
    }
  } else {
    Serial.printf("HTTP GET échoué, erreur: %s\n", http.errorToString(httpCode).c_str());
  }

  http.end();
  delay(20000);  // Délai entre les lectures
}