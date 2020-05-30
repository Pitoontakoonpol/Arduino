/*
    Esp 8266 ->  RFID Module
    3.3V  ->    3.3V
    GND   ->    GND
    ขาD7  ->    MOSI
    ขาD6  ->    MISO
    ขาD5  ->    SCK
    ขาD2  ->    SDA(SS)
    ขาD1  ->    RST

*/

#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <SPI.h>
#include <RFID.h>

#define SS_PIN 4
#define RST_PIN 5
#define Con 16

int ledwifi = A0;

RFID rfid(SS_PIN, RST_PIN);

int box_id = 191000003;
String ambient_url = "http://s02.ambient.co.th/hhm/trigger/index.php";
int branch_id = 0;

IPAddress local_ip = {192, 168, 1, 153};
IPAddress gateway = {192, 168, 1, 1};
IPAddress subnet = {255, 255, 255, 0};
WiFiServer server(80);
WiFiClient client;
ESP8266WiFiMulti WiFiMulti;
HTTPClient http;

int serNum0, serNum1, serNum2, serNum3, serNum4;

void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.init();

  
  for (uint8_t t = 4; t > 0; t--) {
    Serial.printf("[SETUP] WAIT %d...\n", t);
    Serial.flush();
    delay(1000);
  }

  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP("AmbientSoft_Guest", "020300031");
  WiFiMulti.addAP("HHM", "hhm123456");
  WiFiMulti.addAP("Poki", "poki1234");
  WiFiMulti.addAP("FARASHAD2.4", "0811391919");

  connectWiFi();

  pinMode(Con, OUTPUT);
  pinMode(ledwifi, OUTPUT);
  analogWrite(ledwifi, 0);
  Serial.println("");
  Serial.println(digitalRead(Con));
  digitalWrite(Con, LOW);

}

void loop() {
  if ((WiFiMulti.run() == WL_CONNECTED))
  {
    rfidRead();
    
  }
}

void connectWiFi()
{
  while (WiFiMulti.run() != WL_CONNECTED)
  {
    Serial.print("Looking for WiFi: ");
    Serial.println(WiFi.SSID());

    delay(150);
    analogWrite(ledwifi, 0);
    delay(150);
    analogWrite(ledwifi, 1023);
  }
  Serial.print(" connected to WiFi: ");
  Serial.println(WiFi.SSID());

  analogWrite(ledwifi, 1023);

  WiFi.config(local_ip, gateway, subnet);

  server.begin();
  Serial.println("Server started");
  Serial.print("Ambient Box ID : ");
  Serial.println(box_id);
  Serial.print("Ambient Box IP : ");
  Serial.println(WiFi.localIP());

  delay(100);

}

void getServer(String cardSerial, int PaymentType)
{
  String getData, Link;
 
  
  Serial.println("[HTTP] begin...\n");
  getData = "?boxid=" + String(box_id) + "&bid=" + String(branch_id) + "&mid=" + cardSerial + "&pb=" + PaymentType;
  Link = (String)ambient_url + getData;
  
  Serial.println("link = " + Link);
  
  if (http.begin(client, Link))
  {
    Serial.println("[HTTP] GET...\n");
     int httpCode = http.GET();
    if (httpCode > 0)
    {
      Serial.println("[HTTP] GET... code: " + httpCode);

      if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY)
      {
        String payload = http.getString();
        Serial.println(payload);
      } else {
        Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
      }
      http.end();
    } else {
      Serial.printf("[HTTP} Unable to connect\n");
    }
  }
  delay(1000);
}

void rfidRead()
{
  String cardSerial;

  if (rfid.isCard()) {
    if (rfid.readCardSerial()) {
      //      if (rfid.serNum[0] != serNum0
      //          && rfid.serNum[1] != serNum1
      //          && rfid.serNum[2] != serNum2
      //          && rfid.serNum[3] != serNum3
      //          && rfid.serNum[4] != serNum4
      //         ) {
      //        /* With a new cardnumber, show it. */
      //        Serial.println(" ");
      //        Serial.println("Card found");
      //        serNum0 = rfid.serNum[0];
      //        serNum1 = rfid.serNum[1];
      //        serNum2 = rfid.serNum[2];
      //        serNum3 = rfid.serNum[3];
      //        serNum4 = rfid.serNum[4];

      //Serial.println(" ");
      Serial.println("Card Number:");
      Serial.print("Dec: ");
      Serial.print(rfid.serNum[0], DEC);
//      Serial.print(", ");
      Serial.print(rfid.serNum[1], DEC);
//      Serial.print(", ");
      Serial.print(rfid.serNum[2], DEC);
//      Serial.print(", ");
      Serial.print(rfid.serNum[3], DEC);
//      Serial.print(", ");
      Serial.print(rfid.serNum[4], DEC);
      Serial.println(" ");
      cardSerial += rfid.serNum[0];
      cardSerial += rfid.serNum[1];
      cardSerial += rfid.serNum[2];
      cardSerial += rfid.serNum[3];
      cardSerial += rfid.serNum[4];

//      Serial.print("Hex: ");
//      Serial.print(rfid.serNum[0], HEX);
//      Serial.print(", ");
//      Serial.print(rfid.serNum[1], HEX);
//      Serial.print(", ");
//      Serial.print(rfid.serNum[2], HEX);
//      Serial.print(", ");
//      Serial.print(rfid.serNum[3], HEX);
//      Serial.print(", ");
//      Serial.print(rfid.serNum[4], HEX);
      Serial.println(" ");
      
      getServer(cardSerial, 9);
      digitalWrite(Con, HIGH);
      delay(500);
      digitalWrite(Con, LOW);
      Serial.println(" ");
    }
  }
  rfid.halt();
}
