<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

include_once '../../Model/loginModel.php';
include_once '../../inc/config.php';

$db = new Config;
$db = $db -> open();
$objLogin = new Login($db);

$api = $_SERVER['REQUEST_METHOD'];

switch ($api){
    case 'GET': 
        getMethod($objLogin);
        break;
    case 'POST':
        postMethod($objLogin);
        break;
    default:
        http_response_code(404);
}

//Login para web
function getMethod($objLogin){
    $type = $_GET['type'];
    switch($type){
        case '1': 
            $name = $_GET['name'];
            $pass = $_GET['pass'];
        
            $stmt = $objLogin -> logUser($name, $pass);
            $success = $stmt -> rowCount();
        
            if($success >0){
                $result = array();
                $result['Login'] = array();
        
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                        'type' => $tipo
                    );
        
                    array_push($result['Login'], $e);
                }
        
                echo json_encode($result);
            }else{
                http_response_code(404);
            }
            break;
        case '2':
            $mail = $_GET['mail'];
            $stmt = $objLogin -> checkMail($mail);
            $success = $stmt -> rowCount();
            if($success > 0){
                $result = array();
                $result['Reset'] = array();
                
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e= array(
                        'token' => $token,
                        'email' => $email
                        );
                    array_push($result['Reset'],$e);
                }
                echo json_encode($result);
                
            }else{
                http_response_code(404);
            }
            break;
    }
    
}

//Login para movil
function postMethod($objLogin){
    $type = $_POST['type'];
    switch($type){
        case '1':
            $name = $_POST['name'];
            $pass = $_POST['pass'];
        
            $stmt = $objLogin -> logManager($name, $pass);
            $success = $stmt -> rowCount();
        
            if($success >0){
        
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                    );
                }
        
                echo json_encode($e);
            }else{
                http_response_code(404);
            }
            break;
        case '2':
                $tokenMail = 0;
                $mail = $_POST['mail'];
                $stmt = $objLogin -> checkMailManager($mail);
                $success = $stmt -> rowCount();
                
                if($success > 0){
                    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                        extract($row);
                        $tokenMail = $token;
                    }
                    $to      = $mail;
                    $subject = 'PillTake - Cambio de contraseña';
                    $message = 'Se solicitó un cambió de contraseña para tu cuenta '.$mail.'. Si fuiste tu el que solicitó el cambio haz click en el siguiente enlace: https://pruebasrojr.000webhostapp.com/pilltake/reset.php?type=2&token='.$tokenMail.' Si no fuiste tu ignora el mensaje y no hagas nada.';
                    $headers = 'From: no-reply@pilltake.com';
                    mail($to, $subject, $message, $headers);
                    http_response_code(200);
                }
                else{
                    http_response_code(404);
                }
    }
}

?>