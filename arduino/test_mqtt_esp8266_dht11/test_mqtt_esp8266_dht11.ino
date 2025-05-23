/***************************************************
  Adafruit MQTT Library ESP8266 Example

  Must use ESP8266 Arduino from:
    https://github.com/esp8266/Arduino

  Works great with Adafruit's Huzzah ESP board & Feather
  ----> https://www.adafruit.com/product/2471
  ----> https://www.adafruit.com/products/2821

  Adafruit invests time and resources providing this open source code,
  please support Adafruit and open-source hardware by purchasing
  products from Adafruit!

  Written by Tony DiCola for Adafruit Industries.
  MIT license, all text above must be included in any redistribution
 ****************************************************/
#include <ESP8266WiFi.h>
#include "Adafruit_MQTT.h"
#include "Adafruit_MQTT_Client.h"
#include "DHT.h"

/************************* WiFi Access Point *********************************/

#define WLAN_SSID       "labo_snir"
#define WLAN_PASS       "snbaggio123"

/************************* Adafruit.io Setup *********************************/

#define AIO_SERVER      "192.168.112.104"           //voir adresse IP du broker, soit celle de votre RPI
#define AIO_SERVERPORT  1883                    // use 8883 for SSL
#define AIO_USERNAME    "elevesn"               // voir fichier users de l'activité 2
#define AIO_KEY         "snir"           // voir mdp utilisé dans l'activité 2

/************ Global State (you don't need to change this!) ******************/
#define DHTTYPE DHT11   // DHT 11
#define DHTPIN D6        // broche ou l'on a branche le capteur
DHT dht(DHTPIN, DHTTYPE); //déclaration du capteur

// Create an ESP8266 WiFiClient class to connect to the MQTT server.
WiFiClient client;
// or... use WiFiFlientSecure for SSL
//WiFiClientSecure client;

// Setup the MQTT client class by passing in the WiFi client and MQTT server and login details.
Adafruit_MQTT_Client mqtt(&client, AIO_SERVER, AIO_SERVERPORT, AIO_USERNAME, AIO_KEY);

/****************************** Publisher ***************************************/
Adafruit_MQTT_Publish temp = Adafruit_MQTT_Publish(&mqtt,"P210/temperature");
Adafruit_MQTT_Publish hum = Adafruit_MQTT_Publish(&mqtt,"P210/humidite");

/*************************** Sketch Code ************************************/

// Bug workaround for Arduino 1.6.6, it seems to need a function declaration
// for some reason (only affects ESP8266, likely an arduino-builder bug).
void MQTT_connect();

void setup() {
  Serial.begin(115200);
  delay(10);

  Serial.println(F("MQTT demo"));

  // Connect to WiFi access point.
  Serial.println(); Serial.println();
  Serial.print("Connecting to ");
  Serial.println(WLAN_SSID);

  WiFi.begin(WLAN_SSID, WLAN_PASS);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println();

  Serial.println("WiFi connected");
  Serial.println("IP address: "); Serial.println(WiFi.localIP());
  
  dht.begin();
}

float x=21;
boolean increased=true;

void loop() {
  // Ensure the connection to the MQTT server is alive (this will make the first
  // connection and automatically reconnect when disconnected).  See the MQTT_connect
  // function definition further below.
  MQTT_connect();

  float h = dht.readHumidity();//on lit l'hygrometrie
  float t = dht.readTemperature();//on lit la temperature en celsius (par defaut)

  // Now we can publish stuff!
  Serial.print(F("\nSending temperature val "));
  Serial.print(t);
  Serial.print("...");
  if (! temp.publish(t)) {
    Serial.println(F("Failed"));
  } else {
    Serial.println(F("OK!"));
  }
  Serial.print(F("\nSending humidite val "));
  Serial.print(h);
  Serial.print("...");
  if (! hum.publish(h)) {
    Serial.println(F("Failed"));
  } else {
    Serial.println(F("OK!"));
  }
  delay(30000);

  // ping the server to keep the mqtt connection alive
  // NOT required if you are publishing once every KEEPALIVE seconds
  /*
  if(! mqtt.ping()) {
    mqtt.disconnect();
  }
  */
}

// Function to connect and reconnect as necessary to the MQTT server.
// Should be called in the loop function and it will take care if connecting.
void MQTT_connect() {
  int8_t ret;

  // Stop if already connected.
  if (mqtt.connected()) {
    return;
  }

  Serial.print("Connecting to MQTT... ");

  uint8_t retries = 3;
  while ((ret = mqtt.connect()) != 0) { // connect will return 0 for connected
       Serial.println(mqtt.connectErrorString(ret));
       Serial.println("Retrying MQTT connection in 5 seconds...");
       mqtt.disconnect();
       delay(5000);  // wait 5 seconds
       retries--;
       if (retries == 0) {
         // basically die and wait for WDT to reset me
         while (1);
       }
  }
  Serial.println("MQTT Connected!");
}
