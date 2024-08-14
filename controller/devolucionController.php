<?php

require_once __DIR__ . "/../Router.php";
require_once __DIR__ . "/../model/Ventas.php"; // Asegúrate de que el archivo de conexión esté incluido

class DevolucionController {
    public static function devolucion(Router $router) {
        $router->render('profile/devolucion', [
            "title" => "Política de Devoluciones"
        ]);
    }

    public static function procesarDevolucion(Router $router) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitizar el ID de la factura
            $id_factura = filter_var($_POST['id_factura'], FILTER_SANITIZE_NUMBER_INT);

            // Supongamos que el ID del usuario logueado está en la sesión
            session_start();
            $id_usuario = $_SESSION['id'] ?? null; // Asegúrate de que este valor se establece al iniciar sesión

            if ($id_usuario) {
                // Llamar al método del modelo para procesar la devolución
                $result = Ventas::procesarDevolucion($id_factura, $id_usuario);

                $mensaje = "";
                if ($result->num_rows > 0) {
                    $factura = $result->fetch_assoc();
                    $fecha_facturacion = new DateTime($factura['fecha_facturacion']);
                    $fecha_actual = new DateTime();
                    $fecha_limite = clone $fecha_facturacion;
                    $fecha_limite->modify('+1 day');

                    // Validar si han pasado más de 1 día desde la fecha de facturación
                    if ($fecha_actual > $fecha_limite) {
                        $mensaje = "No se puede realizar la devolución, ya ha pasado más de 1 día desde la fecha de facturación.";
                    } else {
                        $mensaje = "Por favor, empaca el producto y envíalo a la siguiente dirección: Calle 18 #28-50.";
                    }
                } else {
                    $mensaje = "No se encontró una factura con el ID proporcionado o no pertenece a tu cuenta.";
                }
            } else {
                $mensaje = "Error: No se ha encontrado información de usuario.";
            }

            // Renderizar la vista con el mensaje
            $router->render('profile/devolucion', [
                "title" => "Política de Devoluciones",
                "mensaje" => $mensaje
            ]);
        }
    }
}
