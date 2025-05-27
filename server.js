const WebSocket = require("ws");
const http = require("http");
const express = require("express");

// Crear una instancia de Express
const app = express();

// Servir archivos estáticos desde la carpeta 'public'
app.use(express.static('public'));

// Crear una instancia del servidor HTTP (Web)
const server = http.createServer(app);

// Crear y levantar un servidor de WebSockets a partir del servidor HTTP
const wss = new WebSocket.Server({ server });

// Escuchar los eventos de conexión
wss.on("connection", function connection(ws) {
    console.log("A user connected");

    // Escuchar los mensajes entrantes
    ws.on("message", function incoming(data) {
        console.log(`Received message: ${data}`);

        // Iterar todos los clientes que se encuentren conectados
        wss.clients.forEach(function each(client) {
            if (client.readyState === WebSocket.OPEN) {
                // Enviar la información recibida
                client.send(data.toString());
            }
        });
    });

    ws.on("close", () => {
        console.log("User disconnected");
    });
});

// Levantar el servidor HTTP
server.listen(8080, () => {
    console.log("Servidor funcionando. Utiliza ws://localhost:8080 para conectar.");
});
