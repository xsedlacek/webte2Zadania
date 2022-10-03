<?php
include "config.php";
if (isset($path)) {
    if (isset($_POST['title']) && isset($_FILES['fileToUpload'])) {
        $ext = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
        $filename = $path . $_POST['title'] . "." . $ext;
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filename);
    }


}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File upload</title>
    <script src="sorttable.js"></script>
    <style>
        h1{
            text-align: center;
            color: darkgreen;
            font-family: Bahnschrift, serif;
            font-size: xxx-large;
        }
        h2{
            text-align: center;
        }
        h3{
            font-size: x-large;
            margin-left: 32%;
        }
        .sortable {
            margin-left: 20%;
            border-collapse: collapse;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }
        .sortable thead tr {
            background-color: #c2fbd7;
            color: green;
            text-align: left;
        }
        .sortable th,
        .sortable td {
            padding: 12px 15px;
        }
        .sortable tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .sortable tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .sortable tbody tr:last-of-type {
            border-bottom: 2px solid #c2fbd7;
        }
        .sortable tbody tr.active-row {
            font-weight: bold;
            color: #000000;
        }


        table.sortable thead {
            background-color:#c2fbd7;
            color:green;
            font-weight: bold;
            cursor: pointer;
        }
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 20px;
        }
        .form1{
            margin-left: 30%;
            font-size: x-large;
            font-family: sans-serif;

        }
        .button-33 {
            margin-left: 20%;
            margin-top: 16px;
            background-color: #c2fbd7;
            border-radius: 100px;
            box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,rgba(44, 187, 99, .15) 0 1px 2px,rgba(44, 187, 99, .15) 0 2px 4px,rgba(44, 187, 99, .15) 0 4px 8px,rgba(44, 187, 99, .15) 0 8px 16px,rgba(44, 187, 99, .15) 0 16px 32px;
            color: green;
            cursor: pointer;
            display: inline-block;
            font-family: CerebriSans-Regular,-apple-system,system-ui,Roboto,sans-serif;
            padding: 15px 23px;
            text-align: center;
            text-decoration: none;
            transition: all 250ms;
            border: 0;
            font-size: 16px;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .button-33:hover {
            box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,rgba(44,187,99,.25) 0 1px 2px,rgba(44,187,99,.25) 0 2px 4px,rgba(44,187,99,.25) 0 4px 8px,rgba(44,187,99,.25) 0 8px 16px,rgba(44,187,99,.25) 0 16px 32px;
            transform: scale(1.05) rotate(-1deg);
        }


    </style>
</head>
<body>
<h1>
    File upload
</h1>
<div class="grid-container">
    <div class="grid-child purple">
        <h3>Obsah adresára</h3>
        <table class = "sortable">
            <thead>
            <tr>
                <th>Názov</th>
                <th>Velkosť</th>
                <th>Dátum nahratia</th>
            </tr>

            </thead>
            <tbody>
            <?php

            if (isset($path)){
                $files = (scandir($path));

                foreach ($files as $file){
                    if (is_dir($path.$file)){
                        if (basename($path) == "files"){
                            if ($file != ".."){ ?>
                                <tr class="active-row">
                                    <td><a href="?path=<?php echo $path.$file."/" ?>"><?php echo $file ?></a></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php }
                        }else{ ?>
                            <tr class="active-row">
                                <td><a href="?path=<?php echo $path.$file."/" ?>"><?php echo $file ?></a></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                        }
                    }else{


                        ?>
                        <tr class="active-row">
                            <td><?php echo $file ?></td>
                            <td><?php echo filesize($path.$file)."B" ?></td>
                            <td><?php echo date("d.m.y h:i:s", filemtime($path.$file))?></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class="grid-child green">
        <h2>Upload formulár</h2>
        <form action="index.php" enctype="multipart/form-data" method="post" class="form1">
            <div><label for="title">Názov súboru: </label><input type="text" id="title" name="title"></div>
            <div><label for="file">Súbor: </label><input name="fileToUpload" type="file"></div>
            <button type="submit" class="button-33" role="button">Upload</button>
        </form>
    </div>

</div>

</body>
</html>