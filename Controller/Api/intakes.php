<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

include_once '../../Model/intakesModel.php';
include_once '../../inc/config.php';

$db = new Config;
$db = $db -> open();
$objIntakes = new Intakes($db);

$api = $_SERVER['REQUEST_METHOD'];

switch ($api){
    case 'GET': 
        getMethod($objIntakes);
        break;
    case 'POST':
        postMethod($objIntakes);
        break;
    case 'PUT':
        putMethod($objIntakes);
        break;
    case 'DELETE':
        deleteMethod($objIntakes);
        break;
    default:
        http_response_code(404);
}


//Obtiene los datos de la base de datos

function getMethod($objIntakes){
    $type = $_GET['type'];

    //Tipo 1 es para web
    //Tipo 2 es para movil
    switch($type){
        case '1':
            $id = $_GET['id'];
            $stmt = $objIntakes -> fetch($id);
            $success = $stmt -> rowCount();

            if($success >0){
                $result = array();
                $result['intakes'] = array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'id' => $id,
                        'medicine' => $medicina,
                        'taken' => $ingerido,
                        'patient' => $paciente,
                        'time' => $tiempo,
                        'intakeTime' => $tiempoingerido
                    );

                    array_push($result['intakes'], $e);
                }

                echo json_encode($result);
            }else{
                $result['error'] = array();
                $e = array('message' => '0');
                array_push($result['error'],$e);
                echo json_encode($result);
            }
            break;

        case '2':
            $manager = $_GET['id'];

            $stmt = $objIntakes -> fetchTaken($manager);
            $success = $stmt -> rowCount();

            $result['intakes'] = array();
            $result['remaining'] = array();

            if($success > 0){
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'name' => $nombre,
                        'idPaciente' => $idPaciente,
                        'medicine' => $medicina,
                        'taken' => $ingerido,
                        'time' => $tiempo,
                        'idIngesta' => $idIngesta
                    );
                    array_push($result['intakes'], $e);
                }
            }

            $stmt = $objIntakes -> fetchRemaining($manager);
            $success = $stmt -> rowCount();

            if($success > 0){
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        'name' => $nombre,
                        'idPaciente' => $idPaciente,
                        'medicine' => $medicina,
                        'taken' => $ingerido,
                        'time' => $tiempo,
                        'idIngesta' => $idIngesta
                    );
                    array_push($result['remaining'], $e);
                }
                echo json_encode($result);
            } else{
                $result['error'] = array();
                $e= array('message' => '0');
                array_push($result['error'],$e);
                echo json_encode($result);
            }
            break;
        case '3':
            $id = $_GET['id'];
            $taken = '-1';
            $time = '';
        
            $stmt = $objIntakes -> update($id, $taken, $time);
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
            break;        
        default: http_response_code(404);    
    }
}

//Inserta datos en la base de datos

function postMethod($objIntakes){
    $patient = $_POST['patient'];
    $medicine1 = $_POST['medicine1'];
    $medicine2 = $_POST['medicine2'];
    $medicine3 = $_POST['medicine3'];
    $medicine4 = $_POST['medicine4'];
    $time1 = $_POST['time1'];
    $time2 = $_POST['time2'];
    $time3 = $_POST['time3'];
    $time4 = $_POST['time4'];
    $quant1 = $_POST['quant1'];
    $quant2 = $_POST['quant2'];
    $quant3 = $_POST['quant3'];
    $quant4 = $_POST['quant4'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $stmt = $objIntakes -> insert($patient, $medicine1, $time1, $quant1, $start, $end);
    $success = $stmt -> rowCount();

    if($medicine2 != null){
        $stmt2 = $objIntakes -> insert($patient, $medicine2, $time2, $quant2, $start, $end);
        $success = $stmt2 -> rowCount();
    }

    if($medicine3 != null){
        $stmt3 = $objIntakes -> insert($patient, $medicine3, $time3, $quant3, $start, $end);
        $success = $stmt3 -> rowCount();
    }

    if($medicine4 != null){
        $stmt4 = $objIntakes -> insert($patient, $medicine4, $time4, $quant4, $start, $end);
        $success = $stmt4 -> rowCount();
    }

    $result['success'] = array();

    if ($success > 0) {
        $e = array('message' => '1');
        array_push($result, $e);
        echo json_encode($result);
    }
    else {
        $e = array('message' => '0');
        array_push($result, $e);
        echo json_encode($result);
    }
}

//Actualiza los datos de una entrada en la base de datos

function putMethod($objIntakes){
    parse_str(file_get_contents("php://input"),$put_vars);

    $id = $put_vars['id'];
    $taken = $put_vars['taken'];
    $time = $put_vars['time'];

    $stmt = $objIntakes -> update($id, $taken, $time);
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

function deleteMethod(){

}

?>