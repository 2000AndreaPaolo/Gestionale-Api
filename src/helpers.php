<?php

$secret = "i-am-inevitable/and-i-am-iron-man";

function getJwt($fields = array(), $secretkey = NULL) {

    if(!$secretkey)
        $secretkey = $secret;
 
    $encoded_header = base64_encode('{"alg": "HS256","typ": "JWT"}');

    $encoded_payload = base64_encode(json_encode($fields));

    $header_payload = $encoded_header . '.' . $encoded_payload;

    $signature = base64_encode(hash_hmac('sha256', $header_payload, $secretkey, true));

    $jwt_token = $header_payload . '.' . $signature;

    return $jwt_token;

}

function checkJwt($token = NULL, $secretkey = NULL) {

    if(!$secretkey)
        $secretkey = $secret;

    $jwt_values = explode('.', $token); 

    $recieved_signature = $jwt_values[2];

    $recievedHeaderAndPayload = $jwt_values[0] . '.' . $jwt_values[1];

    $resultedsignature = base64_encode(hash_hmac('sha256', $recievedHeaderAndPayload, $secretkey, true));

    if ($resultedsignature == $recieved_signature) return(true);
    else return(false);

}

function parseJwt($token = NULL, $secretkey = NULL) {

    if(!$secretkey)
    $secretkey = $secret;

    if(true || checkJwt($token, $secretkey))
        return json_decode(base64_decode(explode('.', $token)[1]), true);
    else 
        return false;
}

/*
function getUser($db, $id){
    $stm = $db->prepare('SELECT id_studente, nome, cognome, id_classe, id_gruppo FROM studente WHERE id_studente = :id');
    $stm->bindValue(":id", $id);
    $stm->execute();
    return $stm->fetch(PDO::FETCH_ASSOC);
}
*/