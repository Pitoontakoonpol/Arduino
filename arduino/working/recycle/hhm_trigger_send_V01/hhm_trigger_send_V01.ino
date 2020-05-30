#include <ESP8266WiFi.h>
#include <Wire.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>


int box_id = 191000001;
char ambient_url = 'http://103.13.229.32/hhm/trigger/index.php';


IPAddress local_ip = {192, 168, 2, 200};
IPAddress gateway = {192, 168, 2, 1};
IPAddress subnet = {255, 255, 255, 0};

const char* ssid = "AmbientSoft_Guest";
const char* password = "020300031";


int ledWifi = D0;
int ledTrigger = D1;

int Button = D2;
int ButtonState = 0;
int ButtonState_old = 0;

int Relay = D3;
int RelayState = 0;
int RelayState_old = 0;


WiFiServer server(80);
void setup() {
  Wire.begin();

  Serial.begin(115200);
  pinMode(ledWifi, OUTPUT);
  pinMode(ledTrigger, OUTPUT);

  pinMode(Button, INPUT);
  pinMode(Relay, INPUT);

  digitalWrite(ledWifi, LOW);
  digitalWrite(ledTrigger, LOW);



  delay(100);

  // Connect to WiFi network
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {

    delay(150);
    digitalWrite(ledWifi, LOW);
    delay(150);
    digitalWrite(ledWifi, HIGH);
    Serial.print(".");
  }

  WiFi.config(local_ip, gateway, subnet);

  Serial.println("");
  Serial.print(ssid);
  Serial.println(" Connected");

  // Start the server
  server.begin();
  Serial.println("Server started");
  Serial.print("Ambient Box ID : ");
  Serial.println(box_id);
  Serial.print("Ambient Box IP : ");
  Serial.println(WiFi.localIP());




  Serial.println();
  Serial.println("Board is Ready!");
  Serial.println();
}
void loop() {
  HTTPClient http;
  String ADCData, station, getData, Link;
  ButtonState = digitalRead(Button);
  RelayState = digitalRead(Relay);

  Serial.print("State : ");
  Serial.print(ButtonState_old);
  Serial.print("/");
  Serial.print(ButtonState);
  Serial.print(" - ");
  Serial.print(RelayState_old);
  Serial.print("/");
  Serial.println(RelayState);
  if (ButtonState == 1 && ButtonState_old != ButtonState) {
    Serial.println("Button HIGH");


    getData = "?boxid=" + String(box_id) + "&ButtonState=" + String(ButtonState);
    Link = "http://103.13.229.32/hhm/trigger/index.php" + getData;

    http.begin(Link);

    int httpCode = http.GET();
    String payload = http.getString();

    Serial.println(httpCode);
    Serial.println(payload);
    digitalWrite(ledTrigger, HIGH);
    delay(150);
    digitalWrite(ledTrigger, LOW);


  }
  else if (ButtonState == 0 && ButtonState_old != ButtonState) {
    digitalWrite(ledTrigger, LOW);
    Serial.println("LOW");
  }

  ButtonState_old = ButtonState;
  RelayState_old = RelayState;

  http.end();  //Close connection
  delay(50);
}


/*
  References:
  https://circuits4you.com/2018/03/10/esp8266-nodemcu-post-request-data-to-website/
  https://circuits4you.com/2018/03/10/esp8266-http-get-request-example/

*/
