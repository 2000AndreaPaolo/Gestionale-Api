<?php

class PrestazioneController{

    // GET /admin/prestazione
    static function getPrestazione($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT prestazione.*, alteta.nome, atleta.cognome, esercizio.descrizione FROM prestazione INNER JOIN atleta ON atleta.id_atleta=prestazione.id_atleta INNER JOIN esercizio ON esercizio.id_esercizio=prestazione.id_esercizio WHERE prestazione.deleted=false');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_prestazione' => +$entry['id_prestazione'],
                'id_atleta' => +$entry['id_atleta'],
                'id_esercizio' => +$entry['id_esercizio'],
                'peso' => +$entry['peso'],
                'data' => $entry['data'],
                'note' => $entry['note'],
                'nome_atleta' => $entry['nome'],
                'cognome_atleta' => $entry['cognome'],
                'esercizio_descrizione' => $entry['esercizio_descrizione']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/prestazione
    static function addPrestazione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO prestazione ( id_atleta, id_esercizio, peso, note ) VALUES (:id_atleta,:id_esercizio,:peso,:note)');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":peso", $body['peso']);
        $stm->bindValue(":note", $body['note']);
	    if($stm->execute()){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Prestazione non aggiunta", "code" => 500 ]);
		}
    }

    // PUT /admin/prestazione
    static function modifyPeso($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE prestazione SET id_atleta=:id_atleta, id_esercizio=:id_esercizio, peso=:peso, note=:note WHERE id_prestazione=:id_prestazione');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":id_esercizio", $body['id_esercizio']);
        $stm->bindValue(":peso", $body['peso']);
        $stm->bindValue(":note", $body['note']);
        $stm->bindValue(":id_prestazione", $body['id_prestazione']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Prestazione non modificata", "code" => 500 ]);
		}
    }

    // DELETE /admin/prestazione
    static function deletePrestazione($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE prestazione SET deleted=true WHERE id_prestazione=:id_prestazione');
        $stm->bindValue(":id_prestazione", $body['id_prestazione']);
        $stm->execute();
	    if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Prestazione non eliminata", "code" => 500 ]);
		}
    }
}