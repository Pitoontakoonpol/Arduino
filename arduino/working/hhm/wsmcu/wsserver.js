console.log('\n' + logDate() + ' Controller Start!');

/**************************websocket_example.js*************************************************/
var bodyParser = require("body-parser");
const express = require('express'); //express framework to have a higher level of methods
const app = express(); //assign app variable the express class/method
var http = require('http');
var path = require("path");
app.use(bodyParser.urlencoded({
  extended: false
}));
app.use(bodyParser.json());
const server = http.createServer(app);
require('dns').lookup(require('os').hostname(), function(err, add, fam) {
  console.log('addr: ' + add);
});
/**********************websocket setup**************************************************************************************/
//var expressWs = require('express-ws')(app,server);
const WebSocket = require('ws');

const s = new WebSocket.Server({
  server
});
//when browser sends get request, send html file to browser
// viewed at http://localhost:30000
app.get('/', function(req, res) {
  res.sendFile(path.join(__dirname + '/nodemcu.html'));
});
//*************************************************************************************************************************
//***************************ws chat server********************************************************************************
//app.ws('/echo', function(ws, req) {
s.on('connection', function(ws, req) {
  /******* when server receives messsage from client trigger function with argument message *****/
  ws.on('message', function(message) {
    //  console.log(logDate() + " Received: " + message);


    var prefix = message.substring(0, 3); //Get prefix command (only first 3 Digits).
    var bcChannel = message.substring(3, 10);
    var bcMessage = message.substring(10, message.LENGTH);

    //    console.log();

    if (prefix === "MCO") {
      //Machine Connected.

      //console.log('ws = "'+ws.channel+'"');
    } else if (prefix === "SCO") {
      //Server Connected.
      bcChannel = message.substring(3, 10) + "s";

    } else if (prefix === "SCM") {
      //Server Command Machine.

    } else if (prefix === "MFB") {
      //Machine FeedBack.
      //p = "MCU"+message;
      //console.log('p = "'+ws.channel+'"');
    } else if (prefix === "MPU") {
      //Machine Pulse.
    } else if (prefix === "SCQ") {
      //Server Check Queue.

      bcChannel = message.substring(3, 10) + "s";
    } else if (prefix === "MRR") {
      //Machine Reward Return,
    }

    ws.channel = bcChannel; // Register to the Channel
    var total_bc = s.clients.size;
    console.log(logDate() + " | " + prefix + " | " + bcChannel + " | " + bcMessage);

    s.clients.forEach(function(client) {
      //broadcast incoming message to all clients (s.clients)
      if (client != ws && client.readyState && client.channel == ws.channel) {
        client.send(prefix + bcMessage);
      }
    });
  });
  ws.on('close', function() {
    console.log(logDate() + " - lost one client " + ws.channel);
  });

  ws.on('error', function(e) {
    console.log(logDate() + "\nerror=> " + e + "\n");
  });
  //ws.send("new client connected");
  //d = new Date();
  //  console.log(logDate() + " + new client connected " + ws.channel);
});



server.listen(1919);

function logDate() {
  return (new Date()).toLocaleString('th-TH', {
    timeZone: 'Asia/Bangkok'
  });
}

/* Reference
https://esp8266-shop.com/blog/websocket-connection-between-esp8266-and-node-js-server/
*/
