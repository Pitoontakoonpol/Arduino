const path = require('path');
const express = require('express');
const WebSocket = require('ws');
const app = express();

const WS_PORT  = [65070,65080,65090];
const HTTP_PORT = 8080;

const wsServer = [];
WS_PORT.forEach((item,i) => {
    wsServer.push(new WebSocket.Server({port: item}, ()=> console.log(`WS Server is listening at ${wsServer[i].options.port}`)));
});


let connectedClients = [];
wsServer.forEach((item,i) => {connectedClients[i] = new Array(wsServer.length)});
wsServer.forEach((item,i)=> {
    item.on('connection', (ws, req)=>{
    console.log(`Connected via ${WS_PORT[i]}`);
    connectedClients[i].push(ws);

    ws.on('message', data => {
        connectedClients[i].forEach((ws,j)=>{
            if(ws.readyState === ws.OPEN){
                ws.send(data);
            }else{
                connectedClients[i].splice(j ,1);
            }
        })
    });
});
});

WS_PORT.forEach((item,i)=>{
    app.get(`/client${i}`,(req,res)=>res.sendFile(path.resolve(__dirname, `./testClient${i}.html`)));
});

app.listen(HTTP_PORT, ()=> console.log(`HTTP server listening at ${HTTP_PORT}`));
