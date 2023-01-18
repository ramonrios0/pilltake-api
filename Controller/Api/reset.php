<?php

if(!isset($_GET['token']) || !isset($_GET['type'])  || !isset($_GET['password'])){
    http_response_code(404);
}else{

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: X-Requested-With');
    header('Content-Type: application/json');

    include_once '../../Model/resetModel.php';
    include_once '../../inc/config.php';

    $db = new Config;
    $db = $db -> open();
    $objReset = new ResetPassword($db);
    $type = $_GET['type'];
    $password = $_GET['password'];
    $token = $_GET['token'];

    switch($type){
        case '1':
            $stmt = $objReset -> resetMedic($token, $password);
            $success = $stmt -> rowCount();
            if($success > 0){
                $result = array();
                $result['Reset'] = array();
                $e = array('msg' => 'success');
                array_push($result['Reset'],$e);
                echo json_encode($result);
            }else { 
                $result = array();
                $result['Reset'] = array();
                $e = array('msg' => 'error');
                array_push($result['Reset'],$e);
                echo json_encode($result);
            }
            break;
        case '2':
            $stmt = $objReset -> resetManager($token, $password);
            $success = $stmt -> rowCount();
            if($success > 0){
                $result = array();
                $result['Reset'] = array();
                $e = array('msg' => 'success');
                array_push($result['Reset'],$e);
                echo json_encode($result);
            }else { 
                $result = array();
                $result['Reset'] = array();
                $e = array('msg' => 'error');
                array_push($result['Reset'],$e);
                echo json_encode($result);
            }
    }
}
?>