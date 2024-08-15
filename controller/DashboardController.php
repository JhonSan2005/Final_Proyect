<?php

require_once __DIR__ . "/../Router.php";
require_once __DIR__ . "/../model/Usuario.php";
require_once __DIR__ . "/../helpers/functions.php";

class DashboardController {

    public static function index( Router $router ) {

        $router->render('dashboard/dashboard', [
            "title" => "Dashboard"
        ]);

    }
    public static function tablaUser( Router $router ) {

        $documento = $_GET['documento'] ?? '';

        if ($documento) {
            $result = Usuario::eliminarUsuarioAdmin($documento);
    
            if ($result !== false) {
                return header("Location: /admin/tablaUser");
            }
        }


        $router->render('dashboard/tablaUser', [
            "title" => "Dashboard"
        ]);

    }


}

?>