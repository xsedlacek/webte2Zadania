<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Tron</title>
</head>
<body>

<div class="container text-center mb-5">
    <h1>Game Tron</h1>
</div>


<strong>
    <div class="container text-center mb-3" id="winner">

    </div>
</strong>

<div class="container text-center mb-3">
    <canvas id="myCanvas" width="600" height="600" style="border:1px solid #000000"></canvas>
</div>
<script>

    let gameObject;
    const socket = new WebSocket('wss://site153.webte.fei.stuba.sk:9000');

    socket.addEventListener("message", msg => {
        gameObject = JSON.parse(msg.data);
        console.log(gameObject.hp);

        if(typeof gameObject.hp !== 'undefined'){
            gameObject.hp.forEach((row, x) => {
                row.forEach((cell, y) => {
                    if(cell === 1){
                        setFilled(x, y, "blue");
                    }else if(cell === 2){
                        setFilled(x, y, "red");
                    }else if(cell === 0){
                        setFilled(x, y, "white");
                    }
                });
            });
        }else if (typeof gameObject.winner !== 'undefined'){
            document.getElementById("winner").innerHTML = "Winner: "+gameObject.winner
        }
    })


    const c = document.getElementById("myCanvas");
    const ctx = c.getContext("2d");
    const setFilled = (x,y,color) => {
        ctx.fillStyle = color;
        ctx.fillRect(x*20,y*20,20,20)
    }

    for(let i = 0; i< 600; i+=20){
        ctx.moveTo(0,i);
        ctx.lineTo(600,i);
    }
    for(let j = 0; j< 600; j+=20){
        ctx.moveTo(j,0);
        ctx.lineTo(j,600);
    }
    ctx.stroke();


    document.onkeydown = (e) => {

        e = e || window.event;

        if (e.keyCode == '38') {
            dir = [0,-1];
            socket.send(JSON.stringify({'dir':dir}));
        }
        else if (e.keyCode == '40') {
            dir = [0,1];
            socket.send(JSON.stringify({'dir':dir}));
        }
        else if (e.keyCode == '37') {
            dir = [-1,0];
            socket.send(JSON.stringify({'dir':dir}));
        }
        else if (e.keyCode == '39') {
            dir = [1,0];
            socket.send(JSON.stringify({'dir':dir}));
        }

    }

</script>


</body>
</html>