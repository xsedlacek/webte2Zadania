<?php
require_once "vendor/autoload.php";
session_start();
$client = new Google\Client();
$client->setAuthConfig('client_secret_493688070600-2c0qtblo0250e75tcqfhkrlpr22hk8ju.apps.googleusercontent.com.json');
$redirect_uri = "https://site153.webte.fei.stuba.sk/zadanie3/redirect.php";
$client->addScope("email");
$client->addScope("profile");
$client->setRedirectUri($redirect_uri);

?>
<!doctype html>
<html lang="sk">
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />

</head>
<body>
<?php
var_dump($_SESSION);
if ($_SESSION['alert']){
echo "
<script type=\"text/javascript\">
     document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('alert').style.display = 'block';
     });
    </script>
";

}else{
    echo "
<script type=\"text/javascript\">
     document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('alert').style.display = 'none';
     });
    </script>
";
}
if ($_SESSION['alert2']){
    echo "
<script type=\"text/javascript\">
     document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('alert2').style.display = 'block';
     });
    </script>
";

}else{
    echo "
<script type=\"text/javascript\">
     document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('alert2').style.display = 'none';
     });
    </script>
";
}
?>
<div class="alert" id="alert" style="display: none" >
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    Zadali ste nesprávne meno alebo heslo,zaregistrujte sa alebo skúste znovu.
</div>
<div class="alert" id="alert2" style="display: none" >
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    Zadaný mail už v systéme existuje, zadajte iný alebo sa prihláste.
</div>

<div class="main">
    <input type="checkbox" id="chk" aria-hidden="true">

    <div class="signup">
        <form method="post" action="register.php">
            <label for="chk" aria-hidden="true"> Registrácia</label>
            <input type="text" name="name" placeholder="Meno" required>
            <input type="email" name="mail" placeholder="Email" required>
            <input type="password" name="pw" placeholder="Heslo"  required>
            <input type="submit" value="Registruj" role="button" id="button">
        </form>
    </div>

    <div class="login">
        <form action="login.php" method="post">
            <label for="chk" aria-hidden="true">Prihlásenie</label>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Heslo" required>
            <input type="submit" value="Login" role="button" id="button">
            <a href="<?php echo $client->createAuthUrl() ?>">
            <div class="google-btn">
                <div class="google-icon-wrapper">
                    <img class="google-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"/>
                </div>
                <p class="btn-text">
                     <b>Sign in with google</b>
                </p>
            </div>
            </a>

        </form>
    </div>
</div>

</body>
</html>