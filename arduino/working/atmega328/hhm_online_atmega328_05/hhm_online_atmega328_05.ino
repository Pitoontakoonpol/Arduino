#include <Wire.h>

/* visualiztion
 *    543210GAA32109
 *  28--------------15
 *   1--------------14
 *    r01234VGxx5678 
 *    
 *       ___
 *reset | U |      A5
 *D0 RX |   |      A4
 *D1 TX |   |      A3
 *D2    |   |      A2
 *D3    |   |      A1
 *D4    |   |      A0
 *VCC   |   |      GND
 *GND   |   |      AREF
 *cry   |   |      AVCC
 *cry   |   | SCK  D13
 *D5    |   | MISO D12
 *D6    |   | MOSI D11
 *D7    |   |      D10
 *D8    |   |      D9
 *       ̅ ̅ ̅
 */
const int FB_SW = A0;
const int LR_SW = A1;
const int TD1_SW = A3;
const int TD2_SW = A2;

//FB
#define FB_PWM 9 // D15
#define FB_DRI 10 // D16

//LR
#define LR_PWM 5 // D3
#define LR_DRI 6 // D6

//TD
#define TD_PWM 11 // D17
#define TD_DRI 12 // D18

//GR
#define GR_PWM 3 // D5

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
  pinMode(FB_DRI, OUTPUT);

  //LR
  pinMode(LR_PWM, OUTPUT);
  pinMode(LR_DRI, OUTPUT);

  //TD
  pinMode(TD_PWM, OUTPUT);
  pinMode(TD_DRI, OUTPUT);

  //GR
  pinMode(GR_PWM, OUTPUT);

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
    digitalWrite(FB_PWM, HIGH);
    digitalWrite(FB_DRI, LOW);
    

  } else if (TX == "2") {
    digitalWrite(FB_PWM, LOW);
    digitalWrite(FB_DRI, HIGH);
  }
}


void move_LR(String TX) {

  if (TX == "3") {
    if (SW_State.substring(1, 2) != "0") {
      digitalWrite(LR_PWM, HIGH);
      digitalWrite(LR_DRI, LOW);
      

    } else {
      digitalWrite(LR_DRI, LOW);

    }

  } else if (TX == "4") {
    digitalWrite(LR_PWM, LOW);
    digitalWrite(LR_DRI, HIGH);
   
  }
}


void move_TD(String TD_Direction) {
  if (TD_Direction == "1") {
    digitalWrite(TD_PWM, HIGH);
    digitalWrite(TD_DRI, LOW);

  } else if (TD_Direction == "2") {
    digitalWrite(TD_DRI, HIGH);
    digitalWrite(TD_PWM, LOW);

  }
}


void move_Halt() {
  digitalWrite(FB_DRI, HIGH);
  digitalWrite(FB_PWM, HIGH);

  digitalWrite(LR_DRI, HIGH);
  digitalWrite(LR_PWM, HIGH);

  digitalWrite(TD_DRI, LOW);
  digitalWrite(TD_PWM, LOW);

}

void move_Stop(){
  //This function may look similar to move_Halt()
  //But it is to lower the power consumption and reduce heat of the device
  //Thus, safer operations

  digitalWrite(FB_DRI, LOW);
  digitalWrite(FB_PWM, LOW);

  digitalWrite(LR_DRI, LOW);
  digitalWrite(LR_PWM, LOW);

  digitalWrite(TD_DRI, LOW);
  digitalWrite(TD_PWM, LOW);
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
