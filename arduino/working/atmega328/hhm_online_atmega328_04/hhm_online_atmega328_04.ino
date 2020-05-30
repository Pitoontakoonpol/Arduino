#include <Wire.h>

const int FB_SW = A2;
const int LR_SW = A3;
const int TD1_SW = A5;
const int TD2_SW = A4;

//FB
#define FB_PWM 10
#define FB_IN1 A1
#define FB_IN2 A0

//LR
#define LR_PWM 3
#define LR_IN1 4
#define LR_IN2 5

//TD
#define TD_PWM 9
#define TD_IN1 12
#define TD_IN2 11

//GR
#define GR_PWM 6
#define GR_IN1 8
#define GR_IN2 7

int TX_value_start, TY_value_start;
String read_serial, TX_Value, TY_Value, SW_State;
int FB_SW_State, LR_SW_State, TD1_SW_State, TD2_SW_State;
int FB_Speed = 200;
int LR_Speed = 200;
int TD_Speed = 200;

void setup() {


  Serial.begin(115200);

  //FB
  pinMode(FB_PWM, OUTPUT);
  pinMode(FB_IN1, OUTPUT);
  pinMode(FB_IN2, OUTPUT);

  //LR
  pinMode(LR_PWM, OUTPUT);
  pinMode(LR_IN1, OUTPUT);
  pinMode(LR_IN2, OUTPUT);

  //TD
  pinMode(TD_PWM, OUTPUT);
  pinMode(TD_IN1, OUTPUT);
  pinMode(TD_IN2, OUTPUT);

  //GR
  pinMode(GR_PWM, OUTPUT);
  pinMode(GR_IN1, OUTPUT);
  pinMode(GR_IN2, OUTPUT);

  //Switch
  pinMode(FB_SW, INPUT_PULLUP);
  pinMode(LR_SW, INPUT_PULLUP);
  pinMode(TD1_SW, INPUT_PULLUP);
  pinMode(TD2_SW, INPUT_PULLUP);
  TX_Value = "9";
  TY_Value = "999999";  
  /*TYabcdef:
   * a = holding on down, start grabing 
   * b = holding to top
   * c = holding on top 
   * d = holding to left 
   * e = holding on left 
   * f = holding to front
   */
}

void loop() {

  if (Serial.available() && TX_Value != "5" && TX_Value != "8" && TX_Value != "9") {
    read_serial = Serial.readStringUntil('\n');

    TX_value_start = read_serial.indexOf("TX");
    TY_value_start = read_serial.indexOf("TY");

    TX_Value = read_serial.substring(TX_value_start + 2, 3); // Return TX 1 digit
    TY_Value = read_serial.substring(TY_value_start + 2, 11); // Return TY 6 digits

  }

  SW_State = String(digitalRead(FB_SW)) + String(digitalRead(LR_SW)) + String(digitalRead(TD1_SW)) + String(digitalRead(TD2_SW));

  Serial.print("TX|" + TX_Value + " " + SW_State);

  if (TX_Value == "1" || TX_Value == "2") {
    move_FB(TX_Value);
  }
  else if (TX_Value == "3" || TX_Value == "4") {
    move_LR(TX_Value);
  }
  else if (TX_Value == "5") {
    move_GR();
  }
  else if (TX_Value == "8") {
    check_GR_Reverse();
  }
  else if (TX_Value == "9") {
    reset();
  } else {
    move_Halt();
    delay(1);
    move_Stop();
  }
  Serial.println();

  delay(250);
}

void move_FB(String TX) {

  analogWrite(FB_PWM, FB_Speed);
  if (TX == "1") {
    digitalWrite(FB_IN1, HIGH);
    digitalWrite(FB_IN2, LOW);

  } else if (TX == "2") {
    digitalWrite(FB_IN1, LOW);
    digitalWrite(FB_IN2, HIGH);
  }
}


void move_LR(String TX) {

  if (TX == "3") {
    if (SW_State.substring(1, 2) != "0") {
      analogWrite(LR_PWM, LR_Speed);
      digitalWrite(LR_IN1, HIGH);
      digitalWrite(LR_IN2, LOW);

    } else {
      digitalWrite(LR_IN1, LOW);

    }

  } else if (TX == "4") {
    analogWrite(LR_PWM, LR_Speed);
    digitalWrite(LR_IN1, LOW);
    digitalWrite(LR_IN2, HIGH);

  }
}


void move_TD(String TD_Direction) {
  if (TD_Direction == "1") {
    analogWrite(TD_PWM, TD_Speed);
    digitalWrite(TD_IN1, LOW);
    digitalWrite(TD_IN2, HIGH);

  } else if (TD_Direction == "2") {
    analogWrite(TD_PWM, TD_Speed);
    digitalWrite(TD_IN1, HIGH);
    digitalWrite(TD_IN2, LOW);

  }
}


void move_Halt() {
  analogWrite(FB_PWM, 255);
  digitalWrite(FB_IN1, HIGH);
  digitalWrite(FB_IN2, HIGH);

  analogWrite(LR_PWM, 255);
  digitalWrite(LR_IN1, HIGH);
  digitalWrite(LR_IN2, HIGH);

  analogWrite(TD_PWM, 0);
  digitalWrite(TD_IN1, LOW);
  digitalWrite(TD_IN2, LOW);

}

void move_Stop(){
  //This function may look similar to move_Halt()
  //But it is to lower the power consumption and reduce heat of the device
  //Thus, safer operations
  analogWrite(FB_PWM, 0);
  digitalWrite(FB_IN1, LOW);
  digitalWrite(FB_IN2, LOW);

  analogWrite(LR_PWM, 255);
  digitalWrite(LR_IN1, LOW);
  digitalWrite(LR_IN2, LOW);

  analogWrite(TD_PWM, 0);
  digitalWrite(TD_IN1, LOW);
  digitalWrite(TD_IN2, LOW);
}

int delay_FB = 1; //Using for reset() only

int GR_History = 1; //Using for move_GR()

int time_count = 0;
int digit4_check = 1; //By check, it means "Please, check it."
int digit6_check = 1;

void reset() {
  
  //If not Top; Move Top
  if (SW_State.substring(2, 4) != "10") {
    move_TD("2"); 
    
    if (time_count >= 500) {
      analogWrite(GR_PWM, GR_Power(TY_Value.substring(1, 2))); //Digit 2 of GR_PWM
      Serial.print(" GR Power 2 :" + (String)GR_Power(TY_Value.substring(1, 2)));
    }
  } else {
    
    //It's Top
    move_Halt(); delay(1); move_Stop();
    
    //Check Left
    analogWrite(GR_PWM, GR_Power(TY_Value.substring(2, 3))); //Digit 3 of GR_PWM
    Serial.print(" GR Power 3 :" + (String)GR_Power(TY_Value.substring(2, 3)));

    //Avoiding the system from performing step 3 again once it comes to this loop after it's ready for step 4
    if (digit4_check == 1) {
      time_count = 0;
      digit4_check = 0;
    }

    //If not Left; Move Left
    if (SW_State.substring(1, 2) != "0") {
      move_LR("3");

      if (time_count >= 300) {
        analogWrite(GR_PWM, GR_Power(TY_Value.substring(3, 4))); //Digit 4 of GR_PWM
        Serial.print(" GR Power 4 :" + (String)GR_Power(TY_Value.substring(3, 4)));
      }

    } else {
      
      //It's Top Left
      move_Halt(); delay(2); move_Stop();

      analogWrite(GR_PWM, GR_Power(TY_Value.substring(4, 5))); //Digit 5 of GR_PWM
      Serial.print(" GR Power 5 :" + (String)GR_Power(TY_Value.substring(4, 5)));
      
      // Check if FB is at Back
      if (delay_FB == 1) {
        move_FB("2");
        delay_FB = 0;
        
        delay(100);
      } else {
        //It's Top Left and Not Back
        
        //Check Front
        if (digit6_check == 1) {
          time_count = 0;
          digit6_check = 0;
        }

        if (SW_State.substring(0, 1) != "0") {
          
          //It's not Front
          move_FB("2");
          if (time_count >= 100) {
            analogWrite(GR_PWM, GR_Power(TY_Value.substring(5, 6))); //Digit 6 of GR_PWM
            Serial.print(" GR Power 6 :" + (String)GR_Power(TY_Value.substring(5, 6)));
          }

        } else {
          
          //It's Top Left Front
          move_Halt();

          //Clearing flags
          delay_FB = 1;
          GR_History = 1;
          time_count = 0;
          digit4_check = 1;
          digit6_check = 1;

          //Set the command to "Stop"
          TX_Value = "0";
          
          analogWrite(GR_PWM, 0);

          //For power saving
          delay(1);
          move_Stop();
          
          //END OF RESET
          Serial.println();
          Serial.println("##########RESET COMPLETED##########");
        }
      }
    }
  }
  Serial.println();
  time_count += 100;
  delay(100);
}

void move_GR() {
  //  move_Halt();
  //Move TD Down
  if (SW_State.substring(2, 4) != "01" && GR_History == 1) {
    move_TD("1");
    delay(200);
    GR_History = 0;
  } else if (SW_State.substring(2, 4) == "01") {
    //Grabing

    Serial.print(" GRAB 1st Step!");
    Serial.print(" GR Power 1 :" + (String)GR_Power(TY_Value.substring(0, 1)));
    analogWrite(GR_PWM, GR_Power(TY_Value.substring(0, 1)));
    digitalWrite(GR_IN1, HIGH);
    digitalWrite(GR_IN2, LOW);
    TX_Value = "9";

  } else if (SW_State.substring(2, 4) != "11") {
    check_GR_Reverse();
  }
}

int GR_Power(String Digit) {

  if (Digit == "0") return 0;
  else if (Digit == "1")  return 28;
  else if (Digit == "2")  return 57;
  else if (Digit == "3")  return 85;
  else if (Digit == "4")  return 113;
  else if (Digit == "5")  return 142;
  else if (Digit == "6")  return 170;
  else if (Digit == "7")  return 198;
  else if (Digit == "8")  return 227;
  else if (Digit == "9")  return 255;
}

int check_TD_Move_Down = 1;
int retain_reverse = 1;

void check_GR_Reverse() {

  if (check_TD_Move_Down == 1) {
    move_TD("1"); // Move Down
    delay(1500);
    check_TD_Move_Down = 0;
  }

  if (SW_State.substring(2, 4) == "10" && retain_reverse == 1) { // Check Not at the center
    // Do Reverse

    Serial.println("REVERSING!!!!!!!!!!!!");

    move_TD("2");
    Serial.print("DO REVERSING!!");
    delay(2000);
    retain_reverse = 0;
  } else {
    Serial.println("Do Nothing!");
    retain_reverse = 1;
    TX_Value = "9";
    check_TD_Move_Down = 1;
  }
}
