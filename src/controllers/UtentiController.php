<?php

class UtentiController{

    // GET /admin/atleta
    static function getAtleti($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT id_atleta, nome, cognome, data_nascita,username FROM atleta WHERE deleted=false');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_atleta' => +$entry['id_atleta'],
                'nome' => $entry['nome'],
                'cognome' => $entry['cognome'],
                'data_nascita' => $entry['data_nascita'],
                'username' => $entry['username']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/atleta
    static function addAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $username = $body['nome'].'.'.$body['cognome'];
        $password = $body['nome'].'.'.$body['cognome'];
        $stm = $app->db->prepare('INSERT INTO atleta ( nome, cognome, username, password, data_nascita ) VALUES (:nome, :cognome, :username, :password, :data_nascita)');
        $stm->bindValue(":nome", $body['nome']);
        $stm->bindValue(":cognome", $body['cognome']);
        $stm->bindValue(":username", $username);
        $stm->bindValue(":password", $password);
        $stm->bindValue(":data_nascita", $body['data_nascita']);
	    if($stm->execute()){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Atleta non aggiunto", "code" => 500, "error" => $stm->errorInfo() ]);
		}
    }

    // PUT /admin/atleta
    static function modifyAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $username = $body['nome'].'.'.$body['cognome'];
        $stm = $app->db->prepare('UPDATE atleta SET nome=:nome, cognome=:cognome, username=:username, data_nascita=:data_nascita WHERE id_atleta=:id_atleta');
        $stm->bindValue(":nome", $body['nome']);
        $stm->bindValue(":cognome", $body['cognome']);
        $stm->bindValue(":username", $username);
        $stm->bindValue(":data_nascita", $body['data_nascita']);
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Atleta non modificato", "code" => 500 ]);
		}
    }

    // DELETE /admin/atleta
    static function deleteAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE atleta SET deleted=true WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->execute();
	    if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Atleta non eliminato", "code" => 500 ]);
		}
    }
}