<?php

class PlicometriaController{

    // GET /admin/plicometria
    static function getPlicometrie($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT plicometria.*, atleta.nome, atleta.cognome, atleta.data_nascita FROM plicometria INNER JOIN atleta ON plicometria.id_atleta=atleta.id_atleta WHERE plicometria.deleted = false ORDER BY plicometria.data_rilevazione DESC');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_plicometria' => +$entry['id_plicometria'],
                'pettorale' => +$entry['pettorale'],
                'addome' => +$entry['addome'],
                'gamba' => +$entry['gamba'],
                'percentuale' => +$entry['percentuale'],
                'data_rilevazione' => $entry['data_rilevazione'],
                'note' => $entry['note'],
                'id_atleta' => +$entry['id_atleta'],
                'nome_atleta' => $entry['nome'],
                'cognome_atleta' => $entry['cognome'],
                'data_nascita' => $entry['data_nascita'],
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/plicometria
    static function addPlicometria($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO plicometria ( id_atleta, pettorale, addome, gamba, percentuale, data_rilevazione, note ) VALUES (:id_atleta,:pettorale,:addome,:gamba,:percentuale,:data_rilevazione,:note)');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":pettorale", $body['pettorale']);
        $stm->bindValue(":addome", $body['addome']);
        $stm->bindValue(":gamba", $body['gamba']);
        $stm->bindValue(":percentuale", $body['percentuale']);
        $stm->bindValue(":data_rilevazione", $body['data_rilevazione']);
        $stm->bindValue(":note", $body['note']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Plicometria non aggiunta", "code" => 500 ]);
		}
    }

    // PUT /admin/plicometria
    static function modifyPlicometria($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE plicometria SET id_atleta=:id_atleta, pettorale=:pettorale, addome=:addome, gamba=:gamba, percentuale=:percentuale, data_rilevazione=:data_rilevazione,note=:note WHERE id_plicometria=:id_plicometria');
        $stm->bindValue(":id_plicometria", $body['id_plicometria']);
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":pettorale", $body['pettorale']);
        $stm->bindValue(":addome", $body['addome']);
        $stm->bindValue(":gamba", $body['gamba']);
        $stm->bindValue(":percentuale", $body['percentuale']);
        $stm->bindValue(":data_rilevazione", $body['data_rilevazione']);
        $stm->bindValue(":note", $body['note']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Plicometria non modificata", "code" => 500 ]);
		}
    }

    // DELETE /admin/plicometria
    static function deleteScheda($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE plicometria SET deleted=true WHERE id_plicometria=:id_plicometria');
        $stm->bindValue(":id_plicometria", $body['id_plicometria']);
        $stm->execute();
	    if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Plicometria non eliminata", "code" => 500 ]);
		}
    }
}