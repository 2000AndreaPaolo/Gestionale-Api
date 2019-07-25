<?php

class EsercizziController{

    // GET /admin/esercizio
    static function getEsercizzi($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('SELECT esercizio.id_esercizio, esercizio.descrizione FROM esercizio WHERE esercizio.deleted=false AND esercizio.id_coach=:id_coach');
        $stm->bindValue(":id_coach", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_esercizio' => +$entry['id_esercizio'],
                'descrizione' => $entry['descrizione']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/esercizio
    static function addEsercizio($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO esercizio ( descrizione, id_coach ) VALUES (:descrizione,:id_coach)');
        $stm->bindValue(":descrizione", $body['descrizione']);
        $stm->bindValue(":id_coach", $body['id_coach']);
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
        $stm = $app->db->prepare('UPDATE esercizio SET descrizione=:descrizione WHERE id_esercizio=:id_esercizio');
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":descrizione", $body['descrizione']);
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
}