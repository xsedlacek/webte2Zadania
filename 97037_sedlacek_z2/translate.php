<?php
header("Content-type:application/json; charset=utf-8");
if (isset($_GET['search'])){

    $servername = "localhost";
    $dbname = "glosar";
    $username = "xsedlacekp";
    $password = "VsFfwZsyaPVOUSG";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $stm = $conn->prepare("SELECT t1.title as searchTitle,
                                                t1.description as searchDescription,
                                                t2.title,
                                                t2.description,
                                                t1.word_id
                                        FROM Translations t1
                                        JOIN Translations t2
                                            on t1.word_id = t2.word_id 
                                            
                                        JOIN languages 
                                            on t1.language_id = languages.id 
                                        WHERE 
                                            languages.code = :language and
                                            t1.title like :search and 
                                            t1.id <> t2.id ");
        $search = "%".$_GET['search']."%";
        $stm->bindParam(":search",$search);
        $stm->bindParam(":language",$_GET['language_code']);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stm->fetchAll();
        echo json_encode($result);


    }catch (PDOException $e){
        echo $e->getMessage();
    }
}




