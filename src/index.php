<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, token');
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

//Admin - auth
$klein->respond('POST', '/admin/auth', ['AuthController', 'adminLogin']);
$klein->respond('POST', '/admin/atleta/resetpassword', ['AuthController', 'resetPasswordClienti']);

//Admin - atleta
$klein->respond('POST', '/admin/get/atleta', ['UtentiController', 'getAtleti']);
$klein->respond('POST', '/admin/atleta', ['UtentiController', 'addAtleta']);
$klein->respond('PUT', '/admin/atleta', ['UtentiController', 'modifyAtleta']);
$klein->respond('DELETE', '/admin/atleta', ['UtentiController', 'deleteAtleta']);

//Admin - specializzazione
$klein->respond('GET', '/admin/specializzazione', ['UtentiController', 'getSpecializzazione']);

//Admin - esercizio
$klein->respond('POST', '/admin/get/esercizio', ['EsercizziController', 'getEsercizzi']);
$klein->respond('POST', '/admin/esercizio', ['EsercizziController', 'addEsercizio']);
$klein->respond('PUT', '/admin/esercizio', ['EsercizziController', 'modifyEsercizio']);
$klein->respond('DELETE', '/admin/esercizio', ['EsercizziController', 'deleteEsercizio']);

//Admin - scheda
$klein->respond('POST', '/admin/get/scheda', ['SchedaController', 'getSchede']);
$klein->respond('POST', '/admin/scheda', ['SchedaController', 'addScheda']);
$klein->respond('PUT', '/admin/scheda', ['SchedaController', 'modifyScheda']);
$klein->respond('DELETE', '/admin/scheda', ['SchedaController', 'deleteScheda']);

//Admin - progressione
$klein->respond('POST', '/admin/get/progressione', ['ProgressioneController', 'getProgressione']);
$klein->respond('POST', '/admin/progressione', ['ProgressioneController', 'addProgressione']);
$klein->respond('PUT', '/admin/progressione', ['ProgressioneController', 'modifyProgressione']);
$klein->respond('DELETE', '/admin/progressione', ['ProgressioneController', 'deleteProgressione']);

//Admin - plicometria
$klein->respond('POST', '/admin/get/plicometria', ['PlicometriaController', 'getPlicometrie']);
$klein->respond('POST', '/admin/plicometria', ['PlicometriaController', 'addPlicometria']);
$klein->respond('PUT', '/admin/plicometria', ['PlicometriaController', 'modifyPlicometria']);
$klein->respond('DELETE', '/admin/plicometria', ['PlicometriaController', 'deletePlicometria']);
$klein->respond('POST', '/admin/plicometria/last', ['PlicometriaController', 'lastPlicometria']);

//Admin - programmazione
$klein->respond('POST', '/admin/get/programmazione', ['ProgrammazioneController', 'getProgrammazione']);
$klein->respond('POST', '/admin/programmazione', ['ProgrammazioneController', 'addProgrammazione']);
$klein->respond('PUT', '/admin/programmazione', ['ProgrammazioneController', 'modifyProgrammazione']);
$klein->respond('DELETE', '/admin/programmazione', ['ProgrammazioneController', 'deleteProgrammazione']);

//Admin - programma
$klein->respond('POST', '/admin/get/programma', ['ProgrammaController', 'getProgramma']);
$klein->respond('POST', '/admin/programma', ['ProgrammaController', 'addProgramma']);
$klein->respond('PUT', '/admin/programma', ['ProgrammaController', 'modifyProgramma']);
$klein->respond('DELETE', '/admin/programma', ['ProgrammaController', 'deleteProgramma']);

//Admin - peso
$klein->respond('POST', '/admin/get/peso', ['PesoController', 'getPeso']);
$klein->respond('POST', '/admin/peso', ['PesoController', 'addPeso']);
$klein->respond('PUT', '/admin/peso', ['PesoController', 'modifyPeso']);
$klein->respond('DELETE', '/admin/peso', ['PesoController', 'deletePeso']);
$klein->respond('POST', '/admin/peso/last', ['PesoController', 'lastPeso']);

//Admin - prestazione
$klein->respond('POST', '/admin/get/prestazione', ['PrestazioneController', 'getPrestazione']);
$klein->respond('POST', '/admin/prestazione', ['PrestazioneController', 'addPrestazione']);
$klein->respond('PUT', '/admin/prestazione', ['PrestazioneController', 'modifyPrestazione']);
$klein->respond('DELETE', '/admin/prestazione', ['PrestazioneController', 'deletePrestazione']);
$klein->respond('POST', '/admin/prestazione/massimale', ['PrestazioneController', 'getMassimale']);

//Admin - note
$klein->respond('post', '/admin/get/note', ['NoteController', 'getNote']);
$klein->respond('POST', '/admin/note', ['NoteController', 'addNote']);
$klein->respond('PUT', '/admin/note', ['NoteController', 'modifyNote']);
$klein->respond('DELETE', '/admin/note', ['NoteController', 'deleteNote']);
$klein->respond('POST', '/admin/note/last', ['NoteController', 'lastNote']);

//Admin - cestino
$klein->respond('POST', '/admin/deleted/atleti/get', ['CestinoController', 'getAtleti']);
$klein->respond('POST', '/admin/deleted/atleti/restore', ['CestinoController', 'restoreAtleti']);

//Atleta - auth
$klein->respond('POST', '/atleta/auth', ['AuthController', 'atletaLogin']);
$klein->respond('POST', '/atleta/password', ['AuthController', 'changePasswordClienti']);

//Atleta - programma
$klein->respond('POST', '/atleta/programma', ['ProgrammaController', 'getProgrammaAtleta']);

//Atleta - programmazione
$klein->respond('POST', '/atleta/programmazione/giorno', ['ProgrammazioneController', 'getProgrammazioneGiorno']);

//Atleta - scheda
$klein->respond('POST', '/atleta/scheda', ['SchedaController', 'getSchedaAtleta']);

//Atleta - progressione
$klein->respond('POST', '/atleta/progressione', ['ProgressioneController', 'getProgressioneAtleta']);

//Atleta - note
$klein->respond('POST', '/atleta/note', ['NoteController', 'getNoteAtleta']);

//Atleta - peso
$klein->respond('POST', '/atleta/peso', ['PesoController', 'getPesoAtleta']);

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
