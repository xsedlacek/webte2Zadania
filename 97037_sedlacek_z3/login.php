<?php
$servername = "localhost";
$dbname = "zadanie3";
$username = "xsedlacekp";
$password = "VsFfwZsyaPVOUSG";
session_start();
if (isset($_POST['email'])){
    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stm = $conn->prepare("SELECT email FROM users WHERE email= :email");
        $stm->bindParam(":email",$_POST['email']);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stm->fetch();

        if (isset($user['email'])) {
            $stm = $conn->prepare("SELECT name,password,user_id,accounts.id as account FROM users JOIN accounts on users.id = accounts.user_id WHERE email= :email");
            $stm->bindParam(":email", $_POST['email']);
            $stm->execute();
            $stm->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stm->fetch();

            if (password_verify($_POST['password'], $user['password'])) {
                $stm = $conn->prepare("INSERT INTO logins (account_id) VALUES(" . $user['account'] . ")");
                $stm->execute();
                $_SESSION['name'] = $user['name'];
                $_SESSION['id'] = $user['account'];
                $_SESSION['alert'] = false;
                header("Location: dashboard.php");

            } else {
                $_SESSION['alert'] = true;
                header("Location: index.php");
            }

        }else{
                $_SESSION['alert'] = true;
                header("Location: index.php");
            }




    }catch (PDOException $e){
        echo $e->getMessage();
    }
    $conn = null;
}
