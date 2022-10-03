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
        $stm = $conn->prepare(" DELETE FROM words where id= :id
        
        ");

        $stm->bindParam(":id",$data['id'],PDO::PARAM_INT);
        $stm->execute();
        $result = ["deleted" =>true , "message" =>"deleted"];
        echo json_encode($result);


    }catch (PDOException $e){
        $result = ["deleted" =>false , "message" => $e->getMessage()];
        echo json_encode($result);
    }
}
