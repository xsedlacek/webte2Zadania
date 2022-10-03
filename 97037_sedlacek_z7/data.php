<?php
ini_set ('display_errors', 'on');
ini_set ('log_errors', 'on');
ini_set ('display_startup_errors', 'on');
ini_set ('error_reporting', E_ALL);


require_once "Database.php";

$conn = (new Database())->getConnection();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt = $conn->prepare("SELECT * FROM info;");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$users = json_encode($users);


$stmt2 = $conn->prepare("SELECT DISTINCT country, country_code FROM info;");
$stmt2->execute();
$countries = $stmt2->fetchAll();


$stmt3 = $conn->prepare("SELECT count(ip) as ip FROM info WHERE country LIKE :country;");


$stmt4 = $conn->prepare("SELECT timestamp as time2 FROM info;");
$stmt4->execute();
$users2 = $stmt4->fetchAll(PDO::FETCH_ASSOC);




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
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <title>Map</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
        <a href="index.php" class="navbar-brand" >Zadanie 7    Peter Sedláček</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="info.php">Info</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="data.php">Map <span class="sr-only">(current)</span></a>
                </li>

            </ul>
        </div>
    </nav>
</header>

<body>
<div class="card" style="width: 70%; margin-left: 15%;padding: 3em;">

    <div class="container">
        <table class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Flag</th>
                <th scope="col">Country</th>
                <th scope="col">Counter</th>

            </tr>
            </thead>
            <tbody>

        <?php
        $poc = 0;
            for ($i = 0; $i <= sizeof($countries) - 1; $i++) {

                $stmt3->bindParam(':country', $countries[$i][0]);
                $stmt3->execute();
                $country_ip = $stmt3->fetch(PDO::FETCH_ASSOC);
                $url = 'https://site153.webte.fei.stuba.sk/zadanie7/flag';

                $url =$url. '?country=' .$countries[$i][0] ;
                echo "<tr><td><a href='".$url."/'><img  width='100px' src='https://www.geonames.org/flags/x/".strtolower($countries[$i][1]).".gif'></a></td>";
                echo "<td>".$countries[$i][0]."</td>";
                echo "<td>".$country_ip["ip"]."</td></tr>";
                $poc = $poc + $country_ip["ip"];


            }
        ?>

            </tbody>
        </table>
    </div>
    <div class="container">
        <h1>Time is set to Europe/Bratislava</h1>
        <table class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">6:00-15:00</th>
                <th scope="col">15:00-21:00</th>
                <th scope="col">21:00-24:00</th>
                <th scope="col">24:00-6:00</th>


            </tr>
            </thead>
            <tbody>
                <?php
                $t1=0;
                $t2=0;
                $t3=0;
                $t4=0;
                for ($i = 0; $i <= $poc - 1; $i++) {
                    $date = $users2[$i]['time2'];
                    $date = strtotime($date);

                    $hour = date('H', $date);
                    //echo $hour. "<br> ";
                    if (($hour>=4)&&($hour<13)){
                        $t1 = $t1+1;
                    }
                    if (($hour>=13)&&($hour<19)){
                        $t2 = $t2+1;
                    }
                    if (($hour>=19)&&($hour<22)){
                        $t3 = $t3+1;
                    }
                    if (($hour>=22)&&($hour<4)){
                        $t4 = $t4+1;
                    }


                }

                echo "<tr><td>".$t1."</td>";
                echo "<td>".$t2."</td>";
                echo "<td>".$t3."</td>";
                echo "<td>".$t4."</td></tr>";
                ?>
            </tbody>
        </table>
    </div>


    <div id="googleMap" style="width:100%;height:650px;"></div>

    <script>

        function myMap() {
            var mapProp= {
                center:new google.maps.LatLng(51.508742,-0.120850),
                zoom:2,
            };

            var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);


            json = <?php echo $users;?>;
            var users = JSON.parse(JSON.stringify(json));

            for(var i = 0; i < users.length; i++){
                var pos = { lat: parseFloat(users[i]['lat']), lng: parseFloat(users[i]['lon'])};
                addMarker(pos, map);
            }

        }

        function addMarker(location, map) {
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }





    </script>
</body>

<footer class="bg-light text-center text-lg-start  fixed-bottom">
    <p style="color: black">© Peter Sedláček</p>
</footer>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFEMOjlV9rvwWSGhZ2vBt4Ros-KcRvS8Y&callback=myMap"></script>
</html>
