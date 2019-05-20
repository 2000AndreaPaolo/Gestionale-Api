<?php

class ProgressioneController{

    // GET /admin/progressione
    static function getProgressione($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT progressione.id_scheda, progressione.id_progressione, progressione.giorno, progressione.serie, progressione.ripetizioni, progressione.note, esercizio.id_esercizio, esercizio.descrizione FROM progressione INNER JOIN esercizio ON progressione.id_esercizio = esercizio.id_esercizio WHERE progressione.deleted = false ORDER BY progressione.giorno ASC, progressione.id_progressione ASC');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_scheda' => +$entry['id_scheda'],
                'id_progressione' => +$entry['id_progressione'],
                'giorno' => +$entry['giorno'],
                'serie' => $entry['serie'],
                'ripetizioni' => $entry['ripetizioni'],
                'note' => $entry['note'],
                'id_esercizio' => +$entry['id_esercizio'],
                'nome_esercizio' => $entry['descrizione']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/progressione
    static function addProgressione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO progressione ( id_scheda, id_esercizio, giorno, serie, ripetizioni, note ) VALUES (:id_scheda, :id_esercizio, :giorno, :serie, :ripetizioni, :note)');
        $stm->bindValue(":id_scheda", $body['id_scheda']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":giorno", $body['giorno']);
        $stm->bindValue(":serie", $body['serie']);
        $stm->bindValue(":ripetizioni", $body['ripetizioni']);
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
        $stm = $app->db->prepare('UPDATE progressione SET id_scheda=:id_scheda, id_esercizio=:id_esercizio, giorno=:giorno, serie=:serie, ripetizioni=:ripetizioni, note=:note WHERE id_progressione=:id_progressione');
        $stm->bindValue(":id_progressione", $body['id_progressione']);
        $stm->bindValue(":id_scheda", $body['id_scheda']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":giorno", $body['giorno']);
        $stm->bindValue(":serie", $body['serie']);
        $stm->bindValue(":ripetizioni", $body['ripetizioni']);
        $stm->bindValue(":note", $body['note']);
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