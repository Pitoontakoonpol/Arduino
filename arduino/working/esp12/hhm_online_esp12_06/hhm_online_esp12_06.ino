#include <ESP8266WiFi.h>
#include <WebSocketClient.h>
#include <ESP8266WiFiMulti.h>

#define ledwifi 16

char* cbox_id = "2000009";
String box_id = cbox_id;

ESP8266WiFiMulti wifiMulti;
boolean connectioWasAlive = true;
/* 
 *  Reference to ESP8266WiFiMulti Library example:
 * https://arduino-esp8266.readthedocs.io/en/latest/esp8266wifi/station-examples.html
 * 
 */
//###################################################WS

char* host = "103.76.180.24";
//char* host = "192.168.1.95";

int RW_SW = 12;
String RW_SW_State;
boolean handshakeFailed = 0;
String data = "";
char* path = "/";



const int espport = 1919;

WebSocketClient webSocketClient;

String Serial_Header = "cmd:" + box_id + "_";
//###################################################WS

WiFiClient client;

unsigned int times; //Restart every 15 minutes


int slaveAddress2 = 9;

//int ledTransfer = 0;
int TX_Start = 0;
int TX_End = 0;
String TX_Data = "Hi";
int TX_Data_send = 0;

WiFiServer server(80);



void setup() {
  Serial.begin( 115200 );

  pinMode(ledwifi, OUTPUT);
  digitalWrite(ledwifi, LOW);


  pinMode(RW_SW, INPUT_PULLUP);

  wifiMulti.addAP("AmbientSoft_Guest", "020300031");
  wifiMulti.addAP("HHM", "hhm123456");
  wifiMulti.addAP("Poki", "poki1234");
  wifiMulti.addAP("FARASHAD2.4", "0811391919");


}

String To_Serial;

void loop()
{
  monitorWiFi(); // Checking WiFi status.
  
  if (client.connected()) {
    webSocketClient.getData(data);

    if (millis() - times > 1 * 60 * 1000 )
    {
      times = millis();
      webSocketClient.sendData("MCU" + (String)box_id + " ");
    }


    if (data.length() > 10) {

      To_Serial = data.substring(Serial_Header.length());
      Serial.println(To_Serial);//Server can send data through chanelled connections.

      webSocketClient.sendData("Device " + data.substring(4));
      data = "";
    }
    else  {
    }
    delay(5);
  }

  RW_SW_State = (String)digitalRead(RW_SW);

  if (To_Serial.indexOf("TX7") != -1 && RW_SW_State == "0") {
    Serial.println("Reward1");
    webSocketClient.sendData("MCU" + (String)box_id + "_Reward:1");
    To_Serial = "";
  } else if (To_Serial.indexOf("TX7") != -1) {
    Serial.println("Reward0");
    webSocketClient.sendData("MCU" + (String)box_id + "_Reward:0");
    To_Serial = "";
  }

  

  delay(1);
}

void wsconnect() {
  // Connect to WS Server


  if (client.connect(host, espport)) {
    Serial.print("MCU" + box_id);
    Serial.println("Connected");
  } else {
    Serial.print("MCU" + box_id);
    Serial.println(" Connection failed.");
    delay(1000);

    ESP.restart();
  }

  // Handshake with WS Server
  webSocketClient.path = path;
  webSocketClient.host = host;
  if (webSocketClient.handshake(client)) {
    Serial.print("MCU " + box_id);
    Serial.println(" Handshake Success");

    //    webSocketClient.sendData("MCU" + (String)box_id + " Device Handshaked.");
    webSocketClient.sendData("MCU" + (String)box_id + " C");
  } else {

    Serial.print("MCU" + box_id);
    Serial.println(" Handshake FAILED!");
    delay(1000);

    ESP.restart();
  }
}

void monitorWiFi()
{
  if (wifiMulti.run() != WL_CONNECTED)
  {
    if (connectioWasAlive == true)
    {
      connectioWasAlive = false;
      Serial.println();
      Serial.print("Looking for WiFi ");
    }
    delay(150);
    digitalWrite(ledwifi, LOW);
    delay(150);
    digitalWrite(ledwifi, HIGH);
    Serial.print(".");
  }
  else if (connectioWasAlive == false)
  {
    connectioWasAlive = true;
    Serial.println();
    Serial.printf(" connected to %s\n", WiFi.SSID().c_str());
    digitalWrite(ledwifi, HIGH);

    server.begin();
    Serial.println("Server started");
    Serial.print("Ambient Box ID : ");
    Serial.println(box_id);
    Serial.print("Ambient Box IP : ");
    Serial.println(WiFi.localIP());

    wsconnect();

    times = millis();
  }
}
