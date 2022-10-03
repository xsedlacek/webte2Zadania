<?php
$servername = "localhost";
$dbname = "zadanie3";
$username = "xsedlacekp";
$password = "VsFfwZsyaPVOUSG";
session_start();
if (isset($_POST['mail'])){
    try{

        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stm = $conn->prepare("SELECT email FROM users WHERE email= :email");
        $stm->bindParam(":email",$_POST['mail']);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stm->fetch();

        if (isset($user['email'])){
            $_SESSION['alert2'] = true;
            header("Location: index.php");
        }
        else{

            $stm = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stm->bindParam(":name",$_POST['name']);
            $stm->bindParam(":email",$_POST['mail']);
            $stm->execute();
            $stm->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stm->fetch();
            $user_id = $conn->lastInsertId();

            $stm = $conn->prepare("INSERT INTO accounts (user_id, password) VALUES (".$user_id.", :password)");
            $pwHash = password_hash($_POST['pw'],PASSWORD_BCRYPT);
            $stm->bindParam(":password",$pwHash);
            $stm->execute();
            $stm = $conn->prepare("INSERT INTO logins (account_id) VALUES (".$conn->lastInsertId().")");
            $stm->execute();
            $acc = $stm->fetch();
            $acc_id = $conn->lastInsertId();

            session_start();
            $_SESSION['name'] = $_POST['name'];
            $_SESSION['id'] = $acc_id;
            $_SESSION['alert2'] = false;
            header("Location: dashboard.php");

        }

    }catch (PDOException $e){
        echo $e->getMessage();
    }
    $conn = null;
}


