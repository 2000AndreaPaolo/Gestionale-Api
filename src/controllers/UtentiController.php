<?php

class UtentiController{

    // GET /admin/atleta
    static function getAtleti($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT atleta.id_atleta, atleta.nome, atleta.cognome, atleta.data_nascita, atleta.username, atleta.id_specializzazione, specializzazione.descrizione FROM atleta INNER JOIN specializzazione ON specializzazione.id_specializzazione=atleta.id_specializzazione WHERE atleta.deleted=false');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_atleta' => +$entry['id_atleta'],
                'nome' => $entry['nome'],
                'cognome' => $entry['cognome'],
                'data_nascita' => date("d-m-Y", strtotime($entry['data_nascita'])),
                'username' => $entry['username'],
                'descrizione' => $entry['descrizione'],
                'id_specializzazione' => +$entry['id_specializzazione']
            ];
        }, $dbres);

        $res->json($data);
    }

    // POST /admin/atleta
    static function addAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $username = $body['nome'].'.'.$body['cognome'];
        $password = $body['nome'].'.'.$body['cognome'];
        $stm = $app->db->prepare('INSERT INTO atleta ( nome, cognome, username, password, data_nascita, id_specializzazione ) VALUES (:nome, :cognome, :username, :password, :data_nascita, :id_specializzazione)');
        $stm->bindValue(":nome", $body['nome']);
        $stm->bindValue(":cognome", $body['cognome']);
        $stm->bindValue(":username", $username);
        $stm->bindValue(":password", md5($password));
        $stm->bindValue(":data_nascita", $body['data_nascita']);
        $stm->bindValue(":id_specializzazione", $body['id_specializzazione']);
	    if($stm->execute()){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Atleta non aggiunto", "code" => 500, "error" => $stm->errorInfo() ]);
		}
    }

    // PUT /admin/atleta
    static function modifyAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $username = $body['nome'].'.'.$body['cognome'];
        $stm = $app->db->prepare('UPDATE atleta SET nome=:nome, cognome=:cognome, username=:username, data_nascita=:data_nascita, id_specializzazione=:id_specializzazione WHERE id_atleta=:id_atleta');
        $stm->bindValue(":nome", $body['nome']);
        $stm->bindValue(":cognome", $body['cognome']);
        $stm->bindValue(":username", $username);
        $stm->bindValue(":data_nascita", $body['data_nascita']);
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->bindValue(":id_specializzazione", $body['id_specializzazione']);
        $stm->execute();
		if($stm->rowCount() > 0){
			$res->json(["message" => "OK", "code" => 200 ]);
		}else{
			$res->json(["message" => "Atleta non modificato", "code" => 500 ]);
		}
    }

    // DELETE /admin/atleta
    static function deleteAtleta($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        $stm = $app->db->prepare('UPDATE atleta SET deleted=true WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body['id_atleta']);
        $stm->execute();
	    if($stm->rowCount() > 0){
            //Rimozione delle note
            $stm = $app->db->prepare('UPDATE note SET deleted=true WHERE id_atleta=:id_atleta');
            $stm->bindValue(":id_atleta", $body['id_atleta']);
            $stm->execute();
            //Rimozione del peso
            $stm = $app->db->prepare('UPDATE peso SET deleted=true WHERE id_atleta=:id_atleta');
            $stm->bindValue(":id_atleta", $body['id_atleta']);
            $stm->execute();
            //Rimozione della plicometria
            $stm = $app->db->prepare('UPDATE plicometria SET deleted=true WHERE id_atleta=:id_atleta');
            $stm->bindValue(":id_atleta", $body['id_atleta']);
            $stm->execute();
            //Rimozione delle prestazioni
            $stm = $app->db->prepare('UPDATE prestazione SET deleted=true WHERE id_atleta=:id_atleta');
            $stm->bindValue(":id_atleta", $body['id_atleta']);
            $stm->execute();
            //Rimozione delle programma
            $stm = $app->db->prepare('SELECT id_programma FROM programma WHERE id_atleta=:id_atleta AND deleted=false');
            $stm->bindValue(":id_atleta", $body['id_atleta']);
            $stm->execute();
            $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);
            foreach($dbres as $id){
                $stm = $app->db->prepare('UPDATE programma SET deleted=true WHERE id_programma=:id_programma');
                $stm->bindValue(":id_programma", $id['id_programma']);
                $stm->execute();
                if($stm->rowCount() > 0){
                    //Rimozione della programmazione
                    $stm = $app->db->prepare('UPDATE programmazione SET deleted=true WHERE id_programma=:id_programma');
                    $stm->bindValue(":id_programma", $id['id_programma']);
                    $stm->execute();
                }
            }
            //Rmozione delle schede
            $stm = $app->db->prepare('SELECT id_scheda FROM scheda WHERE id_atleta=:id_atleta AND deleted=false');
            $stm->bindValue(":id_atleta", $body['id_atleta']);
            $stm->execute();
            $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);
            foreach($dbres as $id){
                $stm = $app->db->prepare('UPDATE scheda SET deleted=true WHERE id_scheda=:id_scheda');
                $stm->bindValue(":id_scheda", $id['id_scheda']);
                $stm->execute();
                if($stm->rowCount() > 0){
                    //Rimozione della programmazione
                    $stm = $app->db->prepare('UPDATE progressione SET deleted=true WHERE id_scheda=:id_scheda');
                    $stm->bindValue(":id_scheda", $id['id_scheda']);
                    $stm->execute();
                }
            }
            $res->json(["message" => "OK", "code" => 200 ]);
        }else{
            $res->json(["message" => "Atleta non eliminato", "code" => 500 ]);
        }
    }

    // GET /admin/specializzazione
    static function getSpecializzazione($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT id_specializzazione, descrizione FROM specializzazione WHERE deleted=false');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_specializzazione' => +$entry['id_specializzazione'],
                'descrizione' => $entry['descrizione']
            ];
        }, $dbres);

        $res->json($data);
    }
}