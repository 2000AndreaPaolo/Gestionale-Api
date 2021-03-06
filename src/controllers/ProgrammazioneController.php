<?php

class ProgrammazioneController{

    // GET /admin/programmazione
    static function getProgrammazione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('SELECT programmazione.*, esercizio.descrizione, programmazione.note AS note_programmazione,programma.* FROM programmazione INNER JOIN esercizio ON programmazione.id_esercizio = esercizio.id_esercizio INNER JOIN programma ON programmazione.id_programma=programma.id_programma WHERE programmazione.deleted=false AND programma.id_programma=:id_programma ORDER BY programmazione.data ASC, programmazione.id_programmazione ASC');
        $stm->bindValue(":id_programma", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_programmazione' => +$entry['id_programmazione'],
                'id_programma' => +$entry['id_programma'],
                'id_esercizio' => +$entry['id_esercizio'],
                'data' => $entry['data'],
                'serie' => +$entry['serie'],
                'ripetizioni' => +$entry['ripetizioni'],
                'carico' => +$entry['carico'],
                'note' => $entry['note_programmazione'],
                'nome_esercizio' => $entry['descrizione']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/programmazione
    static function addProgrammazione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO programmazione ( id_programma, id_esercizio, data, serie, ripetizioni, carico, note ) VALUES (:id_programma,:id_esercizio,:data,:serie,:ripetizioni,:carico,:note)');
        $stm->bindValue(":id_programma", $body['id_programma']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":data", $body['data']);
        $stm->bindValue(":serie", $body['serie']);
        $stm->bindValue(":ripetizioni", $body['ripetizioni']);
        $stm->bindValue(":carico", $body['carico']);
        $stm->bindValue(":note", $body['note']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Programmazione non aggiunta", "code" => 500 ]);
		}
    }

    // PUT /admin/programmazione
    static function modifyProgrammazione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE programmazione SET id_programma=:id_programma, id_esercizio=:id_esercizio, data=:data, serie=:serie, ripetizioni=:ripetizioni, carico=:carico, note=:note, data=:data WHERE id_programmazione=:id_programmazione');
        $stm->bindValue(":id_programmazione", $body['id_programmazione']);
        $stm->bindValue(":id_programma", $body['id_programma']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":settimana", $body['data']);
        $stm->bindValue(":serie", $body['serie']);
        $stm->bindValue(":ripetizioni", $body['ripetizioni']);
        $stm->bindValue(":carico", $body['carico']);
        $stm->bindValue(":note", $body['note']);
        $stm->bindValue(":data", $body['data']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Programmazione non modificata", "code" => 500 ]);
		}
    }

    // DELETE /admin/programmazione
    static function deleteProgrammazione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE programmazione SET deleted=true WHERE id_programmazione=:id_programmazione');
        $stm->bindValue(":id_programmazione", $body['id_programmazione']);
        $stm->execute();
	    if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Programmazione non eliminats", "code" => 500 ]);
		}
    }

    // POST /atleta/programmazione
    static function getProgrammazioneGiorno($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('SELECT programmazione.*, esercizio.descrizione FROM programmazione INNER JOIN esercizio ON programmazione.id_esercizio = esercizio.id_esercizio INNER JOIN programma ON programma.id_programma=programmazione.id_programma WHERE programmazione.deleted=false AND programmazione.data=CURDATE() AND programma.id_programma=:id_programma ORDER BY programmazione.id_programmazione ASC, programmazione.data ASC');
        $stm->bindValue(":id_programma", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_programmazione' => +$entry['id_programmazione'],
                'id_esercizio' => +$entry['id_esercizio'],
                'data' => date("d-m-Y", strtotime($entry['data'])),
                'serie' => +$entry['serie'],
                'ripetizioni' => +$entry['ripetizioni'],
                'carico' => +$entry['carico'],
                'note' => $entry['note'],
                'nome_esercizio' => $entry['descrizione']
            ];
        }, $dbres);

        $res->json($data);
    }
}