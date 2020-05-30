#include <Wire.h>

const int LimitSwitchM = A3;

#define enaY 3
#define in1Y 5
#define in2Y 6
#define enbX 7
#define in3X 4
#define in4X 2
#define enaZ 9
#define in1Z 8
#define in2Z 10

#define in3G 12
#define in4G 13

int x = 0;
void setup() {
  Wire.begin(9);
   
  Serial.begin(115200); 
         
  pinMode(enaY, OUTPUT);
  pinMode(in1Y, OUTPUT);
  pinMode(in2Y, OUTPUT);
  pinMode(enbX, OUTPUT);
  pinMode(in3X, OUTPUT);
  pinMode(in4X, OUTPUT);
  pinMode(enaZ, OUTPUT);
  pinMode(in1Z, OUTPUT);
  pinMode(in2Z, OUTPUT);

  
  pinMode(in3G, OUTPUT);
  pinMode(in4G, OUTPUT);
  pinMode(LimitSwitchM, INPUT_PULLUP);

}

void forward()
{
  
    analogWrite(enbX,   1023);
    digitalWrite(in3X,   LOW);
    digitalWrite(in4X,   HIGH);
    Serial.println("Moving Forward"); 
    if(digitalRead(LimitSwitchM) == LOW)
    {
       analogWrite(enbX,   0);
    digitalWrite(in3X,   LOW);
    digitalWrite(in4X,   LOW);
    }
}
void back()
{
    analogWrite(enbX,   1023);
    digitalWrite(in4X,   LOW);
    digitalWrite(in3X,   HIGH);
    Serial.println("Moving Backward"); 
    if(digitalRead(LimitSwitchM) == LOW)
    {
       analogWrite(enbX,   0);
    digitalWrite(in3X,   LOW);
    digitalWrite(in4X,   LOW);
    }
}
void right()
{
    analogWrite(enaY,   1023);
    digitalWrite(in1Y,   LOW);
    digitalWrite(in2Y,   HIGH);
    Serial.println("Moving Right"); 
        if(digitalRead(LimitSwitchM) == LOW)
    {
        analogWrite(enaY,   0);
    digitalWrite(in1Y,   LOW);
    digitalWrite(in2Y,   LOW);
    }
}
void left()
{
    analogWrite(enaY,   1023);
    digitalWrite(in2Y,   LOW);
    digitalWrite(in1Y,   HIGH);
    Serial.println("Moving Left"); 
        if(digitalRead(LimitSwitchM) == LOW)
    {
       analogWrite(enaY,   0);
    digitalWrite(in1Y,   LOW);
    digitalWrite(in2Y,   LOW);
    }
}
void drow()
{
    analogWrite(enaZ,   1023);
    digitalWrite(in2Z,   LOW);
    digitalWrite(in1Z,   HIGH);
    Serial.println("Keeping");
    delay(700000);

    analogWrite(enaZ,   0);
    digitalWrite(in1Z,   LOW);
    digitalWrite(in2Z,   LOW);
    Serial.println("Keeping");
    delay(300000);

        digitalWrite(in3G,   LOW);
    digitalWrite(in4G,   HIGH);
    Serial.println("Keeping");

    analogWrite(enaZ,   1023);
    digitalWrite(in1Z,   LOW);
    digitalWrite(in2Z,   HIGH);
    Serial.println("Keeping");
    delay(900000);

    analogWrite(enaZ,   0);
    digitalWrite(in1Z,   LOW);
    digitalWrite(in2Z,   LOW);
    Serial.println("Keeping");

     analogWrite(enaY,   1023);
    digitalWrite(in1Y,   LOW);
    digitalWrite(in2Y,   HIGH);
     delay(900000);

      analogWrite(enbX,   1023);
    digitalWrite(in4X,   LOW);
    digitalWrite(in3X,   HIGH);
    delay(900000);

    analogWrite(enaY,   0);
    digitalWrite(in1Y,   LOW);
    digitalWrite(in2Y,   LOW);

    analogWrite(enbX,   0);
    digitalWrite(in4X,   LOW);
    digitalWrite(in3X,   LOW);

    digitalWrite(in4G,   LOW);
    digitalWrite(in3G,   HIGH);
    Serial.println("Keeping");

    
//
//    digitalWrite(in3G,   HIGH);
//    digitalWrite(in4G,   LOW);
//    Serial.println("Keeping");
//    delay(900000);

}
void keepUp()
{
    analogWrite(enaZ,   150);
    digitalWrite(in1Z,   LOW);
    digitalWrite(in2Z,   HIGH);
    Serial.println("Keeping");
}
void grab()
{
    digitalWrite(in3G,   LOW);
    digitalWrite(in4G,   HIGH);
    Serial.println("Keeping");
}

void stops()
{
    digitalWrite(enaZ,  LOW);
    digitalWrite(in1Z,  LOW);
    digitalWrite(in2Z,  LOW);
    
//    digitalWrite(in3G,  LOW);
//    digitalWrite(in4G,  LOW);
  
    
    digitalWrite(enaY,  LOW);
    digitalWrite(in2Y,  LOW);
    digitalWrite(in1Y,  LOW);

    digitalWrite(enbX,  LOW);  
    digitalWrite(in4X,  LOW);
    digitalWrite(in3X,  LOW);
    
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
  
    digitalWrite(enaZ,  LOW);
    digitalWrite(in1Z,  LOW);
    digitalWrite(in2Z,  LOW);
    
    digitalWrite(in3G,  LOW);
    digitalWrite(in4G,  LOW);
  
    
    digitalWrite(enaY,  LOW);
    digitalWrite(in2Y,  LOW);
    digitalWrite(in1Y,  LOW);

    digitalWrite(enbX,  LOW);  
    digitalWrite(in4X,  LOW);
    digitalWrite(in3X,  LOW);
    
  if (x == 4) 
  {
    forward();
  }
  else if (x == 3) 
  {
    back();
  }
  
  else if (x == 2) 
  {
    right();
  }
  else if (x == 1) 
  {
    left();
  }
  else if (x == 5) 
  {
    drow();
//    analogWrite(enaZ,   1023);
//    digitalWrite(in1Z,   LOW);
//    digitalWrite(in2Z,   HIGH);
//    Serial.println("Keeping");
//    delay(1500);
//
//    analogWrite(enaZ,   0);
//    digitalWrite(in1Z,   LOW);
//    digitalWrite(in2Z,   LOW);
//    Serial.println("Keeping");
//    delay(1500);

//        digitalWrite(in3G,   LOW);
//    digitalWrite(in4G,   HIGH);
//    Serial.println("Keeping");
//    delay(20000);
//
//    analogWrite(enaZ,   1023);
//    digitalWrite(in2Z,   LOW);
//    digitalWrite(in1Z,   HIGH);
//    Serial.println("Keeping");
//    delay(1500);

//    digitalWrite(in3G,   HIGH);
//    digitalWrite(in4G,   LOW);
//    Serial.println("Keeping");
//    delay(200);
  }
  else if (x == 0) stops();
  
  Serial.println(x);
}
