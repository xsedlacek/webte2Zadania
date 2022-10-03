<?php
session_start();

$url = "https://api.mapbox.com/geocoding/v5/mapbox.places/".$_SESSION["response"]->features[0]->geometry->coordinates[0].",".$_SESSION["response"]->features[0]->geometry->coordinates[0].".json?access_token=pk.eyJ1IjoieHNlZGxhY2VrcCIsImEiOiJjbDJodG50b2QwZ2ZyM2pwOTlyamRiaGYwIn0.BgEXKaMaKNAWZQ9fV2QyEw";

$json_data = file_get_contents($url);

$response = json_decode($json_data);

$url_country = "https://restcountries.com/v3.1/name/".$_SESSION["response"]->features[0]->context[count($_SESSION["response"]->features[0]->context)-1]->text;

$json_data_country = file_get_contents($url_country);
$response_country = json_decode($json_data_country, true);
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
    <title>Info</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
        <a href="index.php" class="navbar-brand" >Zadanie 7    Peter Sedláček</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="info.php">Info  <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data.php">Map</a>
                </li>

            </ul>
        </div>
    </nav>
</header>

<body>
<div class="card" style="width: 50%; margin-left: 25%;padding: 3em;">
    <h2> Info about visitor </h2>
    <div >
        <div>Lat: <?php echo $_SESSION["response"]->features[0]->geometry->coordinates[1];?> Lon: <?php echo $_SESSION["response"]->features[0]->geometry->coordinates[0];?></div>
        <div>City: <?php echo $_SESSION["response"]->features[0]->text;?></div>
        <div>Country: <?php echo $_SESSION["response"]->features[0]->context[count($_SESSION["response"]->features[0]->context)-1]->text;?></div>
        <div>Capital city: <?php echo $response_country[0]["capital"][0];?></div>
    </div>


</div>

</body>
<footer class="bg-light text-center text-lg-start  fixed-bottom">
    <p style="color: black">© Peter Sedláček</p>
</footer>
</html>
