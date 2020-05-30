#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <RFID.h>



int box_id = 191000003;
int branch_id = 0;
char ambient_url = 'http://103.13.229.32/hhm/trigger/index.php';


IPAddress local_ip = {192, 168, 2, 153};
IPAddress gateway = {192, 168, 2, 1};
IPAddress subnet = {255, 255, 255, 0};

const char* ssid = "AmbientSoft_Guest";
const char* password = "020300031";

//const char* ssid = "FawasAP";
//const char* password = "0811391919";


int ledWifi = D0;
int ledTrigger = D1;

int Button = D2;
int ButtonState = 0;
int ButtonState_old = 0;

#define RST_PIN D3
#define SS_PIN D4

RFID rfid(SS_PIN, RST_PIN);

// Setup variables:
int serNum0, serNum1, serNum2, serNum3, serNum4;

WiFiServer server(80);
void setup() {

  Serial.begin(115200);
  SPI.begin();
  rfid.init();
  pinMode(ledWifi, OUTPUT);
  pinMode(ledTrigger, OUTPUT);

  pinMode(Button, INPUT);

  digitalWrite(ledWifi, LOW);
  digitalWrite(ledTrigger, HIGH);



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
  ButtonState = digitalRead(Button);

  Serial.print("State : ");
  Serial.print(ButtonState_old);
  Serial.print("/");
  Serial.println(ButtonState);


  if (ButtonState == 1 && ButtonState_old != ButtonState) {
    String nodata;
    trigger_on(0,nodata);

  }

  if (rfid.isCard()) {

    rfid.readCardSerial();
    Serial.println("Card detected:");
    String cardSerial;
    for (int i = 0; i < 5; i++)
    {
      cardSerial += rfid.serNum[i];
    }
    cardSerial = cardSerial, DEC;
    Serial.println(cardSerial);
    trigger_on(9,cardSerial);


  
  digitalWrite(ledTrigger, LOW);
  delay(250);
  digitalWrite(ledTrigger, HIGH);
    delay(250);
  }

  ButtonState_old = ButtonState;

  delay(50);
}

void trigger_on(int PaymentType,String cardSerial) {
  HTTPClient http;

  String ADCData, station, getData, Link;
  Serial.println("Button HIGH ");
  Serial.print("Member: ");
  Serial.println(cardSerial);



  getData = "?boxid=" + String(box_id) + "&bid=" + String(branch_id) + "&mid=" + cardSerial + "&pb="+PaymentType;
  Link = "http://103.13.229.32/hhm/trigger/index.php" + getData;

  http.begin(Link);

  int httpCode = http.GET();
  String payload = http.getString();

  Serial.println(httpCode);
  Serial.println(payload);

  

  
  digitalWrite(ledWifi, LOW);
  delay(250);
  digitalWrite(ledWifi, HIGH);
  http.end();  //Close connection
}



/*

  Port Mapping:
  3.3V -> 3.3V
  GND -> GND
  D7 -> MOSI
  D6 -> MISO
  D5 -> SCK
  D4 -> SDA(SS)
  D3 -> RST

  References:
  https://www.myarduino.net/article/181/%E0%B8%AA%E0%B8%AD%E0%B8%99%E0%B9%83%E0%B8%8A%E0%B9%89%E0%B8%87%E0%B8%B2%E0%B8%99-nodemcu-esp8266-rfid-%E0%B8%AD%E0%B9%88%E0%B8%B2%E0%B8%99%E0%B8%9A%E0%B8%B1%E0%B8%95%E0%B8%A3%E0%B8%84%E0%B8%B5%E0%B8%A2%E0%B9%8C%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%8C%E0%B8%94-%E0%B8%84%E0%B8%A7%E0%B8%9A%E0%B8%84%E0%B8%B8%E0%B8%A1%E0%B9%80%E0%B8%9B%E0%B8%B4%E0%B8%94%E0%B8%9B%E0%B8%B4%E0%B8%94%E0%B9%84%E0%B8%9F-led

*/
