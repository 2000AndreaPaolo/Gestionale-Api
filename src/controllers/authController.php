<?php

require_once __DIR__ . '/../helpers.php';

class AuthController {
    static function adminLogin($req, $res, $service, $app){
        $parameters = $req->body();
        $parameters = json_decode($parameters, true);
        $stm = $app->db->prepare('SELECT * FROM coach WHERE username=:username AND password=:password');
        $stm->bindValue(":username", $parameters['username']);
        $stm->bindValue(":password", md5($parameters['password']));
        $stm->execute();
        if($stm->rowCount() > 0){
            $var = $stm->fetchAll(PDO::FETCH_ASSOC);
            $data = array_map(function($entry){
                return [
                    'id_coach' => +$entry['id_coach'],
                    'nome' => $entry['nome'],
                    'cognome' => $entry['cognome'],
                    'token' => getJwt(['id_coach' => +$entry['id_coach']])
                ];
            }, $var);
            $res->json($data[0]);
        }else{
            $res->json(["message" => "Username o Password errati", "code" => 401]);
        }
    }

    static function atletaLogin($req, $res, $service, $app){
        $parameters = $req->body();
        $parameters = json_decode($parameters, true);
        $stm = $app->db->prepare('SELECT id_atleta, nome, cognome, username, data_nascita, id_specializzazione FROM atleta WHERE username = :username and password = :password and deleted=false');
        $stm->bindValue(":username", $parameters['username']);
        $stm->bindValue(":password", md5($parameters['password']));
        $stm->execute();
        if($stm->rowCount() > 0){
            $var = $stm->fetchAll(PDO::FETCH_ASSOC);
            $data = array_map(function($entry){
                return [
                    'id_atleta' => +$entry['id_atleta'],
                    'nome' => $entry['nome'],
                    'cognome' => $entry['cognome'],
                    'username' => $entry['username'],
                    'id_specializzazione' => +$entry['id_specializzazione'],
                    'data_nascita' => $entry['data_nascita'],
                    'token' => getJwt(['id_atleta' => +$entry['id_atleta']])
                ];
            }, $var);
            $res->json($data[0]);
        }else{
            $res->json(["message" => "Credenziali errate", "code" => 401]);
        }
    }

    static function resetPasswordClienti($req, $res, $service, $app){
        $parameters = $req->body();
        $parameters = json_decode($parameters, true);
        $stm = $app->db->prepare('SELECT nome, cognome FROM atleta where id_atleta = :id ');
        $stm->bindValue(":id", $parameters['id']);
        $stm->execute();
        if($stm->rowCount())
        {   
          $dbres = $stm->fetchAll(PDO::FETCH_ASSOC);
          $data = array_map(function($entry){
              return [
                  'nome' => $entry['nome'],
                  'cognome' => $entry['cognome'],
              ];
          }, $dbres);
          $password = $data[0]['nome'] . '.' . $data[0]['cognome'];
          $password = strtolower($password);
          $stm = $app->db->prepare("UPDATE atleta SET password=:password WHERE id_atleta =:id");
          $stm->bindValue(":id", $parameters['id']);
          $stm->bindValue(":password", md5($password));
          if($stm->execute())
            $res->json(["message" => "OK", "code" => 200]);
            else{
              $res->json(["message" => "Password non resetta", "code" => 500]);
            }
        }else{
          $res->json(["message" => "Password non resetta", "code" => 500]);
        }
    }

    static function changePasswordClienti($req, $res, $service, $app){
        $parameters = $req->body();
        $parameters = json_decode($parameters, true);
        $stm = $app->db->prepare('SELECT * FROM atleta where id_atleta = :id AND password = :oldpassword');
        $stm->bindValue(":id", $parameters['id']);
        $stm->bindValue(":oldpassword", md5($parameters['oldpassword']));
        $stm->execute();
        if($stm->rowCount())
        {
          $stm = $app->db->prepare('UPDATE atleta SET password=:newpassword WHERE id_atleta = :id' );
          $stm->bindValue(":id", $parameters['id']);
          $stm->bindValue(":newpassword", md5($parameters['newpassword']));
            if($stm->execute()){
              $res->json(["message" => "OK", "code" => 200 ]);
            }
            else{
              $res->json(["message" => "Password non modificata", "code" => 500 ]);
            }
        }else{
          $res->json(["message" => "Vecchia Password non corretta", "code" => 501]);
        }
      }
}