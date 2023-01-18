<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

include_once '../../Model/medicinesModel.php';
include_once '../../inc/config.php';

$db = new Config;
$db = $db -> open();
$objMedicines = new Medicines($db);

$api = $_SERVER['REQUEST_METHOD'];

switch ($api){
    case 'GET': 
        getMethod($objMedicines);
        break;
    case 'POST':
        postMethod($objMedicines);
        break;
    default:
        http_response_code(404);
}


//Obtiene los datos de la base de datos

function getMethod($objMedicines){
        $stmt = $objMedicines -> fetchAll();
        $success = $stmt -> rowCount();

        if($success >0){
            $result = array();
            $result['medicines'] = array();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $e = array(
                    'id' => $id,
                    'name' => $nombre,
                );

                array_push($result['medicines'], $e);
            }
            echo json_encode($result);

        }else{
            http_response_code(404);
            echo json_encode(
                array('message' => '0')
            );
        }
}

//Inserta datos en la base de datos

function postMethod($objMedicines){
    parse_str(file_get_contents("php://input"),$post_vars);
    $name = $post_vars['name'];

    $stmt = $objMedicines -> insert($name);
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