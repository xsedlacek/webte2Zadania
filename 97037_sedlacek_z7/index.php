<?php
session_start();
unset($_SESSION["address"]);
unset($_SESSION["response"]);
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Home</title>
</head>
<body>


<div class="card" style="text-align:center;width: 50%; margin-left: 25%;padding: 3em;">
    <form action="home.php" method="post">
        <h1>Zadanie 7</h1>
        <label for="address" style="padding-top: 5em;">Miesto</label>
        <input type="text" name="address" id="address">
        <input type="submit">
    </form>
</div>

</body>
<footer class="bg-light text-center text-lg-start  fixed-bottom">
    <p style="color: black">© Peter Sedláček</p>
</footer>
</html>
