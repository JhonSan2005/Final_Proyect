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

        $router->render('dashboard/tablaUser', [
            "title" => "Dashboard"
        ]);

    }
    public static function eliminarUsuarioAdmin(Router $router)
{
    if (!isAuth()) {
        return header("Location: /404");
    }

    $documento = $_GET['documento'] ?? null;

    if ($documento === null) {
        return header("Location: /404"); // Redirige si no se proporciona un documento
    }

    $result = Usuario::eliminarUsuarioAdmin($documento);
    if ($result !== false) {
        return header("Location: /admin/tablaUser");
    }

    $router->render('dashboard/tablaUser', [
        "title" => "Dashboard"
    ]);
}

    

}

?>