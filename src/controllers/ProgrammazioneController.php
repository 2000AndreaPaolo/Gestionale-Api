<?php

class ProgrammazioneController{

    // GET /admin/programmazione
    static function getProgrammazione($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT programmazione.*, esercizio.descrizione FROM programmazione INNER JOIN esercizio ON programmazione.id_esercizio = esercizio.id_esercizio WHERE programmazione.deleted=false ORDER BY programmazione.giorno ASC, programmazione.id_programmazione ASC');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_programmazione' => +$entry['id_programmazione'],
                'id_programma' => +$entry['id_programma'],
                'id_esercizio' => +$entry['id_esercizio'],
                'giorno' => +$entry['giorno'],
                'serie' => +$entry['serie'],
                'ripetizioni' => +$entry['ripetizioni'],
                'carico' => +$entry['carico'],
                'note' => $entry['note'],
                'nome_esercizio' => $entry['descrizione']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/programma
    static function addProgrammazione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO programmazione ( id_programma, id_esercizio, giorno, serie, ripetizioni, carico, note ) VALUES (:id_programma,:id_esercizio,:giorno,:ripetizioni,:serie,:carico,:note)');
        $stm->bindValue(":id_programma", $body['id_programma']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":giorno", $body['giorno']);
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

    // PUT /admin/programma
    static function modifyProgrammazione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE programmazione SET id_programma=:id_programma, id_esercizio=:id_esercizio, giorno=:giorno, serie=:serie, ripetizioni=:ripetizioni, carico=:carico, note=:note WHERE id_programmazione=:id_programmazione');
        $stm->bindValue(":id_programmazione", $body['id_programmazione']);
        $stm->bindValue(":id_programma", $body['id_programma']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":giorno", $body['giorno']);
        $stm->bindValue(":serie", $body['serie']);
        $stm->bindValue(":ripetizioni", $body['ripetizioni']);
        $stm->bindValue(":carico", $body['carico']);
        $stm->bindValue(":note", $body['note']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Programmazione non modificata", "code" => 500 ]);
		}
    }

    // DELETE /admin/programma
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
}