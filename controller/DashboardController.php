<?php

// Requiere el archivo necesario para el funcionamiento del controlador.
require_once __DIR__ . "/../Router.php";

class DashboardController {

    // Método para mostrar la vista principal del dashboard.
    public static function index(Router $router) {

        // Renderiza la vista del dashboard, pasando un título a la vista.
        $router->render('dashboard/dashboard', [
            "title" => "Dashboard"
        ]);

    }

    // Método para mostrar la vista de la tabla de usuarios en el dashboard.
    public static function tablaUser(Router $router) {

        // Renderiza la vista de la tabla de usuarios en el dashboard, pasando un título a la vista.
        $router->render('dashboard/tablaUser', [
            "title" => "Dashboard"
        ]);

    }
}

?>
