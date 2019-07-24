<?php

class NoteController{

    // GET /admin/note
    static function getNote($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('SELECT note.*, atleta.nome, atleta.cognome, atleta.id_coach FROM note INNER JOIN atleta ON note.id_atleta=atleta.id_atleta WHERE note.deleted=false AND atleta.id_coach=:id_coach ORDER BY note.data DESC');
        $stm->bindValue(":id_coach", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_note' => +$entry['id_note'],
                'id_atleta' => +$entry['id_atleta'],
                'data' => $entry['data'],
                'note' => $entry['note'],
                'nome_atleta' => $entry['nome'],
                'cognome_atleta' => $entry['cognome']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/note
    static function addNote($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('INSERT INTO note ( id_atleta, note ) VALUES (:id_atleta,:note)');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":note", $body['note']);
	    if($stm->execute()){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Nota non aggiunta", "code" => 500 ]);
		}
    }

    // PUT /admin/note
    static function modifyNote($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE note SET id_atleta=:id_atleta, note=:note WHERE id_note=:id_note');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":note", $body['note']);
        $stm->bindValue(":id_note", $body['id_note']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Peso non modificato", "code" => 500 ]);
		}
    }

    // DELETE /admin/note
    static function deleteNote($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE note SET deleted=true WHERE id_note=:id_note');
        $stm->bindValue(":id_note", $body['id_note']);
        $stm->execute();
	    if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Nota non eliminata", "code" => 500 ]);
		}
    }

    // POST /atelta/programma
    static function getNoteAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('SELECT note.* FROM note WHERE note.deleted=false AND note.id_atleta=:id_atleta ORDER BY note.data DESC');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_note' => +$entry['id_note'],
                'data' => date("d-m-Y", strtotime($entry['data'])),
                'note' => $entry['note']
            ];
        }, $dbres);

        $res->json($data);
    }
}