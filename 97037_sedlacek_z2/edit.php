<?php
header("Content-type:application/json; charset=utf-8");
$data = json_decode(file_get_contents("php://input"),true);
if (isset($data)){

    $servername = "localhost";
    $dbname = "glosar";
    $username = "xsedlacekp";
    $password = "VsFfwZsyaPVOUSG";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $stm = $conn->prepare(" UPDATE Translations SET description= :desc_sk where word_id = :id and language_id = 1
        
        ");

        $stm->bindParam(":id",$data['id'],PDO::PARAM_INT);
        $stm->bindParam(":desc_sk",$data['desc_sk']);
        $stm->execute();

        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $stm = $conn->prepare(" UPDATE Translations SET description= :desc_en where word_id = :id and language_id = 2
        
        ");

        $stm->bindParam(":id",$data['id'],PDO::PARAM_INT);
        $stm->bindParam(":desc_en",$data['desc_en']);
        $stm->execute();
        $result = ["edited" =>true , "message" =>"edited"];
        echo json_encode($result);


    }catch (PDOException $e){
        $result = ["edited" =>false , "message" => $e->getMessage()];
        echo json_encode($result);
    }
}
