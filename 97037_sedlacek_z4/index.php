<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//FIIT FOOD RESTAURACIA1
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "http://www.freefood.sk/menu/#fiit-food");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

$dom = new DOMDocument();

@$dom->loadHTML($output);
$dom->preserveWhiteSpace = false;
$div = $dom->getElementById('fiit-food');

$rows = $div->getElementsByTagName('li');
//echo "<pre>";
//var_dump($rows->item(1));
//echo "</pre>";
$index = 1;
$dayCount = 0;

$foodCount = 4;

$foods = [
    ["date"  => date( 'd.m.Y', strtotime( 'monday this week' ) ), "day" => "Pondelok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'tuesday this week' ) ), "day" => "Utorok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'wednesday this week' ) ), "day" => "Streda", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'thursday this week' ) ), "day" => "Štvrtok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'friday this week' ) ), "day" => "Piatok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'saturday this week' ) ), "day" => "Sobota", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'sunday this week' ) ), "day" => "Nedeľa", "menu" => []],
];

for ($i =0;$i<5;$i++){

    array_push($foods[$dayCount]["menu"], $rows->item($index)->nodeValue);
    array_push($foods[$dayCount]["menu"], $rows->item($index+1)->nodeValue);
    array_push($foods[$dayCount]["menu"], $rows->item($index+2)->nodeValue);
    array_push($foods[$dayCount]["menu"], $rows->item($index+3)->nodeValue);

    $index += $foodCount+1;
    $dayCount++;
}

$fp = fopen('./storage/fiit.json', 'w');
fwrite($fp, json_encode($foods));
fclose($fp);

//EAT AND MEET RESTAURACIA2
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "http://eatandmeet.sk/tyzdenne-menu");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

$dom = new DOMDocument();

@$dom->loadHTML($output);
$dom->preserveWhiteSpace = false;

$parseNodes = ["day-1", "day-2", "day-3", "day-4", "day-5", "day-6", "day-7"];

$jedla = [
    ["date"  => date( 'd.m.Y', strtotime( 'monday this week' ) ), "day" => "Pondelok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'tuesday this week' ) ), "day" => "Utorok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'wednesday this week' ) ), "day" => "Streda", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'thursday this week' ) ), "day" => "Štvrtok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'friday this week' ) ), "day" => "Piatok", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'saturday this week' ) ), "day" => "Sobota", "menu" => []],
    ["date"  => date( 'd.m.Y', strtotime( 'sunday this week' ) ), "day" => "Nedeľa", "menu" => []],
];

foreach ($parseNodes as $index => $nodeId) {

    $node = $dom->getElementById($nodeId);

    foreach ($node->childNodes as $menuItem)
    {
        if($menuItem && $menuItem->childNodes->item(1) && $menuItem->childNodes->item(1)->childNodes->item(3)){
            $nazov = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue);
            $cena = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(3)->nodeValue);
            $popis = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(3)->nodeValue);
            array_push($jedla[$index]["menu"], "$nazov ($popis): $cena");
        }
    }
}

$fp = fopen('./storage/eat.json', 'w');
fwrite($fp, json_encode($jedla));
fclose($fp);

//DELIKANTI RESTAURACIA3
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "https://www.delikanti.sk/prevadzky/3-jedalen-prif-uk/");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);


@$dom->loadHTML($output);
$dom->preserveWhiteSpace = false;
$tables = $dom->getElementsByTagName('table');

$rows = $tables->item(0)->getElementsByTagName('tr');
$index = 0;
$dayCount = 0;

$foods = [];
$foodCount = $rows->item(0)->getElementsByTagName('th')->item(0)->getAttribute('rowspan');

foreach ($rows as $row) {

    if($row->getElementsByTagName('th')->item(0)){
        $foodCount = $row->getElementsByTagName('th')->item(0)->getAttribute('rowspan');

        $day = trim($rows->item($index)->getElementsByTagName('th')->item(0)->getElementsByTagName('strong')->item(0)->nodeValue);

        $th = $rows->item($index)->getElementsByTagName('th')->item(0);

        foreach($th->childNodes as $node)
            if(!($node instanceof \DomText))
                $node->parentNode->removeChild($node);

        $date = trim($rows->item($index)->getElementsByTagName('th')->item(0)->nodeValue);


        array_push($foods, ["date" => $date, "day" => $day, "menu" => []]);

        for($i = $index; $i <  $index + intval($foodCount); $i++)
        {
            if($foods[$dayCount])
                array_push($foods[$dayCount]["menu"], trim($rows->item($i)->getElementsByTagName('td')->item(1)->nodeValue));
        }
        $index += intval($foodCount);
        $dayCount++;
    }

}

$fp = fopen('./storage/delikanti.json', 'w');
fwrite($fp, json_encode($foods));
fclose($fp);

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Jedalnicek</title>
</head>
<body>
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

</body>
</html>
