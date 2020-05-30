const http = require('http');

const hostname = '127.0.0.1';
const port = [3000,3001];

let app = [];
port.forEach((p,i) => {
    app[i] = http.createServer((req, res)=>{
    res.writeHead(200, {'Content-Type': 'text/plain'});
    res.end(`Hello World${i}!\n`);
    });
    app[i].listen(p, hostname);
    console.log(`Node server${i} running at http://${hostname}:${p}/`);
});