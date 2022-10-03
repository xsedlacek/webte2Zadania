<?php
require_once "../Inventor.php";
require_once "../Invention.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');
switch ($_SERVER['REQUEST_METHOD']) {

    case "POST":
        header("HTTP/1.1 201 OK");
        $data = json_decode(file_get_contents('php://input'), true);
        if ($_POST['inventionDate']&&$_POST['desc']&&$_POST['birthDate']){
            $inventor = new Inventor();
            $inventor->setName($_POST['name']);
            $inventor->setSurname($_POST['lastName']);
            $inventor->setDescription($_POST['description']);
            $inventor->setBirthDate($_POST["birthDate"]);
            $inventor->setBirthPlace($_POST['birthPlace']);
            if ($_POST['deathDate']){
                $inventor->setDeathDate($_POST["deathDate"]);
            }
            $inventor->setDeathPlace($_POST['deathPlace']);
            $inventor->save();
            $invention = new Invention();
            $invention->setInventionDate($_POST['inventionDate']);
            $invention->setDescription($_POST['desc']);
            $invention->setInventorId($inventor->getId());
            $invention->save();
            header('X-PHP-Response-Code: 200', true, 200);
        }elseif($_POST['inv_id']&&$_POST["inv_date"]&&$_POST['inv_desc']){
            $invention = new Invention();
            $invention->setInventionDate($_POST['inv_date']);
            $invention->setDescription($_POST['inv_desc']);
            $invention->setInventorId($_POST['inv_id']);
            $invention->save();
            header('X-PHP-Response-Code: 200', true, 200);
            break;
        }
        header('X-PHP-Response-Code: 400', true, 400);
        break;
    case "DELETE":
        $id = $_GET['id'];
        if (Inventor::find($id)){
            Inventor::find($id)->destroy();
            header('X-PHP-Response-Code: 204', true, 204);
        }else{
            header('X-PHP-Response-Code: 400', true, 400);
        }
        break;
    case "GET":
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if ($id) {
                if (Inventor::find($id)) {
                    echo json_encode(Inventor::find($id)->toArray());
                    header('X-PHP-Response-Code: 200', true, 200);
                } else {
                    header('X-PHP-Response-Code: 400', true, 400);
                }
            }else{
                header('X-PHP-Response-Code: 200', true, 200);
                echo json_encode(Inventor::all());
            }
            break;
        }
        if (isset($_GET['surname'])){
            $surname = $_GET['surname'];
            if($surname){
                if (Inventor::findBySurname($surname)){
                    echo json_encode(Inventor::findBySurname($surname)->toArray());
                    header('X-PHP-Response-Code: 200', true, 200);
                }else{
                    header('X-PHP-Response-Code: 400', true, 400);
                }

            }
            break;
        }
        if (isset($_GET['century'])){
            $century = $_GET['century'];
            if($century){
                if (Invention::findByCentury($century)){
                    echo json_encode(Invention::findByCentury($century));
                    header('X-PHP-Response-Code: 200', true, 200);
                }else{
                    header('X-PHP-Response-Code: 400', true, 400);
                }
            }
            break;
        }
        if (isset($_GET['year'])){
            $year = $_GET['year'];
            if($year){
                if (Inventor::findByYear($year)){
                    echo json_encode(Inventor::findByYear($year));
                    header('X-PHP-Response-Code: 200', true, 200);
                }else{
                    header('X-PHP-Response-Code: 400', true, 400);
                }

            }
            break;
        }





}
