<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Jedálniček</title>
</head>
<body>
<?php
$json = file_get_contents('./storage/delikanti.json');
$arr = json_decode($json);
$delikanti = get_object_vars($arr[4]);
$json = file_get_contents('./storage/eat.json');
$arr = json_decode($json);
$eat = get_object_vars($arr[4]);
$json = file_get_contents('./storage/fiit.json');
$arr = json_decode($json);
$fiit = get_object_vars($arr[4]);
//var_dump($delikanti);
//var_dump($fiit);

?>
<header>
    <div class="page">
        <nav class="page__menu menu">
            <ul class="menu__list r-list">
                <li class="menu__group"><a href="pondelok.php" class="menu__link r-link text-underlined">Pondelok</a></li>
                <li class="menu__group"><a href="utorok.php" class="menu__link r-link text-underlined">Utorok</a></li>
                <li class="menu__group"><a href="streda.php" class="menu__link r-link text-underlined">Streda</a></li>
                <li class="menu__group"><a href="stvrtok.php" class="menu__link r-link text-underlined">Štvrtok</a></li>
                <li class="menu__group"><a href="piatok.php" class="menu__link r-link text-underlined">Piatok</a></li>
                <li class="menu__group"><a href="sobota.php" class="menu__link r-link text-underlined">Sobota</a></li>
                <li class="menu__group"><a href="nedela.php" class="menu__link r-link text-underlined">Nedeľa</a></li>
            </ul>
        </nav>
    </div>
</header>



<div class="container">
    <div class="date">
        <?php
        echo $eat['day']." ".$eat['date'];

        ?>
    </div>
    <div class="restaurant">
        <h2>Eat and Meet</h2>
        <div><?php
            for ($i = 0;$i<10;$i++){
                echo $eat['menu'][$i]."\n";
                echo "<br>";
            }
            ?></div>
    </div>
    <div class="restaurant">
        <h2>Delikanti</h2>
        <div><?php
            for ($i = 0;$i<6;$i++){
                echo $delikanti['menu'][$i]."\n";
                echo "<br>";
            }
            ?></div>

    </div>
    <div class="restaurant">
        <h2>FIIT Food</h2>
        <div class="fiit"><?php
            for ($i = 0;$i<4;$i++){
                echo $fiit['menu'][$i]."\n";
                echo "<br>";
            }
            ?></div>

    </div>
</div>

</body>
</html>
