#include <Wire.h>


int x = 0;
void setup() {
  Wire.begin(9);
   
  Serial.begin(115200);        

  pinMode(3, OUTPUT); //ENBX
  pinMode(8, OUTPUT); //IN4Z
  pinMode(7, OUTPUT); //IN3Z
  pinMode(6, OUTPUT); //ENBZ
  pinMode(5, OUTPUT); //ENAY
  pinMode(2, OUTPUT); //IN4X
  pinMode(4, OUTPUT); //IN3X
  pinMode(13, OUTPUT); //IN1Y
  pinMode(11, OUTPUT); //IN2Y
/*
  digitalWrite(3,LOW);
  digitalWrite(8,LOW);
  digitalWrite(7,LOW);
  digitalWrite(6,LOW);
  digitalWrite(9,LOW);
  digitalWrite(2,LOW);
  digitalWrite(4,LOW);
  digitalWrite(10,LOW);
  digitalWrite(11,LOW);
  */

     
//    analogWrite(3,   250);
//    digitalWrite(7,   LOW);
//    digitalWrite(8,   HIGH);
//    Serial.println("Moving Forward"); 
//    delay(5000);
//
//    analogWrite(3,   250);
//    digitalWrite(8,   LOW);
//    digitalWrite(7,   HIGH);
//    Serial.println("Moving Forward"); 
//    delay(5000);
}

void forward()
{
  
    analogWrite(9,   250);
    digitalWrite(10,   LOW);
    digitalWrite(11,   HIGH);
    Serial.println("Moving Forward"); 
}
void back()
{
    analogWrite(9,   250);
    digitalWrite(11,   LOW);
    digitalWrite(10,   HIGH);
    Serial.println("Moving Backward"); 
}
void right()
{
    analogWrite(6,   250);
    digitalWrite(13,   LOW);
    digitalWrite(12,   HIGH);
    Serial.println("Moving Right"); 
}
void left()
{
    analogWrite(6,   250);
    digitalWrite(12,   LOW);
    digitalWrite(13,   HIGH);
    Serial.println("Moving Left"); 
}
void keepDrow()
{
  analogWrite(10,   250);
    digitalWrite(4,   LOW);
    digitalWrite(2,   HIGH);
    Serial.println("Keeping");

}
void keepUp()
{
    analogWrite(10,   250);
    digitalWrite(2,   LOW);
    digitalWrite(4,   HIGH);
    Serial.println("Keeping");

}
void stops()
{
    digitalWrite(12,  LOW);
    digitalWrite(11,  LOW);
    digitalWrite(2,   LOW);
    digitalWrite(4,   LOW);
    digitalWrite(7,   LOW);
    digitalWrite(8,   LOW);
    digitalWrite(13,   LOW);
    digitalWrite(5,   LOW);
    digitalWrite(10,   LOW);

    digitalWrite(9,   LOW);  
    digitalWrite(3,   LOW);
    digitalWrite(6,   LOW);
    Serial.println("Machine Stoping"); 
}

void loop() 
{
  Wire.onReceive(receiveEvent);
  delay(1);
}

void receiveEvent( int bytes )
{
  
  x = Wire.read(); 
  
  digitalWrite(3,LOW);
  digitalWrite(8,LOW);
  digitalWrite(7,LOW);
  digitalWrite(6,LOW);
  digitalWrite(5,LOW);
  digitalWrite(2,LOW);
  digitalWrite(4,LOW);
  digitalWrite(11,LOW);
  digitalWrite(13,LOW);
  

  if (x == 1) forward();
  else if (x == 2) back();
  else if (x == 3) left();
  else if (x == 4) right();
  else if (x == 5) keepDrow();
  else if (x == 6) keepUp();
  else if (x == 0) stops();
  
  Serial.println(x);
}
