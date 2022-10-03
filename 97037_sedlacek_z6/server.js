const WebSocket = require('ws');
const https = require('https');
const fs = require('fs');

const server = https.createServer({
    cert: fs.readFileSync(`/home/xsedlacekp/webte.fei.stuba.sk-chain-cert.pem`),
    key: fs.readFileSync(`/home/xsedlacekp/webte.fei.stuba.sk.key`)
})

server.listen(9000)

const ws = new WebSocket.Server({ server })

let id = 1;
const hp = [];
let winner = "";
let pos1 = [14,15];
let pos2 = [15,15];
let dir1 = [1,0];
let dir2 = [-1,0];

for (let i = 0; i<30; i++){
    hp[i] = [];
    for (let j = 0; j<30; j++) {
        hp[i][j] = 0;
    }
}


ws.on('connection', (socket) =>{
    socket.id = id++;
    console.log("Player :"+socket.id);

    socket.on("message", (data) =>{
        const msg = JSON.parse(data);
        if(msg.dir && socket.id === 1){
            dir1 = msg.dir;
        }else if(msg.dir && socket.id === 2){
            dir2 = msg.dir
        }
    })
})

intervalID = setInterval(() => {
    if(ws.clients.size === 2) {


        if ((pos1[0] + dir1[0] >= 29) || (pos1[1] + dir1[1] >= 29)){
            // blue hits wall end
            winner = "RED"
            ws.clients.forEach(client=>{
                client.send(JSON.stringify({"winner":winner}))
            })
            clearInterval(intervalID);
        }

        if ((pos1[0] + dir1[0] <= 0) || (pos1[1] + dir1[1] <= 0)){
            // blue hits wall 0
            winner = "RED"
            ws.clients.forEach(client=>{
                client.send(JSON.stringify({"winner":winner}))
            })
            clearInterval(intervalID);
        }

        // blue
        pos1[0] += dir1[0];
        pos1[1] += dir1[1];


        if(hp[pos1[0]][pos1[1]] === 2){
            // blue eats red
            winner = "RED"
            ws.clients.forEach(client=>{
                client.send(JSON.stringify({"winner":winner}))
            })
            clearInterval(intervalID);
        }
        //blue
        hp[pos1[0]][pos1[1]] = 1;




        if (( ((pos2[0] + dir2[0] >=29) || (pos2[0] + dir2[0] <=0))
                || ((pos2[1] + dir2[1] >=29)) || ((pos2[1] + dir2[1] <=0)) )
            && (( (pos1[0] + dir1[0] >= 29) || (pos1[0] + dir1[0] <= 0))
                || ((pos1[1] + dir1[1] >= 29)|| (pos1[1] + dir1[1] <= 0)))){
            // both hit wall
            winner = "DRAW"
            ws.clients.forEach(client=>{
                client.send(JSON.stringify({"winner":winner}))
            })
            clearInterval(intervalID);
        }





        if ((pos2[0] + dir2[0] >=29) || (pos2[1] + dir2[1] >=29)){
            // red hits wall end
            winner = "BLUE"
            ws.clients.forEach(client=>{
                client.send(JSON.stringify({"winner":winner}))
            })
            clearInterval(intervalID);
        }

        if ((pos2[0] + dir2[0] <= 0) || (pos2[1] + dir2[1] <= 0)){
            // red hits wall 0
            winner = "BLUE"
            ws.clients.forEach(client=>{
                client.send(JSON.stringify({"winner":winner}))
            })
            clearInterval(intervalID);
        }

        pos2[0] += dir2[0];
        pos2[1] += dir2[1];
        if(hp[pos2[0]][pos2[1]] === 1){
            // red eats blue
            winner = "BLUE"
            ws.clients.forEach(client=>{
                client.send(JSON.stringify({"winner":winner}))
            })
            clearInterval(intervalID);
        }
        hp[pos2[0]][pos2[1]] = 2;
        ws.clients.forEach(client => {
            client.send(JSON.stringify({'hp': hp}))
        })
    }
}, 600);