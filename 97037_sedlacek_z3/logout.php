<?php
session_start();
unset($_SESSION['name']);
unset($_SESSION['id']);
unset($_SESSION['alert']);
unset($_SESSION['alert2']);
header("Location: index.php");
