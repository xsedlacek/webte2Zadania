<?php
session_start();
if (!isset($_SESSION['name'])){
    header("Location: index.php");
}
$servername = "localhost";
$dbname = "zadanie3";
$username = "xsedlacekp";
$password = "VsFfwZsyaPVOUSG";

if (isset($_SESSION['id'])){
    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stm = $conn->prepare("SELECT created_at, type FROM logins WHERE account_id = :id");
        $stm->bindParam(":id",$_SESSION['id']);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $login = $stm->fetchAll();

        $stm = $conn->prepare("SELECT DISTINCT account_id FROM logins WHERE type = 'google'");
        $stm->execute();
        $google = count($stm->fetchAll());

        $stm = $conn->prepare("SELECT DISTINCT account_id FROM logins WHERE type = 'classic'");
        $stm->execute();
        $classic = count($stm->fetchAll());

    }catch (PDOException $e){
        echo $e->getMessage();
    }
    $conn = null;
}

?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    <title>Dashboard</title>
</head>
<body>
<div class="top-right">
    <a href="logout.php" style="text-decoration:none">
        <button class="button-35" role="button">Odhlásenie</button>
    </a>
</div>
<div class ="nadpis">
    <div class="hlavny-nadpis">
        <h1>
            🔹 Ahoj 🔹
        </h1>
    </div>

    <div class="podnadpis">
        <h2>
            <?php echo $_SESSION['name']; ?>
        </h2>
    </div>

</div>


<div class="login-table-div">
    <div class="logins">
        <button class="button-40" role="button" id="show-table">Minulé prihlásenia</button>
    </div>
    <div>
        <table style="display: none" class="sortable" id="table">
            <thead >
            <tr class="active-row">
                <td>Typ prihlásenia</td>
                <td>Čas</td>
            </tr>
            </thead>
            <tbody>
            <?php
            if (isset($login)){
                foreach ($login as $log){?>
                    <tr class="active-row">
                        <td><?php echo $log['type']; ?></td>
                        <td><?php echo $log['created_at']; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>

            </tbody>
        </table>
        <p style="display: none;color:white" id="p1">Prihlásených pomocou registrácie: <?php echo $classic?> </p>
        <p style="display: none;color: white" id="p2">Prihlásených pomocou účtu Google: <?php echo $google?> </p>
    </div>
</div>


<script>
    const button = document.getElementById("show-table");
    button.addEventListener('click',()=>{
        document.getElementById("table").style.display ="block";
        document.getElementById("p1").style.display ="block";
        document.getElementById("p2").style.display ="block";
    })
</script>
</body>
</html>
