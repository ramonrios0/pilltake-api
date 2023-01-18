<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

include_once '../../Model/managersModel.php';
include_once '../../inc/config.php';
include_once '../../Model/medicsModel.php';

$db = new Config;
$db = $db -> open();
$objManagers = new Managers($db);

$api = $_SERVER['REQUEST_METHOD'];

switch ($api){
    case 'GET': 
        getMethod($objManagers, $db);
        break;
    case 'POST':
        postMethod($objManagers);
        break;
    case 'PUT':
        putMethod($objManagers);
        break;
    case 'DELETE':
        deleteMethod($objManagers);
        break;
    default:
        http_response_code(404);
}


//Obtiene los datos de la base de datos

function getMethod($objManagers, $db){
    $type = $_GET['type'];

    switch($type){
        case '1' :
            $id = $_GET['id'];
            $stmt = $objManagers -> fetch($id);
            $success = $stmt -> rowCount();
    
            if($success >0){
                $result = array();
                $result['manager'] = array();
                
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                        'email' => $email,
                        'contact' => $contacto
                    );
                    
                    array_push($result['manager'], $e);
                }
                
                echo json_encode($result);
            }else{
                http_response_code(404);
                echo json_encode(
                    array('message' => '0')
                );
            }
            break;
        case '2' :
            $stmt = $objManagers -> fetchAll();
            $success = $stmt -> rowCount();

            if($success >0){
                $result = array();
                $result['managers'] = array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                        'email' => $email,
                        'contact' => $contacto
                    );

                    array_push($result['managers'], $e);
                }

                echo json_encode($result);
            }else{
                http_response_code(404);
                echo json_encode(
                    array('message' => '0')
                );
            }
            break;
        case '3' :
            $id = $_GET['id'];

            $stmt = $objManagers -> fetchRelated($id);
            $success = $stmt -> rowCount();

            if($success >0){
                $result = array();
                $result['manager'] = array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                        'email' => $email,
                        'contact' => $contacto
                    );

                    array_push($result['manager'], $e);
                }

                echo json_encode($result);
            }else{
                http_response_code(404);
                echo json_encode(
                    array('message' => '0')
                );
            }
            break;
        case '4': 
            $id = $_GET['id'];
            $medicID = 0;

            $stmt = $objManagers -> fetch($id);
            $success = $stmt -> rowCount();

            $result = array();
            $result['manager'] = array();
            $result['medic'] = array();

            if($success > 0){
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'name' => $nombre,
                        'email' => $email,
                        'contact' => $contacto,
                    );
                    $medicID = $medico;
                    array_push($result['manager'], $e);
                }
            }

            $objMedic = new Medics($db);
            $stmt = $objMedic -> fetch($medicID);
            $success = $stmt -> rowCount();

    if($success > 0){
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                'name' => $nombre,
                'email' => $email,
                'contact' => $contacto
            );
            array_push($result['medic'], $e);

            echo json_encode($result);
        }
    }else{
        http_response_code(404);
        $result['result'] = array();
        $msg = array('message' => '0');
        $error = array_push($result['result'], $msg);
        echo json_encode($error);
    }
            break;
        default : http_response_code(404);
    }
}

//Inserta datos en la base de datos

function postMethod($objManagers){
    parse_str(file_get_contents("php://input"),$post_vars);
    $name = $post_vars['name'];
    $email = $post_vars['email'];
    $contact = $post_vars['contact'];
    $pass = $post_vars['pass'];
    $medic = $post_vars['medic'];
    $token = rand(1,1000000000);

    $stmt = $objManagers -> insert($name, $pass, $email, $contact, $medic, $token);
    $success = $stmt -> rowCount();
    
    if($success >0){
        $result['result'] = array();
        $e = array('message' => '1');
        array_push($result['result'],$e);
        echo json_encode($result);
    }else{
        $result['result'] = array();
        $e = array('message' => '0');
        array_push($result['result'],$e);
        echo json_encode($result);
        http_response_code(404);
    }
}

//Actualiza datos en la base de datos

function putMethod($objManagers){
    parse_str(file_get_contents("php://input"),$put_vars);

    $name = $put_vars['name'];
    $email = $put_vars['email'];
    $contact = $put_vars['contact'];
    $id = $put_vars['id'];

    $stmt = $objManagers -> update($id, $name, $email, $contact);
    $success = $stmt -> rowCount();

    if($success > 0){
        $result['result'] = array();
        $e = array('message' => '1');
        array_push($result['result'],$e);
        echo json_encode($result);
    }else{
        $result['result'] = array();
        $e = array('message' => '0');
        array_push($result['result'],$e);
        echo json_encode($result);
        http_response_code(404);
    }
}

//Elimina datos en la base de datos

function deleteMethod($objManagers){
    parse_str(file_get_contents("php://input"),$delete_vars);
    
    $id = $delete_vars['id'];

    $stmt = $objManagers -> delete($id);
    $success = $stmt -> rowCount();

    if($success > 0){
        $result['result'] = array();
        $e = array('message' => '1');
        array_push($result['result'],$e);
        echo json_encode($result);
    }else{
        $result['result'] = array();
        $e = array('message' => '0');
        array_push($result['result'],$e);
        echo json_encode($result);
        http_response_code(404);
    }
}

?>