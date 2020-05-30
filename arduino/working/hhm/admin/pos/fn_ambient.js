
function ambient_time(Tformat) {
//#####Get Date
  var d = new Date();
//##### End of Get Date
  if (Tformat === 1) {
    //#####DateU
    var dateU = d.getTime() / 1000;
    return dateU;
  } else if (Tformat === 2) {
    //#####Readable
    var dateRead = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + d.getHours();

    if (d.getMinutes() < 10) {
      dateRead += ':0' + d.getMinutes();
    } else {
      dateRead += ':' + d.getMinutes();
    }
    if (d.getSeconds() < 10) {
      dateRead += ':0' + d.getSeconds();
    } else {
      dateRead += ':' + d.getSeconds();
    }
    return dateRead;
  } else if (Tformat === 3) {
    //#####Time Session
    var dateSession = parseInt(d.getHours() * 3600) + parseInt(d.getMinutes() * 60) + parseInt(d.getSeconds());
    dateSession = Math.floor(dateSession / 900);
    return dateSession;
  }
}
function ambient_payment_option(option_code) {
  option_code = parseInt(option_code);
  var return_name = '';
  switch (option_code) {
    case 0:
      return_name = 'Cash';
      break;
    case 9:
      return_name = 'Point';
      break;
    case 201:
      return_name = 'Visa';
      break;
    case 202:
      return_name = 'Master';
      break;
    case 203:
      return_name = 'JCB';
      break;
    case 204:
      return_name = 'AMEX';
      break;
    case 301:
      return_name = 'QR-KBank';
      break;
    case 302:
      return_name = 'QR-SCB';
      break;
    case 303:
      return_name = 'QR-BBL';
      break;
    case 304:
      return_name = 'QR-TMB';
      break;
    case 305:
      return_name = 'QR-KTB';
      break;
    case 306:
      return_name = 'QR-BAY';
      break;
    case 307:
      return_name = 'QR-GSB';
      break;
  }
  return return_name;
}