<?php

require_once __DIR__ . "/../Router.php";


class FormapagoController {

    public static function formaPago(Router $router) {
        // Iniciar la sesión para acceder a las variables de sesión
        session_start();

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['id']) || !$_SESSION['id']) {
            // Redirigir al usuario a la página de inicio
            header("Location: /login");
            exit();
        }

        // Si el usuario está autenticado, renderizar la página de forma de pago
        $router->render('payments/formaPago', [
            "title" => "Forma de Pago"
        ]);
    }
}
?>


