#include <ESP8266WiFi.h>
#include <Wire.h>


int box_id = 191000001;

IPAddress local_ip = {192, 168, 2, 210};
IPAddress gateway = {192, 168, 2, 1};
IPAddress subnet = {255, 255, 255, 0};

const char* ssid = "AmbientSoft_Guest";
const char* password = "020300031";

int slaveAddress2 = 9;
int slaveAddress3 = 10;
int slave = 0;

int ledWifi = D0;
int ledTransfer = D1;
int TX_Start=0;
int TX_End=0;
String TX_Data = "Hi";
int TX_Data_send = 0;

WiFiServer server(80);




void setup(){
  Wire.begin();
  Serial.begin( 115200 );
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




  pinMode(ledWifi, OUTPUT);
  pinMode(ledTransfer, OUTPUT);
  digitalWrite(ledWifi, LOW);
  digitalWrite(ledTransfer, LOW);
  
}


void loop()
{

  // Check if a client has connected
  WiFiClient client = server.available();
  if (!client) 
  {
    return;
  }

  // Wait until the client sends some data

  Serial.println("Client Connected");
  while (!client.available()) 
  {
    delay(1);
  }

  // Read the first line of the request
  String request = client.readStringUntil('\r');
  Serial.println(request);
  client.flush();

  if (request.indexOf("TX=") != -1) 
  {
    TX_Start=request.indexOf("TX=")+3;
    TX_End=request.indexOf("///");
    TX_Data=request.substring(TX_Start,TX_End);
    //Serial.println(TX_Data);
    Wire.beginTransmission( slaveAddress2 );
    Wire.write(TX_Data.toInt());
    Wire.endTransmission();
  }
  delay(1);
}
