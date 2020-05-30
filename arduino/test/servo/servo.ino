//#include <Servo.h>
#include <VarSpeedServo.h>
#define servoControl 3

VarSpeedServo myServo;

void setup() {
  Serial.begin(115200);
  myServo.attach(servoControl);
}

void loop() {
  myServo.write(90, 255, false);
  delay(1000);
  myServo.write(45, 30, false);
  delay(1000);
  myServo.write(0);
  myServo.detach();
  delay(1000);
}
