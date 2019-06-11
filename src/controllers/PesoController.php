<?php

class PesoController{

    // GET /admin/peso
    static function getPeso($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT peso.*, atleta.nome, atleta.cognome FROM peso INNER JOIN atleta ON atleta.id_atleta=peso.id_atleta WHERE peso.deleted=false ORDER BY peso.data DESC');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_peso' => +$entry['id_peso'],
                'id_atleta' => +$entry['id_atleta'],
                'peso' => +$entry['peso'],
                'data' => date("d-m-Y", strtotime($entry['data'])),
                'note' => $entry['note'],
                'nome_atleta' => $entry['nome'],
                'cognome_atleta' => $entry['cognome']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/peso
    static function addPeso($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO peso ( id_atleta, peso, note ) VALUES (:id_atleta,:peso,:note)');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":peso", $body['peso']);
        $stm->bindValue(":note", $body['note']);
	    if($stm->execute()){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Peso non aggiunto", "code" => 500 ]);
		}
    }

    // PUT /admin/peso
    static function modifyPeso($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE peso SET id_atleta=:id_atleta, peso=:peso, note=:note WHERE id_peso=:id_peso');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":peso", $body['peso']);
        $stm->bindValue(":note", $body['note']);
        $stm->bindValue(":id_peso", $body['id_peso']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Peso non modificato", "code" => 500 ]);
		}
    }

    // DELETE /admin/peso
    static function deletePeso($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE peso SET deleted=true WHERE id_peso=:id_peso');
        $stm->bindValue(":id_peso", $body['id_peso']);
        $stm->execute();
	    if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Peso non eliminato", "code" => 500 ]);
		}
    }

    // POST /atelta/peso
    static function getPesoAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('SELECT peso.* FROM peso WHERE peso.deleted=false AND peso.id_atleta=:id_atleta ORDER BY peso.data DESC');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_peso' => +$entry['id_peso'],
                'peso' => +$entry['peso'],
                'data' => date("d-m-Y", strtotime($entry['data'])),
                'note' => $entry['note']
            ];
        }, $dbres);

        $res->json($data);
    }
}