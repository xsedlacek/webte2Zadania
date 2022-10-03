<?php
ini_set ('display_errors', 'on');
ini_set ('log_errors', 'on');
ini_set ('display_startup_errors', 'on');
ini_set ('error_reporting', E_ALL);
session_start();
function getUserIP()
{
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

if (!isset($_SESSION["address"])){
    $_SESSION["address"] = $_POST["address"];
}

$user_ip = getUserIP();
$url = "https://api.mapbox.com/geocoding/v5/mapbox.places/".$_SESSION["address"].".json?access_token=pk.eyJ1IjoieHNlZGxhY2VrcCIsImEiOiJjbDJodG50b2QwZ2ZyM2pwOTlyamRiaGYwIn0.BgEXKaMaKNAWZQ9fV2QyEw";

$date = new DateTime("now", new DateTimeZone('Europe/Bratislava') );

$json_data = file_get_contents($url);

$response = json_decode($json_data);
//var_dump($response->features[0]->context[1]->text);

$_SESSION["response"] = $response;

$lat = $response->features[0]->geometry->coordinates[1];
$lon = $response->features[0]->geometry->coordinates[0];

$apiKey = "3fb10da04c760ded03066ae66abb5dde";
$googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$lon."&APPID=" . $apiKey;

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);
$data = json_decode($response);
$currentTime = time();
$user_ip = getUserIP();

$url = "http://ip-api.com/json/".$user_ip;

$json_data = file_get_contents($url);

$response = json_decode($json_data);

date_default_timezone_set('Europe/Bratislava');

$current_date = date('Y-m-d H:i:s');


require_once "Database.php";

$conn = (new Database())->getConnection();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt2 = $conn->prepare("SELECT * FROM info WHERE lat LIKE :lat AND lon like :lon AND timestamp > NOW() - INTERVAL 24 HOUR;");
$stmt2->bindParam(':lat', $lat);
$stmt2->bindParam(':lon', $lon);
$stmt2->execute();
$current_user = $stmt2->fetchAll(PDO::FETCH_ASSOC);


if (sizeof($current_user) == 0){

    $stmt = $conn->prepare("INSERT INTO info (ip, lat, lon, country, country_code, timestamp ) VALUES (:ip, :lat, :lon, :country, :country_code, :timestamp)");
    $stmt->bindParam(':ip', $response->{'query'});
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lon', $lon);
    $stmt->bindParam(':country',$_SESSION["response"]->features[0]->context[count($_SESSION["response"]->features[0]->context)-1]->text);
    $stmt->bindParam(':country_code', $_SESSION["response"]->features[0]->context[count($_SESSION["response"]->features[0]->context)-1]->short_code);
    $stmt->bindParam(':timestamp', $current_date);
    $stmt->execute();
}

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
<header>

    <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
        <a href="index.php" class="navbar-brand" >Zadanie 7    Peter Sedláček</a>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="info.php">Info</a>
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
    <h2><?php echo $data->name; ?> Weather Status</h2>
    <div class="time">
        <div><?php echo date("l g:i a", $currentTime); ?></div>
        <div><?php echo date("jS F, Y",$currentTime); ?></div>
        <div><?php echo ucwords($data->weather[0]->description); ?></div>
    </div>
    <div class="weather-forecast">
        <img
            src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
            class="weather-icon" /><b> <?php echo $data->main->temp_max-273.15; ?>°C</b>
    </div>
    <div class="time">
        <div>Humidity: <?php echo $data->main->humidity; ?> %</div>
        <div>Wind: <?php echo $data->wind->speed; ?> km/h</div>
    </div>
</div>

</body>
<footer class="bg-light text-center text-lg-start  fixed-bottom">
    <p style="color: black">© Peter Sedláček</p>
</footer>
</html>
