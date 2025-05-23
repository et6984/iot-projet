#include <ESP8266WiFi.h>
#include "DHT.h"

#define DHTPIN D6
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

const char* ssid     = "ET";
const char* password = "mot_de_passe";
 
const char* host = "172.20.10.5";
 
void setup() {
  Serial.begin(115200);
  delay(100);

  dht.begin();

  // We start by connecting to a WiFi network
 
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
 
  Serial.println("");
  Serial.println("WiFi connected");  
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}
 
int value = 0;
 
void loop() {
  float humi = dht.readHumidity();
  float temp = dht.readTemperature();

  delay(5000);
  ++value;
 
  Serial.print("connecting to ");
  Serial.println(host);
  
  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 8888;
  if (!client.connect(host, httpPort)) {
    Serial.println("connection failed");
    return;
  }
  
  // We now create a URI for the request
  String valeur = "?temp=" + String(temp) + "&humi=" + String(humi);
  String url = "/iot-projet/php/data_test.php";
  String requete = url + valeur;
  Serial.print("Requesting URL: ");
  Serial.println(requete);
  
  // This will send the request to the server
  client.print(String("GET ") + requete + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" + 
               "Connection: close\r\n\r\n");
  delay(500);
  
  // Read all the lines of the reply from server and print them to Serial
  while(client.available()){
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }
  
  Serial.println();
  Serial.println("closing connection");
  delay(60000);
}