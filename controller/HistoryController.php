<?php

require_once __DIR__ . "/../Router.php";

class HistoryController {

    

    public static function history(Router $router) {
        $router->render('historial/verHistorial', [
            "title" => "Historial"
        ]);
    }
  
    

}


?>
