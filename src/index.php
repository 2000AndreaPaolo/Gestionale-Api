<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Credentials: true');

foreach (glob("controllers/*Controller.php") as $filename)
    require_once $filename;

$klein = new \Klein\Klein();

/*
 * DATABASE
 ***************/

$klein->respond(function ($request, $response, $service, $app) {
    $app->register('db', function() {
        return new PDO('mysql:host=database;dbname=gestionale', 'gestionale', 'gestionale');
    });
});

/*
 * ROUTES
 ***************/

$klein->respond('GET', '/', ['IndexController', 'get']);

//Admin - atleta
$klein->respond('GET', '/admin/atleta', ['UtentiController', 'getAtleti']);
$klein->respond('POST', '/admin/atleta', ['UtentiController', 'addAtleta']);
$klein->respond('PUT', '/admin/atleta', ['UtentiController', 'modifyAtleta']);
$klein->respond('DELETE', '/admin/atleta', ['UtentiController', 'deleteAtleta']);

//Admin - esercizio
$klein->respond('GET', '/admin/esercizio', ['EsercizziController', 'getEsercizzi']);
$klein->respond('POST', '/admin/esercizio', ['EsercizziController', 'addEsercizio']);
$klein->respond('PUT', '/admin/esercizio', ['EsercizziController', 'modifyEsercizio']);
$klein->respond('DELETE', '/admin/esercizio', ['EsercizziController', 'deleteEsercizio']);

//Admin - gruppo muscolare
$klein->respond('GET', '/admin/gruppomuscolare', ['EsercizziController', 'getGruppoMuscolare']);

//Admin - scheda
$klein->respond('GET', '/admin/scheda', ['SchedaController', 'getSchede']);
$klein->respond('POST', '/admin/scheda', ['SchedaController', 'addScheda']);
$klein->respond('PUT', '/admin/scheda', ['SchedaController', 'modifyScheda']);
$klein->respond('DELETE', '/admin/scheda', ['SchedaController', 'deleteScheda']);

//Admin - progressione
$klein->respond('GET', '/admin/progressione', ['ProgressioneController', 'getProgressione']);
$klein->respond('POST', '/admin/progressione', ['ProgressioneController', 'addProgressione']);
$klein->respond('PUT', '/admin/progressione', ['ProgressioneController', 'modifyProgressione']);
$klein->respond('DELETE', '/admin/progressione', ['ProgressioneController', 'deleteProgressione']);

/*
 * UTILS
 ****************/

// Match all endpoints to add Content-Type header
$klein->respond(function($req, $res) {
    $res->header('Content-Type', 'application/json');
});

// handle errors
$klein->onHttpError(function ($code, $router) {
    if($code == 404)
        $router->response()->body(json_encode(['error' => 'Not Found', 'code' => 404]));
    elseif ($code >= 400 && $code < 500)
        $router->response()->body(json_encode(['error' => 'User error', 'code' => $code]));
    elseif ($code >= 500 && $code <= 599)
        $router->response()->body(json_encode(['error' => 'Internal Server Error', 'code' => $code]));
});

$klein->dispatch();
