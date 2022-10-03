<?php
ini_set ('display_errors', 'on');
ini_set ('log_errors', 'on');
ini_set ('display_startup_errors', 'on');
ini_set ('error_reporting', E_ALL);
require_once "../Database.php";

$conn = (new Database())->getConnection();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$parameters = 0;

if (isset($_GET['country'])){
    $country = $_GET['country'];
    $newarraynama = rtrim($country, "/ ");
    $stmt = $conn->prepare("SELECT * from info where country= :country ");

    $stmt->bindParam(":country", $newarraynama);
    $stmt->execute();
    $parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
$stmt2 = $conn->prepare("SELECT Count(country) FROM info WHERE country = :country");
$stmt2->bindParam(":country", $newarraynama);
$stmt2->execute();
$countries = $stmt2->fetchAll(PDO::FETCH_ASSOC);


?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://wt119.fei.stuba.sk/zadanie_7/style.css">
    <title>Info logs</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
        <a href="https://site153.webte.fei.stuba.sk/zadanie7" class="navbar-brand" >Zadanie 7    Peter Sedláček</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link" href="https://site153.webte.fei.stuba.sk/zadanie7/home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://site153.webte.fei.stuba.sk/zadanie7/info.php">Info</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="https://site153.webte.fei.stuba.sk/zadanie7/data.php">Map <span class="sr-only">(current)</span></a>
                </li>

            </ul>
        </div>
    </nav>
</header>
<div class="card" style="width: 70%; margin-left: 15%;padding: 3em;">

    <div class="container">

        <?php
        for ($i = 0; $i <= $countries[0]['Count(country)'] - 1; $i++) {
            echo "<p>".$i+1 ." visitor</p>";
            echo "<p>IP:". $parameters[$i]['ip']."</p>";
            echo "<p>Lat:".$parameters[$i]["lat"]." Lon: ". $parameters[$i]["lon"]."</p>";
            echo "<p>UTC Time:". $parameters[$i]["timestamp"]."</p> <br>";

        }


        ?>

    </div>
</div>

</body>
</html>
