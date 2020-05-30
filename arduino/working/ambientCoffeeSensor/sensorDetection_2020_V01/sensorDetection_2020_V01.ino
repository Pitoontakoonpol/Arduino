//Motor
#define MotorPWM 16
#define MotorIN1 5
#define MotorIN2 4

//Sensor
#define sensorOpen 0
#define sensorDetection 2

//Limit Swicht
#define OpenSW 14
#define CloseSW 12

//Motor Speed
int motorSpeed = 1023;

//--------------------------------
int readSwichtOpen = 0;
int readSwichtDetect = 0;
//--------------------------------

void setup() {
 Serial.begin(115200);

  //Motor
  pinMode(MotorPWM, OUTPUT);
  pinMode(MotorIN1, OUTPUT);
  pinMode(MotorIN2, OUTPUT);

   //Sensor
   pinMode(sensorOpen, INPUT);
   pinMode(sensorDetection, INPUT);

  //Limit Swicht
  pinMode(OpenSW, INPUT_PULLUP);
  pinMode(CloseSW, INPUT_PULLUP);
 
}

void loop() {
  readSwichtOpen = digitalRead(sensorOpen);
  Serial.print("readSwichtOpen = ");
  Serial.println(readSwichtOpen);

  readSwichtDetect = digitalRead(sensorDetection);
  Serial.print("readSwichtDetect = ");
  Serial.println(readSwichtDetect);

  if (readSwichtOpen == 1 || readSwichtDetect == 1)
  {
    openMotor();
  }
  else
  {
    closeMotor();
  }
  delay(100);
}

void openMotor()
{
  digitalWrite(MotorPWM, motorSpeed);
  digitalWrite(MotorIN1, HIGH);
  digitalWrite(MotorIN2, LOW);

  do
  {
    readSwichtOpen = digitalRead(OpenSW);
    Serial.print("OpenSW = ");
    Serial.println(OpenSW);

    if (readSwichtOpen == 1)
    {
      digitalWrite(MotorPWM, motorSpeed);
      digitalWrite(MotorIN1, LOW);
    }
  }while(readSwichtOpen == 0);

  Serial.println("Open");
  delay(1);
}

void closeMotor()
{
  digitalWrite(MotorPWM, motorSpeed);
  digitalWrite(MotorIN1, LOW);
  digitalWrite(MotorIN2, HIGH);

  do
  {
    readSwichtDetect = digitalRead(CloseSW);
    Serial.print("CloseSW = ");
    Serial.println(CloseSW);

    if (readSwichtDetect == 1)
    {
      digitalWrite(MotorPWM, motorSpeed);
      digitalWrite(MotorIN2, LOW);
    }
  }while(readSwichtDetect == 0);

  Serial.println("Close");
  delay(1);
}
