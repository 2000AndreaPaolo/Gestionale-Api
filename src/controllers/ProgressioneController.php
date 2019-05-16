<?php

class ProgressioneController{

    // GET /admin/progressione
    static function getProgressione($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT progressione.id_progressione, progressione.serie, progressione.ripetizioni, progressione.note, esercizio.id_esercizio, esercizio.nome FROM progressione INNER JOIN esercizio ON progressione.id_esercizio = esercizio.id_esercizio WHERE progressione = false');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_progressione' => +$entry['id_progressione'],
                'serie' => $entry['serie'],
                'ripetizioni' => $entry['ripetizioni'],
                'note' => $entry['note'],
                'id_esercizio' => +$entry['id_esercizio'],
                'nome' => $entry['nome']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/progressione
    static function addProgressione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO progressione ( serie, ripetizioni, id_esercizio, note ) VALUES (:serie,:ripetizioni, :id_esercizio, :note)');
        $stm->bindValue(":serie", $body['serie']);
        $stm->bindValue(":ripetizioni", $body['ripetizioni']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":note", $body['note']);
	    if($stm->execute()){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Progressione non aggiunta", "code" => 500 ]);
		}
    }

    // PUT /admin/progressione
    static function modifyProgressione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE progressione SET serie=:serie, ripetizioni=:ripetizioni, note=:note, id_esercizio=:id_esercizio WHERE id_progressione=:id_progressione');
        $stm->bindValue(":id_progressione", $body['id_progressione']);
        $stm->bindValue(":serie", $body['serie']);
        $stm->bindValue(":ripetizioni", $body['ripetizioni']);
        $stm->bindValue(":note", $body['note']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Progressione non modificata", "code" => 500 ]);
		}
    }

    // DELETE /admin/progressione
    static function deleteProgressione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE progressione SET deleted=true WHERE id_progressione=:id_progressione');
        $stm->bindValue(":id_progressione", $body['id_progressione']);
        $stm->execute();
	    if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Progressione non eliminata", "code" => 500 ]);
		}
    }
}