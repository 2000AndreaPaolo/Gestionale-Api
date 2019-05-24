<?php

require_once __DIR__ . '/../helpers.php';

class AuthController {
    static function adminLogin($req, $res, $service, $app){
        $parameters = $req->body();
        $parameters = json_decode($parameters, true);
        $stm = $app->db->prepare('SELECT * FROM coach WHERE username=:username AND password=:password');
        $stm->bindValue(":username", $parameters['username']);
        $stm->bindValue(":password", $parameters['password']);
        $stm->execute();
        if($stm->rowCount() > 0){
            $var = $stm->fetchAll(PDO::FETCH_ASSOC);
            $data = array_map(function($entry){
                return [
                    'id_coach' => +$entry['id_coach'],
                    'nome' => $entry['nome'],
                    'cognome' => $entry['cognome'],
                    'token' => getJwt(['id_coach' => +$entry['id_coach']])
                ];
            }, $var);
            $res->json($data[0]);
        }else{
            $res->json(["message" => "Username o Password errati", "code" => 401]);
        }
    }
}