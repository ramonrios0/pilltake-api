<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

include_once '../../Model/intakesModel.php';
include_once '../../Model/recipesModel.php';
include_once '../../inc/config.php';

$db = new Config;
$db = $db -> open();
$objRecipes = new Recipes($db);

$api = $_SERVER['REQUEST_METHOD'];

switch ($api){
    case 'GET': 
        getMethod($objRecipes);
        break;
    case 'POST':
        postMethod($objRecipes, $db);
        break;
    case 'DELETE':
        deleteMethod($objRecipes);
        break;
    default:
        http_response_code(404);
}


//Obtiene los datos de la base de datos

function getMethod($objRecipes){
    $id = $_GET['id'];

    $stmt = $objRecipes -> fetch($id);
    $success = $stmt -> rowCount();

    if($success >0){
        $result = array();
        $result['recipe'] = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                'id' => $id,
                'patient' => $paciente,
                'medicine1' => $medicina1,
                'medicine2' => $medicina2,
                'medicine3' => $medicina3,
                'medicine4' => $medicina4,
                'time1' => $tiempo1,
                'time2' => $tiempo2,
                'time3' => $tiempo3,
                'time4' => $tiempo4,
                'quant1' => $cantidad1,
                'quant2' => $cantidad2,
                'quant3' => $cantidad3,
                'quant4' => $cantidad4,
                'start' => $inicio,
                'end' => $fin
            );

            array_push($result['recipe'], $e);
        }

        echo json_encode($result);
    }else{
        $result = array();
            $result['error'] = array();
            $e = array('message' => '0');
            array_push($result['error'],$e);
            echo json_encode($result);
    }
}

//Inserta datos en la base de datos

function postMethod($objRecipes, $db){
    parse_str(file_get_contents("php://input"),$post_vars);

    $patient = $post_vars['patient'];
    
    $stmt = $objRecipes -> fetch($patient);
    $success = $stmt -> rowCount();
    
    if($success > 0){
        $result['result'] = array();
        $e = array('message' => '2');
        array_push($result['result'],$e);
        echo json_encode($result);
    }
    else{
        $medicine1 = $post_vars['medicine1'];
        $post_vars['medicine2'] == null ? $medicine2 = null : $medicine2 = $post_vars['medicine2'];
        $post_vars['medicine3'] == null ? $medicine3 = null : $medicine3 = $post_vars['medicine3'];
        $post_vars['medicine4'] == null ? $medicine4 = null : $medicine4 = $post_vars['medicine4'];
        $time1 = $post_vars['time1'];
        $post_vars['time2'] == null ? $time2 = null : $time2 = $post_vars['time2'];
        $post_vars['time3'] == null ? $time3 = null : $time3 = $post_vars['time3'];
        $post_vars['time4'] == null ? $time4 = null : $time4 = $post_vars['time4'];
        $quant1 = $post_vars['quant1'];
        $post_vars['quant2'] == null ? $quant2 = null : $quant2 = $post_vars['quant2'];
        $post_vars['quant3'] == null ? $quant3 = null : $quant3 = $post_vars['quant3'];
        $post_vars['quant4'] == null ? $quant4 = null : $quant4 = $post_vars['quant4'];
        $start = $post_vars['start'];
        $end = $post_vars['end'];
    
        $stmt = $objRecipes -> insert($patient, $medicine1, $medicine2, $medicine3, $medicine4, $time1, $time2, $time3, $time4, $quant1, $quant2, $quant3, $quant4, $start, $end);
        $success = $stmt -> rowCount();
        
        
        if($success > 0){
            //Al ser algo relacionado se insertan tambien datos a la base de datos de ingestas desde este controlador.
            $objIntakes = new Intakes($db);
    
            $stmt2 = $objIntakes -> insert($patient, $medicine1, $time1, $quant1, $start, $end);
            $success2 = $stmt2 -> rowCount();
    
            if($medicine2 != null){
                $stmt3 = $objIntakes -> insert($patient, $medicine2, $time2, $quant2, $start, $end);
                $success2 = $stmt3 -> rowCount();
            }
    
            if($medicine3 != null){
                $stmt4 = $objIntakes -> insert($patient, $medicine3, $time3, $quant3, $start, $end);
                $success2 = $stmt4 -> rowCount();
            }
    
            if($medicine4 != null){
                $stmt5 = $objIntakes -> insert($patient, $medicine4, $time4, $quant4, $start, $end);
                $success2 = $stmt5 -> rowCount();
            }
            if($success2 > 0){
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
            
        }else{
            $result['result'] = array();
            $e = array('message' => '0');
            array_push($result['result'],$e);
            echo json_encode($result);
            http_response_code(404);
        }
    }
}

//Elimina datos en la base de datos

function deleteMethod($objRecipes){
    parse_str(file_get_contents("php://input"),$delete_vars);
    
    $id = $delete_vars['id'];

    $stmt = $objRecipes -> delete($id);
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