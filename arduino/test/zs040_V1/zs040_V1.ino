#include <SoftwareSerial.h>

#define ledPin 7

String state = "";

void setup() {
  Serial.begin(38400); // Default communication rate of the Bluetooth module
  pinMode(ledPin, OUTPUT);
  digitalWrite(ledPin, LOW);
}
void loop() {
  if (Serial.available() > 0) { // Checks whether data is comming from the serial port
    state = Serial.read(); // Reads the data from the serial port
    Serial.print("Led state is: ");
    Serial.print(state.toInt());
    
    if (state == '0') {
    digitalWrite(ledPin, LOW); // Turn LED OFF
    Serial.println("LED: OFF"); // Send back, to the phone, the String "LED: ON"
    
  }
  else if (state == '1') {
    digitalWrite(ledPin, HIGH);
    Serial.println("LED: ON");;
  }
 } 
}
