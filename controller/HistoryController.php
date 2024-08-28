<?php

// Requiere los archivos necesarios para el funcionamiento del controlador.

require_once __DIR__ . "/../Router.php";
require_once __DIR__ . '/../config/Conexion_db.php';
require_once __DIR__ . '/../model/Ventas.php';
require_once __DIR__ . '/../model/VentasFactura.php';

class HistoryController {

    public static function history(Router $router) {
        $router->render('historial/verHistorial', [
            "title" => "Historial"
        ]);
    }

    public static function verCompras(Router $router) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario está logueado
        if (!isset($_SESSION['id'])) {
            header("Location: /login");
            exit();
        }

        // Sanitizar el ID del usuario
        $usuarioId = filter_var($_SESSION['id'], FILTER_SANITIZE_NUMBER_INT);

        // Obtener las compras del usuario utilizando la función separada
        $compras = VentasFactura::obtenerComprasUsuario($usuarioId);

        // Renderizar la vista y pasar los datos de compras
        $router->render('historial/misCompras', [
            "title" => "Mis Compras",
            "compras" => $compras
        ]);
    }

    public static function historialCompras(Router $router) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Sanitizar el ID del usuario
        $usuarioId = filter_var($_SESSION['id'], FILTER_SANITIZE_NUMBER_INT);

        $compras = [];

        // Obtén las facturas del usuario
        $facturas = VentasFactura::getFacturasByUsuarioId($usuarioId);

        foreach ($facturas as $factura) {
            // Sanitizar los datos de la factura
            $facturaId = filter_var($factura['id'], FILTER_SANITIZE_NUMBER_INT);
            $productos = VentasFactura::getProductosByFacturaId($facturaId);
            $compras[$facturaId] = [
                'fecha_facturacion' => filter_var($factura['fecha_facturacion'], FILTER_SANITIZE_STRING),
                'descripcion' => filter_var($factura['descripcion'], FILTER_SANITIZE_STRING),
                'impuesto' => filter_var($factura['impuesto'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'direccion_facturacion' => filter_var($factura['direccion_facturacion'], FILTER_SANITIZE_STRING),
                'estado' => filter_var($factura['estado'], FILTER_SANITIZE_STRING),
                'productos' => $productos
            ];
        }

        $router->render('historial/misCompras', [
            'title' => 'Historial de Compras',
            'compras' => $compras
        ]);
    }
}

?>
