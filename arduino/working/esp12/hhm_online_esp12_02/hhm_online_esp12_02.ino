#include <ESP8266WiFi.h>
#include <WebSocketClient.h>


#define ledwifi 16

String box_id = "20200001";

IPAddress local_ip = {192, 168, 1, 201};
IPAddress gateway = {192, 168, 1, 1};
IPAddress subnet = {255, 255, 255, 0};

//const char* ssid = "ambient_robotics1";
//const char* password = "12345678";

const char* ssid = "AmbientSoft_Guest";
const char* password = "020300031";



//###################################################WS

char* host = "103.76.180.24";
//char* host = "192.168.1.95";

int RW_SW = 12;
String RW_SW_State;
boolean handshakeFailed = 0;
String data = "";
char path[] = "/";

const int espport = 1919;
WebSocketClient webSocketClient;
unsigned long previousMillis = 0;
unsigned long currentMillis;
unsigned long interval = 300; //interval for sending data to the websocket server in ms

String Serial_Header = "cmd:" + box_id + "_";
//###################################################WS





WiFiClient client;


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

  // Connect to WiFi network
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {

    delay(150);
    digitalWrite(ledwifi, LOW);
    delay(150);
    digitalWrite(ledwifi, HIGH);
    Serial.print(".");
  }

  WiFi.config(local_ip, gateway, subnet);

  Serial.println("");
  Serial.print(ssid);
  Serial.println(" Connected");
  digitalWrite(ledwifi, HIGH);

  // Start the server
  server.begin();
  Serial.println("Server started");
  Serial.print("Ambient Box ID : ");
  Serial.println(box_id);
  Serial.print("Ambient Box IP : ");
  Serial.println(WiFi.localIP());


  wsconnect();
}

String To_Serial;
void loop()
{


  if (client.connected()) {
    currentMillis = millis();
    webSocketClient.getData(data);

    if (data.length() > 10) {
      //Serial.print("RECEIVE:");
      //Serial.println(data);




      ////####################CRW_SW_Statehecking STRING
      if (data.indexOf(Serial_Header) != -1) {

        To_Serial = data.substring(Serial_Header.length());

        Serial.println(To_Serial);





        
      }


      ////####################END OF Checking STRING





      if (abs(currentMillis - previousMillis) >= interval) {
        previousMillis = currentMillis;
        data = (String) analogRead(A0); //read adc values, this will give random value, since no sensor is connected.
        //For this project we are pretending that these random values are sensor values
        webSocketClient.sendData(data);//send sensor data to websocket server
      }
    }
    else {
    }
    delay(5);
  }

  RW_SW_State = (String)digitalRead(RW_SW);

  if (To_Serial.indexOf("TX7") != -1 && RW_SW_State == "0") {
    Serial.println("Reward1");
    webSocketClient.sendData("MCU" + (String)box_id + " Reward:1");
    To_Serial="";
  } else if (To_Serial.indexOf("TX7") != -1) {
    Serial.println("Reward0");
    webSocketClient.sendData("MCU" + (String)box_id + " Reward:0");
    To_Serial="";
  }

  delay(1);
}




void wsconnect() {
  // Connect to WS Server
  if (client.connect(host, espport)) {
    Serial.print("MCU" + box_id);
    Serial.println(" Connected");
  } else {
    Serial.print("MCU" + box_id);
    Serial.println(" Connection failed.");
    delay(1000);

    if (handshakeFailed) {
      handshakeFailed = 0;
      ESP.restart();
    }
    handshakeFailed = 1;
  }

  // Handshake with WS Server
  webSocketClient.path = path;
  webSocketClient.host = host;
  if (webSocketClient.handshake(client)) {
    Serial.print("MCU " + box_id);
    Serial.println(" Handshake Success");

    webSocketClient.sendData("MCU" + (String)box_id + " Handshaked.");
  } else {

    Serial.print("MCU" + box_id);
    Serial.println(" Handshake FAILED!");
    delay(5000);

    if (handshakeFailed) {
      handshakeFailed = 0;
      ESP.restart();
    }
    handshakeFailed = 1;
  }
}
