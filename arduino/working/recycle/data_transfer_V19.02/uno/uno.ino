#include <Wire.h>


int x = 0;
void setup() {
  Wire.begin(9);

  Serial.begin(115200);

  //### Tier1
  pinMode(4, OUTPUT);   //Cont
  pinMode(7, OUTPUT);   //Ord1
  pinMode(6, OUTPUT);   //Ord2

  //### Tier2
  pinMode(5, OUTPUT);   //Cont
  pinMode(3, OUTPUT);   //Ord1
  pinMode(2, OUTPUT);   //Ord2

  //### Tier3
  pinMode(9, OUTPUT);   //Cont
  pinMode(8, OUTPUT);   //Ord1
  pinMode(11, OUTPUT);  //Ord2

  //### Tier4
  pinMode(10, OUTPUT);  //Cont
  pinMode(12, OUTPUT);  //Ord1
  pinMode(13, OUTPUT);  //Ord2

  Serial.println("Ambient Uno Board is Ready!");
  Serial.println();

}

void loop() {
  Wire.onReceive(receiveEvent);
  delay(10);
}


void receiveEvent( int bytes ) {

  x = Wire.read();
  
  Serial.println();
  Serial.print("Receive = ");
  Serial.println(x);

  stops();
  if (x == 1) forward();
  else if (x == 2) back();
  else if (x == 3) left();
  else if (x == 4) right();
  else if (x == 5) Down();
  else if (x == 6) Up();
  else stops();
  delay(10);

}



void stops() {
  digitalWrite(2,  LOW);
  digitalWrite(3,  LOW);
  digitalWrite(4,  LOW);
  digitalWrite(5,  LOW);
  digitalWrite(6,  LOW);
  digitalWrite(7,  LOW);
  digitalWrite(8,  LOW);
  digitalWrite(9,  LOW);
  digitalWrite(10,  LOW);
  digitalWrite(11,  LOW);
  digitalWrite(12,  LOW);
  digitalWrite(13,  LOW);
}

//##### Control Tier 1
void forward() {
  analogWrite(4, 250);
  digitalWrite(7, HIGH);
  digitalWrite(6, LOW);
  Serial.println("Moving Forward");
}
void back() {
  analogWrite(4,   250);
  digitalWrite(6, HIGH);
  digitalWrite(7, LOW);
  Serial.println("Moving Backward");
}

//##### Control Tier 2
void left() {
  analogWrite(5, 250);
  digitalWrite(3, HIGH);
  digitalWrite(2, LOW);
  Serial.println("Moving Left");
}
void right() {
  analogWrite(5, 250);
  digitalWrite(2, HIGH);
  digitalWrite(3, LOW);
  Serial.println("Moving Right");
}

//##### Control Tier 3
void Down() {
  analogWrite(9, 250);
  digitalWrite(8, HIGH);
  digitalWrite(11, LOW);
  Serial.println("Moving Down");

}
void Up() {
  analogWrite(9, 250);
  digitalWrite(8, LOW);
  digitalWrite(11, HIGH);
  Serial.println("Moving Up");

}
