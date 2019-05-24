<?php

class ProgrammaController{

    // GET /admin/programma
    static function getProgramma($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT programma.*, atleta.nome, atleta.cognome FROM programma INNER JOIN atleta ON programma.id_atleta=atleta.id_atleta WHERE programma.deleted=false');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_programma' => +$entry['id_programma'],
                'id_atleta' => +$entry['id_atleta'],
                'data_inizio' => $entry['data_inizio'],
                'data_fine' => $entry['data_fine'],
                'note' => $entry['note'],
                'nome_atleta' => $entry['nome'],
                'cognome_atleta' => $entry['cognome']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/programma
    static function addProgramma($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO programma ( id_atleta, data_inizio, data_fine, note ) VALUES (:id_atleta,:data_inizio,:data_fine,:note)');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":data_inizio", $body['data_inizio']);
        $stm->bindValue(":data_fine", $body['data_fine']);
        $stm->bindValue(":note", $body['note']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Programma non aggiunto", "code" => 500 ]);
		}
    }

    // PUT /admin/programma
    static function modifyProgramma($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE programma SET id_atleta=:id_atleta, data_inizio=:data_inizio, data_fine=:data_fine, note=:note WHERE id_programma=:id_programma');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":data_inizio", $body['data_inizio']);
        $stm->bindValue(":data_fine", $body['data_fine']);
        $stm->bindValue(":note", $body['note']);
        $stm->bindValue(":id_programma", $body['id_programma']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Programma non modificato", "code" => 500 ]);
		}
    }

    // DELETE /admin/programma
    static function deleteProgramma($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE programma SET deleted=true WHERE id_programma=:id_programma');
        $stm->bindValue(":id_programma", $body['id_programma']);
        $stm->execute();
	    if($stm->rowCount() > 0){
            $stm = $app->db->prepare('UPDATE programmazione SET deleted=true WHERE id_programma=:id_programma');
            $stm->bindValue(":id_programma", $body['id_programma']);
            $stm->execute();
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Programma non eliminato", "code" => 500 ]);
		}
    }
}