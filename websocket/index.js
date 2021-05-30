let WebSocket = require('ws');

let fs = require('fs')
let jwt = require('jsonwebtoken');
let dotenv = require('dotenv');

dotenv.load();

let server = new WebSocket.Server({ port: 8000 });
let jwtKey = fs.readFileSync(process.env.WS_JWT_PUBLIC_KEY);

server.on('connection', function (ws, request) {
  console.log('connected: %s', request.connection.remoteAddress);

  ws.on('message',  message => {
    let data = JSON.parse(message);
    try {
      if (data.type === 'auth') {
        let token = jwt.verify(data.token, jwtKey, { algorithms: ['RS256'] });
        console.log('username: %s', token.sub);
        ws.username = token.sub;
      }

      if (data.type === 'new-message') {
        server.clients.forEach(client => {
          if (client.username === data.dialog.partner.username) {
            client.send(JSON.stringify(data));
          }
        });
      }
    } catch (err) {
      console.log(err);
    }
  });
});

