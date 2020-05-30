<?php

function convert_payment_by($Payment_By) {
  switch ($Payment_By) {
    case 0: $Payment_By_Name = 'Ambient Token';
      break;
    case 7: $Payment_By_Name = 'Wahlap';
      break;
    case 9: $Payment_By_Name = 'Ambient RFID';
      break;
    case 202: $Payment_By_Name = 'Master';
      break;
    case 203: $Payment_By_Name = 'JCB';
      break;
    case 204: $Payment_By_Name = 'AMEX';
      break;
    case 209: $Payment_By_Name = 'Credit-Card';
      break;
    case 301: $Payment_By_Name = 'QR-KBank';
      break;
    case 302: $Payment_By_Name = 'QR-SCB';
      break;
    case 303: $Payment_By_Name = 'QR-BBL';
      break;
    case 304: $Payment_By_Name = 'QR-TMB';
      break;
    case 305: $Payment_By_Name = 'QR-KTB';
      break;
    case 306: $Payment_By_Name = 'QR-BAY';
      break;
    case 307: $Payment_By_Name = 'QR-GSB';
      break;
    case 401: $Payment_By_Name = 'Grab';
      break;
  }
  return $Payment_By_Name;
}
