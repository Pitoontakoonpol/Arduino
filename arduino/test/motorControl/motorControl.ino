#define in1 8
#define in2 9
//#define ena 3

void setup() {
  Serial.begin(115200);
  pinMode(in1, OUTPUT);
  pinMode(in2, OUTPUT);
//  pinMode(ena, OUTPUT);
  Serial.println("Machine Ready... ^^");
}

int speedMotor = 255;

void loop() {
//  analogWrite(ena, speedMotor);
  digitalWrite(in1, HIGH);
  digitalWrite(in2, LOW);
  Serial.println("GO GO GO!!!");
  delay(5000);

//  analogWrite(ena, speedMotor);
  digitalWrite(in2, HIGH);
  digitalWrite(in1, HIGH);
  Serial.println("Stop !!!");
  delay(2000);

//  analogWrite(ena, speedMotor);
  digitalWrite(in2, HIGH);
  digitalWrite(in1, LOW);
  Serial.println("Back !!!");
  delay(5000);

  digitalWrite(in2, HIGH);
  digitalWrite(in1, HIGH);
  Serial.println("Stop !!!");
  delay(5000);

}
