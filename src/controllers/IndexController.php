<?php
/**
 * Created by vivedo <edoardo.viviani@gmail.com>
 * 29/03/19
 */

class IndexController {

    static function get($req, $res, $service, $app){
        $res->json(['message' => 'Welcome to gestionale API', 'version' => 'alpha']);
    }

}