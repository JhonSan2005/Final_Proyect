<?php

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

        $usuarioId = $_SESSION['id'];

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

        $usuarioId = $_SESSION['id']; // Obtén el ID del usuario actual desde la sesión
        $compras = [];

        // Obtén las facturas del usuario
        $facturas = VentasFactura::getFacturasByUsuarioId($usuarioId);

        foreach ($facturas as $factura) {
            $productos = VentasFactura::getProductosByFacturaId($factura['id']);
            $compras[$factura['id']] = [
                'fecha_facturacion' => $factura['fecha_facturacion'],
                'descripcion' => $factura['descripcion'],
                'impuesto' => $factura['impuesto'],
                'direccion_facturacion' => $factura['direccion_facturacion'],
                'estado' => $factura['estado'],
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
