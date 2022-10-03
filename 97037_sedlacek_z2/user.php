<?php
if (isset($_GET['search'])){

    $servername = "localhost";
    $dbname = "glosar";
    $username = "xsedlacekp";
    $password = "VsFfwZsyaPVOUSG";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        if(isset($_GET['fulltext'])){
            if (isset($_GET['translate'])){
                $stm = $conn->prepare("SELECT t1.title as searchTitle,
                                                t1.description as searchDescription,
                                                t2.title,
                                                t2.description
                                        FROM Translations t1
                                        JOIN Translations t2
                                            on t1.word_id = t2.word_id 
                                            
                                        JOIN languages 
                                            on t1.language_id = languages.id 
                                        WHERE 
                                            languages.code = :language and
                                            t1.title like :search and 
                                            t1.id <> t2.id or languages.code = :language and
                                            t1.description like :search and 
                                            t1.id <> t2.id 
                                            ");
            }else
            {

                $stm = $conn->prepare("SELECT Translations.title as searchTitle,
                                                Translations.description as searchDescription                   
                                        FROM Translations 
                                        JOIN languages 
                                            on Translations.language_id = languages.id 
                                        WHERE 
                                            languages.code = :language and
                                            Translations.title like :search or 
                                            languages.code = :language and
                                            Translations.description like :search
                                              
                                                ");

            }

        }else{
            if (isset($_GET['translate'])){

                $stm = $conn->prepare("SELECT t1.title as searchTitle,
                                                t1.description as searchDescription,
                                                t2.title,
                                                t2.description
                                        FROM Translations t1
                                        JOIN Translations t2
                                            on t1.word_id = t2.word_id 
                                            
                                        JOIN languages 
                                            on t1.language_id = languages.id 
                                        WHERE 
                                            languages.code = :language and
                                            t1.title like :search and 
                                            t1.id <> t2.id
                                            ");
            }else
            {

                $stm = $conn->prepare("SELECT Translations.title as searchTitle,
                                                Translations.description as searchDescription                   
                                        FROM Translations 
                                        JOIN languages 
                                            on Translations.language_id = languages.id 
                                        WHERE 
                                            languages.code = :language and
                                            Translations.title like :search 
                                                ");

            }
        }


        $search = "%".$_GET['search']."%";
        $stm->bindParam(":search",$search);
        $stm->bindParam(":language",$_GET['language_code']);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stm->fetchAll();
    }catch (PDOException $e){
        echo $e->getMessage();
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
    <title>Glosar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="index.php">
    <button class="button-33" role="button">Domov</button>
</a>
<div class="container">
    <h1>Stránka používateľa</h1>
    <form action="user.php" method="get">
        <div>
            <label for="search">Hladanie: </label>
            <input type="text" name="search" id="search">
        </div>
        <div>
            <label for="language">Vyberte jazyk hľadania</label>
            <select name="language_code" id="language">
                <option value="sk">SK</option>
                <option value="en">EN</option>
            </select>
            <div>
                <label for="translate">Preložiť</label>
                <input type="checkbox" name="translate" id="translate">
                <br>
                <label for="fulltext">Fulltextové vyhľadávanie</label>
                <input type="checkbox" name="fulltext" id="fulltext">
            </div>

        </div>
        <input type="submit" value="Hladaj" class="button-33">
    </form>
</div>

<table class="sortable">
    <thead>
    <tr>
        <th>Pojem</th>
        <th>Vysvetlenie</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($result as $item) {
        ?>
        <tr>
            <td><?php echo $item['searchTitle'] ?></td>
            <td><?php echo $item['searchDescription'] ?></td>
        </tr>
    <?php

    }
    ?>
    </tbody>
</table>
<?php if (isset($_GET['translate'])){
    ?>
<table class="sortable">
    <thead>
    <tr>
        <th>Term</th>
        <th>Explanation</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($result as $item) {
        ?>
        <tr>
            <td><?php echo $item['title'] ?></td>
            <td><?php echo $item['description'] ?></td>
        </tr>
    <?php
    }
}
?>


    </tbody>
</table>
</body>

</html>
