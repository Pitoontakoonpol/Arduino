#include <Adafruit_GFX.h>    // Core graphics library
#include <MCUFRIEND_kbv.h>   // Hardware-specific library
MCUFRIEND_kbv tft;
#include <TouchScreen.h>

#include <Fonts/FreeSans9pt7b.h>
#include <Fonts/FreeSans12pt7b.h>
#include <Fonts/FreeSerif12pt7b.h>
#include <FreeDefaultFonts.h>

#define BLACK   0x0000
#define RED     0xF800
#define GREEN   0x07E0
#define WHITE   0xFFFF
#define GREY    0x8410
#define ORANGE  0xFF66
#define YELLOW  0xFFE0
#define BLUE    0x001F
#define CYAN    0x07FF
#define MAGENTA 0xF81F

/*
   list fonts

   &FreeSans9pt7b
   &FreeSans12pt7b
   &FreeSerif12pt7b
   &FreeSmallFont
   &FreeSevenSegNumFont

*/
#include "qrcode.h"

const int XP = 6, XM = A2, YP = A1, YM = 7; //ID=0x9341
const int TS_LEFT = 907, TS_RT = 136, TS_TOP = 942, TS_BOT = 139;

#define MINPRESSURE 200
#define MAXPRESSURE 1000

int16_t BOXSIZE;
int16_t PENRADIUS = 1;
uint16_t ID, oldcolor, currentcolor;

TouchScreen ts = TouchScreen(XP, YP, XM, YM, 300);
TSPoint tp;

void setup() {
  Serial.begin(115200);
  tft.reset();
  tft.setRotation(1);
  tft.fillScreen(BLACK);

  uint16_t identifier = tft.readID();
  Serial.print("ID = 0x");
  Serial.println(identifier, HEX);
  if (identifier == 0xEFEF) identifier = 0x9486;
  tft.begin(identifier);
  tft.invertDisplay(false);
  registerCus();
  delay(5000);
  dataCus();
}

void loop() {

}

void showmsgXY(int x, int y, int sz, const GFXfont *f, const char *msg)
{
  tft.setFont(f);
  tft.setCursor(x, y);
  tft.setTextColor(GREEN);
  tft.setTextSize(sz);
  tft.print(msg);
}

void headerApplication(int x, int y, int sz, const GFXfont *f, const char *msg)
{
  tft.setFont(f);
  tft.setCursor(x, y);
  tft.setTextColor(RED);
  tft.setTextSize(sz);
  tft.print(msg);
}

void dataCus()
{
  tft.setRotation(1);
  tft.fillRect(20 , 90 , 480, 250, BLACK);
  showmsgXY(20, 130, 1.5, &FreeSans9pt7b, "ID: ");
  showmsgXY(20, 170, 1.5, &FreeSans9pt7b, "NAME: ");
  showmsgXY(20, 210, 1.5, &FreeSans9pt7b, "Balance: ");
  showmsgXY(20, 250, 1.5, &FreeSans9pt7b, "Points: ");

  tft.drawLine(250, 100, 250, 350, WHITE);

  topUpPlay();
}

void nameHeader()
{
  tft.fillScreen(BLACK);
  tft.setRotation(1);
  tft.setTextColor(RED);
  tft.setTextSize(7);
  tft.setCursor(20, 20);
  tft.print("H");

  tft.setTextColor(BLUE);
  tft.setTextSize(7);
  tft.setCursor(70, 20);
  tft.print("H");

  tft.setTextColor(GREEN);
  tft.setTextSize(7);
  tft.setCursor(120, 20);
  tft.print("M");

  headerApplication(170, 65, 3, &FreeSans9pt7b, "Application");

}

void registerCus()
{
  tft.setRotation(1);
  nameHeader();

  showmsgXY(170, 170, 2, &FreeSans9pt7b, "LOGIN");
  showmsgXY(150, 250, 2, &FreeSans9pt7b, "REGISTER");

  while (1) {
    tp = ts.getPoint();
    pinMode(XM, OUTPUT);
    pinMode(YP, OUTPUT);

    if (tp.z < MINPRESSURE || tp.z > MAXPRESSURE) continue;
    
    if (tp.x > 500 && tp.x < 580  && tp.y > 400 && tp.y < 700) {
      break;
    }
    else if (tp.x > 690 && tp.x < 770  && tp.y > 400 && tp.y < 720) {
      break;
    }
   
    tft.setCursor(180, 280);
    //    tft.print("tp.x=" + String(tp.x) + " tp.y=" + String(tp.y) + "   ");
    tft.fillRect(190 , 280 , 200, 20, BLACK);
    showmsgXY(190, 295, 1, &FreeSans9pt7b, ("tp.x=" + String(tp.y) + " tp.y=" + String(tp.x) + "   ").c_str());
  }
  tft.fillRect(20 , 90 , 480, 250, BLACK);
  qrcode();
}

void qrcode()
{

  // Start time
  uint32_t dt = millis();

  // Create the QR code
  QRCode qrcode;
  uint8_t qrcodeData[qrcode_getBufferSize(5)];
  qrcode_initText(&qrcode, qrcodeData, 5, ECC_LOW,
                  "00020101021131500016A00000067701011301031100203BsC03121234567890126304339D");

  // Delta time
  dt = millis() - dt;
  Serial.print("QR Code Generation Time: ");
  Serial.println(dt);

  // Top quiet zone
  tft.fillRect(170, 100, 130, 130, WHITE);
  for (uint8_t y = 0; y < qrcode.size; y++) {
    // Each horizontal module
    for (uint8_t x = 0; x < qrcode.size; x++) {
      if (qrcode_getModule(&qrcode, x, y))
      {
        tft.fillRect(x * 3 + 175 , y * 3 + 105 , 3, 3, BLACK);
      }
    }
  }
}

void topUpPlay()
{
  showmsgXY(330, 150, 2, &FreeSans9pt7b, "Play");
  showmsgXY(305, 230, 2, &FreeSans9pt7b, "Top Up");
}

void playButton()
{
  dataCus(); showmsgXY(170, 270, 2, &FreeSans9pt7b, "Play");
  while (1) {
    tp = ts.getPoint();
    pinMode(XM, OUTPUT);
    pinMode(YP, OUTPUT);

    if (tp.z < MINPRESSURE || tp.z > MAXPRESSURE) continue;

    tft.setCursor(180, 280);
    //    tft.print("tp.x=" + String(tp.x) + " tp.y=" + String(tp.y) + "   ");
    tft.fillRect(190 , 280 , 200, 20, BLACK);
    showmsgXY(190, 295, 1, &FreeSans9pt7b, ("tp.x=" + String(tp.y) + " tp.y=" + String(tp.x) + "   ").c_str());

    if (tp.y > (int) 630 - 35 && tp.y < (int) 630 + 35  && tp.x > (int) 780 - 35 && tp.x < (int) 780 + 35) break;
  }
}
