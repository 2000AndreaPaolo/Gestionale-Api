<?php

class CestinoController{
    // GET /admin/deleted/atleti
    static function getAtleti($req, $res, $service, $app){
        $stm = $app->db->prepare('SELECT * FROM deleted_atleta');
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);

        $data = array_map(function($entry){
            return [
                'id_atleta' => +$entry['id_atleta'],
                'nome' => $entry['nome'],
                'cognome' => $entry['cognome'],
                'data_nascita' => $entry['data_nascita'],
                'username' => $entry['username']
            ];
        }, $dbres);

        $res->json($data);
    }

    static function restoreAtleti($req, $res, $service, $app){
        $body = $req->body();
        $body = json_decode($body, true);
        //Atleta
        $stm = $app->db->prepare('UPDATE deleted_atleta SET deleted=false WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        //Note
        $stm = $app->db->prepare('UPDATE deleted_note SET deleted=false WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        //Peso
        $stm = $app->db->prepare('UPDATE deleted_peso SET deleted=false WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        //Plicometria
        $stm = $app->db->prepare('UPDATE deleted_plicometria SET deleted=false WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        //Prestazione
        $stm = $app->db->prepare('UPDATE deleted_prestazione SET deleted=false WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        //Programma
        $stm = $app->db->prepare('SELECT id_programma FROM deleted_programma WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach($dbres as $id){
            $stm = $app->db->prepare('UPDATE deleted_programma SET deleted=false WHERE id_programma=:id_programma');
            $stm->bindValue(":id_programma", $id['id_programma']);
            $stm->execute();
            if($stm->rowCount() > 0){
                //Programmazione
                $stm = $app->db->prepare('UPDATE deleted_programmazione SET deleted=false WHERE id_programma=:id_programma');
                $stm->bindValue(":id_programma", $id['id_programma']);
                $stm->execute();
            }
        }
        //Scheda
        $stm = $app->db->prepare('SELECT id_scheda FROM deleted_scheda WHERE id_atleta=:id_atleta');
        $stm->bindValue(":id_atleta", $body);
        $stm->execute();
        $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach($dbres as $id){
            $stm = $app->db->prepare('UPDATE deleted_scheda SET deleted=false WHERE id_scheda=:id_scheda');
            $stm->bindValue(":id_scheda", $id['id_scheda']);
            $stm->execute();
            if($stm->rowCount() > 0){
                //Progressione
                $stm = $app->db->prepare('UPDATE deleted_progressione SET deleted=false WHERE id_scheda=:id_scheda');
                $stm->bindValue(":id_scheda", $id['id_scheda']);
                $stm->execute();
            }
        }
        $res->json(["message" => "OK", "code" => 200 ]);
    }
}