#include <ESP8266WiFi.h>
#include <WiFiClientSecure.h>
#include "DHT.h"

#define DHTPIN D6
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

const char* ssid     = "ET";
const char* password = "mot_de_passe";

// Remplace par le nom de domaine si tu utilises un certificat valide
const char* host = "exemple.com"; // ou l'IP si configuré avec un certificat
const int httpsPort = 443;

// Pour ignorer la vérification SSL (PAS SÉCURISÉ EN PROD)
WiFiClientSecure client;

void setup() {
  Serial.begin(115200);
  delay(100);

  dht.begin();

  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nWiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  // Ignorer la vérification du certificat (à tes risques et périls)
  client.setInsecure();
}

void loop() {
  float humi = dht.readHumidity();
  float temp = dht.readTemperature();

  delay(5000);

  Serial.print("Connecting to ");
  Serial.println(host);

  if (!client.connect(host, httpsPort)) {
    Serial.println("Connection failed");
    return;
  }

  String url = "/iot-projet/php/data_test.php";
  String params = "?temp=" + String(temp) + "&humi=" + String(humi);
  String fullUrl = url + params;

  Serial.print("Requesting URL: ");
  Serial.println(fullUrl);

  client.print(String("GET ") + fullUrl + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" + 
               "User-Agent: ESP8266\r\n" +
               "Connection: close\r\n\r\n");

  delay(500);

  while (client.available()) {
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }

  Serial.println();
  Serial.println("Closing connection");
  delay(60000);
}
