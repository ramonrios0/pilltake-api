<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

include_once '../../Model/medicsModel.php';
include_once '../../inc/config.php';

$db = new Config;
$db = $db -> open();
$objMedics = new Medics($db);

$api = $_SERVER['REQUEST_METHOD'];

switch ($api){
    case 'GET': 
        getMethod($objMedics);
        break;
    case 'POST':
        postMethod($objMedics);
        break;
    case 'PUT':
        putMethod($objMedics);
        break;
    case 'DELETE':
        deleteMethod($objMedics);
        break;
    default:
        http_response_code(404);
}


//Obtiene los datos de la base de datos

function getMethod($objMedics){
    $type = $_GET['type'];

    switch($type){
        case '1':
            $id = $_GET['id'];

            $stmt = $objMedics -> fetch($id);
            $success = $stmt -> rowCount();

            if($success >0){
                $result = array();
                $result['medic'] = array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                        'specialty' => $especialidad,
                        'email' => $email,
                        'contact' => $contacto
                    );

                    array_push($result['medic'], $e);
                }

                echo json_encode($result);
            }else{
                http_response_code(404);
                $result['result'] = array();
                $e = array('message' => '0');
                array_push($result['result'],$e);
                echo json_encode($result);
            }
            break;

        case '2':
            $stmt = $objMedics -> fetchAll();
            $success = $stmt -> rowCount();

            if($success >0){
                $result = array();
                $result['medics'] = array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                        'specialty' => $especialidad,
                        'email' => $email,
                        'contact' => $contacto
                    );

                    array_push($result['medics'], $e);
                }

                echo json_encode($result);
            }else{
                http_response_code(404);
                $result['result'] = array();
                $e = array('message' => '0');
                array_push($result['result'],$e);
                echo json_encode($result);
            }
            break;
        default : http_response_code(404);
    }
}

//Inserta datos en la base de datos

function postMethod($objMedics){
    parse_str(file_get_contents("php://input"),$post_vars);

    $name = $post_vars['name'];
    $email = $post_vars['email'];
    $specialty = $post_vars['specialty'];
    $contact = $post_vars['contact'];
    $pass = $post_vars['pass'];
    $token = rand(1,1000000000);

    $stmt = $objMedics -> insert($name, $pass, $email, $specialty, $contact, $token);
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

function putMethod($objMedics){
    parse_str(file_get_contents("php://input"),$put_vars);

    $name = $put_vars['name'];
    $email = $put_vars['email'];
    $specialty = $put_vars['specialty'];
    $contact = $put_vars['contact'];
    $id = $put_vars['id'];

    $stmt = $objMedics -> update($id, $name, $email, $specialty, $contact);
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

//Elimina datos en la base de datos

function deleteMethod($objMedics){
    parse_str(file_get_contents("php://input"),$delete_vars);
    
    $id = $delete_vars['id'];

    $stmt = $objMedics -> delete($id);
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

?>