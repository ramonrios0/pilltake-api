<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

include_once '../../Model/patientsModel.php';
include_once '../../inc/config.php';

$db = new Config;
$db = $db -> open();
$objPatients = new Patients($db);

$api = $_SERVER['REQUEST_METHOD'];

switch ($api){
    case 'GET': 
        getMethod($objPatients);
        break;
    case 'POST':
        postMethod($objPatients);
        break;
    case 'DELETE':
        deleteMethod($objPatients);
        break;
    default:
        http_response_code(404);
}


//Obtiene los datos de la base de datos

function getMethod($objPatients){
    $type = $_GET['type'];

    //Tipo 1 es para el medico
    //Tipo 2 es para el encargado
    switch($type){
        case '1':
            $medic = $_GET['id'];

            $stmt = $objPatients -> fetchAllMedic($medic);
            $success = $stmt -> rowCount();

            if($success >0){
                $result['patients'] = array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                        'manager' => $encargado,
                        'contact' => $contacto
                    );

                    array_push($result['patients'], $e);
                }

                echo json_encode($result);
            }else{
                http_response_code(404);
                $result['error'] = array();
                $e = array('message' => '0');
                array_push($result['error'],$e);
                echo json_encode($result);
            }
            break;
        case '2':
            $manager = $_GET['id'];

            $stmt = $objPatients -> fetchAllManager($manager);
            $success = $stmt -> rowCount();

            if($success >0){
                $result['patients'] = array();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'name' => $nombre,
                        'start' => $inicio,
                        'end' => $fin
                    );

                    array_push($result['patients'], $e);
                }

                echo json_encode($result);
            }else{
                http_response_code(404);
                $result['error'] = array();
                $e = array('message' => '0');
                array_push($result['error'],$e);
                echo json_encode($result);
            }
            break;
        default: 
            http_response_code(404);
    }
}

//Inserta datos en la base de datos

function postMethod($objPatients){
    parse_str(file_get_contents("php://input"),$post_vars);
    $name = $post_vars['name'];
    $medic = $post_vars['medic'];
    $manager = $post_vars['manager'];

    $stmt = $objPatients -> insert($name, $medic, $manager);
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

function deleteMethod($objPatients){
    parse_str(file_get_contents("php://input"),$delete_vars);
    
    $id = $delete_vars['id'];

    $stmt = $objPatients -> delete($id);
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