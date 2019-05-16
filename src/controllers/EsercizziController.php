<?php

class EsercizziController{

    // GET /admin/esercizio
    static function getEsercizzi($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT esercizio.id_esercizio, esercizio.descrizione, gruppoMuscolare.descrizione AS descrizioneMuscolare, gruppoMuscolare.id_gruppoMuscolare FROM esercizio INNER JOIN gruppoMuscolare ON esercizio.id_gruppoMuscolare = gruppoMuscolare.id_gruppoMuscolare WHERE esercizio.deleted=false');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_esercizio' => +$entry['id_esercizio'],
                'id_gruppoMuscolare' => +$entry['id_gruppoMuscolare'],
                'descrizione' => $entry['descrizione'],
                'gruppoMuscolare' => $entry['descrizioneMuscolare']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/esercizio
    static function addEsercizio($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO esercizio ( descrizione, id_gruppoMuscolare ) VALUES (:descrizione,:id_gruppoMuscolare)');
        $stm->bindValue(":descrizione", $body['descrizione']);
        $stm->bindValue(":id_gruppoMuscolare", $body['id_gruppoMuscolare']);
	    if($stm->execute()){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Esercizio non aggiunto", "code" => 500 ]);
		}
    }

    // PUT /admin/esercizio
    static function modifyEsercizio($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE esercizio SET descrizione=:descrizione, id_gruppoMuscolare=:id_gruppoMuscolare WHERE id_esercizio=:id_esercizio');
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":descrizione", $body['descrizione']);
        $stm->bindValue(":id_gruppoMuscolare", $body['id_gruppoMuscolare']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Esercizio non modificato", "code" => 500 ]);
		}
    }

    // DELETE /admin/esercizio
    static function deleteEsercizio($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE esercizio SET deleted=true WHERE id_esercizio=:id_esercizio');
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->execute();
	    if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Esercizio non eliminato", "code" => 500 ]);
		}
    }

    // GET /admin/gruppomuscolare
    static function getGruppoMuscolare($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT id_gruppoMuscolare, descrizione FROM gruppoMuscolare WHERE deleted=false');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_gruppoMuscolare' => +$entry['id_gruppoMuscolare'],
                'descrizione' => $entry['descrizione']
            ];
        }, $dbres);

        $res->json($data);
    }
}