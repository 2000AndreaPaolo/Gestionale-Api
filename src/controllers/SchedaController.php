<?php

class SchedaController{

    // GET /admin/scheda
    static function getSchede($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('SELECT scheda.id_coach, scheda.id_scheda, scheda.nome, scheda.data_inizio, scheda.data_fine, scheda.durata, scheda.id_atleta, atleta.nome AS nome_atleta, atleta.cognome AS cognome_atleta FROM scheda INNER JOIN atleta ON scheda.id_atleta = atleta.id_atleta WHERE scheda.deleted = false AND scheda.id_coach=:id_coach');
        $stm->bindValue(":id_coach", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_scheda' => +$entry['id_scheda'],
                'nome' => $entry['nome'],
                'data_inizio' => date("d-m-Y", strtotime($entry['data_inizio'])),
                'data_fine' => date("d-m-Y", strtotime($entry['data_fine'])),
                'durata' => $entry['durata'],
                'nome_atleta' => $entry['nome_atleta'],
                'id_atleta' => +$entry['id_atleta'],
                'cognome_atleta' => $entry['cognome_atleta']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/scheda
    static function addScheda($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO scheda ( nome, data_inizio, data_fine, durata, id_atleta, id_coach ) VALUES (:nome,:data_inizio,:data_fine,:durata,:id_atleta,:id_coach)');
        $stm->bindValue(":nome", $body['nome']);
        $stm->bindValue(":data_inizio", $body['data_inizio']);
        $stm->bindValue(":data_fine", $body['data_fine']);
        $stm->bindValue(":durata", $body['durata']);
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":id_coach", $body['id_coach']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Scheda non aggiunta", "code" => 500 ]);
		}
    }

    // PUT /admin/scheda
    static function modifyScheda($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE scheda SET nome=:nome, data_inizio=:data_inizio, data_fine=:data_fine, durata=:durata, id_atleta=:id_atleta WHERE id_scheda=:id_scheda');
        $stm->bindValue(":id_scheda", $body['id_scheda']);
        $stm->bindValue(":nome", $body['nome']);
        $stm->bindValue(":data_inizio", $body['data_inizio']);
        $stm->bindValue(":data_fine", $body['data_fine']);
        $stm->bindValue(":durata", $body['durata']);
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Scheda non modificata", "code" => 500 ]);
		}
    }

    // DELETE /admin/scheda
    static function deleteScheda($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE scheda SET deleted=true WHERE id_scheda=:id_scheda');
        $stm->bindValue(":id_scheda", $body['id_scheda']);
        $stm->execute();
	    if($stm->rowCount() > 0){
            $stm = $app->db->prepare('UPDATE progressione SET deleted=true WHERE id_scheda=:id_scheda');
            $stm->bindValue(":id_scheda", $body['id_scheda']);
            $stm->execute();
            $res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Scheda non eliminata", "code" => 500 ]);
        }
    }

    // POST /atelta/scheda
    static function getSchedaAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('SELECT scheda.id_scheda, scheda.nome, scheda.data_inizio, scheda.data_fine, scheda.durata FROM scheda INNER JOIN atleta ON scheda.id_atleta = atleta.id_atleta WHERE scheda.deleted = false AND scheda.id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_scheda' => +$entry['id_scheda'],
                'nome' => $entry['nome'],
                //'data_inizio' => date("d-m-Y", strtotime($entry['data_inizio'])),
                'data_inizio' => $entry['data_inizio'],
                //'data_fine' => date("d-m-Y", strtotime($entry['data_fine'])),
                'data_fine' => $entry['data_fine'],
                'durata' => $entry['durata'],
            ];
        }, $dbres);

        $res->json($data);
    }
}