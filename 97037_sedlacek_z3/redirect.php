<?php
require_once "vendor/autoload.php";

$client = new Google\Client();
$client->setAuthConfig('client_secret_493688070600-2c0qtblo0250e75tcqfhkrlpr22hk8ju.apps.googleusercontent.com.json');

if (isset($_GET['code'])){
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $id = $google_account_info->getId();

    $servername = "localhost";
    $dbname = "zadanie3";
    $username = "xsedlacekp";
    $password = "VsFfwZsyaPVOUSG";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stm = $conn->prepare("SELECT name,email,users.id,google_id,accounts.id as acc_id FROM users JOIN accounts on users.id = accounts.user_id where email= :email");
        $stm->bindParam(":email",$email);
        $stm->execute();
        $user = $stm->fetch();

        if(!isset($user['email'])) {
            $stm = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stm->bindParam(":name",$name);
            $stm->bindParam(":email",$email);
            $stm->execute();
            $user_id = $conn->lastInsertId();

            $stm = $conn->prepare("INSERT INTO accounts (user_id, google_id) VALUES (".$user_id.",".$id.")");
            $stm->execute();

        }elseif(isset($user['google_id'])){

            $stm = $conn->prepare("UPDATE accounts SET google_id = :google_id WHERE user_id = :id");
            $stm->bindParam(":google_id",$id);
            $stm->bindParam(":id",$user['id']);
            $stm->execute();
        }

        $acc_id = $user['acc_id'];
        $stm = $conn->prepare("INSERT INTO logins (account_id,type ) VALUES (:acc_id,'google')");
        $stm->bindParam(":acc_id",$acc_id);
        $stm->execute();



        session_start();
        $_SESSION['name'] = $user['name'];
        $_SESSION['id'] = $acc_id;
        header("Location: dashboard.php");

    }catch (PDOException $e) {
        echo $e->getMessage();
    }
    $conn = null;

}