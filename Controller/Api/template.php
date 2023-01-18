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
        getMethod();
        break;
    case 'POST':
        postMethod();
        break;
    case 'PUT':
        putMethod();
        break;
    case 'DELETE':
        deleteMethod();
        break;
    default:
        http_response_code(404);
}


//Obtiene los datos de la base de datos

function getMethod(){
    
}

//Inserta datos en la base de datos

function postMethod(){
}

//Actualiza datos en la base de datos

function putMethod(){

}

//Elimina datos en la base de datos

function deleteMethod(){

}

?>