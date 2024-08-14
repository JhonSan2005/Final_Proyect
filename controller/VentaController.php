<?php

require_once __DIR__ . "/../Router.php";
require_once __DIR__ . "/../model/Usuario.php";
require_once __DIR__ . "/../model/Factura.php";
require_once __DIR__ . "/../model/Ventas.php";
require_once __DIR__ . "/../model/VentasFactura.php";

class VentaController {

    public static function index(Router $router) {
        $router->render('payments/formaPago', [
            "title" => "Forma de Pago"
        ]);
    }

    public static function vender() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = filter_var($_POST['nombre'] ?? '', FILTER_SANITIZE_STRING);
            $apellido = filter_var($_POST['apellido'] ?? '', FILTER_SANITIZE_STRING);
            $correo = filter_var($_POST['correo'] ?? '', FILTER_SANITIZE_EMAIL);
            $direccion = filter_var($_POST['direccion'] ?? '', FILTER_SANITIZE_STRING);
            $pais = filter_var($_POST['pais'] ?? '', FILTER_SANITIZE_STRING);
            $departamento = filter_var($_POST['departamento'] ?? '', FILTER_SANITIZE_STRING);
            $municipio = filter_var($_POST['municipio'] ?? '', FILTER_SANITIZE_STRING);
            $listaProductos = json_decode(filter_var($_POST['lista_productos'] ?? '', FILTER_SANITIZE_STRING), true);

            // Validaciones
            if (!$correo) {
                http_response_code(400);
                echo json_encode(["msg" => "El correo no puede ir vacío", "type" => "danger"]);
                return;
            }

            if (empty($listaProductos) || !$direccion || !$pais || !$departamento || !$municipio) {
                http_response_code(400);
                echo json_encode(["msg" => "No se pudo generar la compra, vuelve a intentarlo", "type" => "danger"]);
                return;
            }

            // Verificar existencia del usuario
            $existeUsuario = Usuario::encontrarUsuario('correo', $correo);
            if (!$existeUsuario) {
                http_response_code(400);
                echo json_encode(["msg" => "Error con el usuario, por favor ingrese el correo de su cuenta", "type" => "danger"]);
                return;
            }

            // Procesar factura
            $hoy = date("Y-m-d H:i:s");
            $direccionCompleta = "$direccion - $municipio, $departamento, $pais";
            $generarFactura = Factura::guardar('Factura de Venta', $existeUsuario['id'], $hoy, $direccionCompleta, 19);

            if ($generarFactura) {
                $productosEnCarrito = [];

                foreach ($listaProductos as $producto) {
                    // Validar que el id de cada producto exista
                    $existeProducto = Product::buscarProducto('id_producto', $producto['id']);
                    if (!$existeProducto) {
                        http_response_code(400);
                        echo json_encode(["msg" => "Ha ocurrido un error, vuelve a intentarlo más tarde", "type" => "danger"]);
                        return;
                    }

                    // Validar stock
                    if (intval($existeProducto["stock"]) < intval($producto['cantidad'])) {
                        http_response_code(400);
                        echo json_encode(["msg" => "Ha ocurrido un error, parece que las cantidades seleccionadas no están disponibles", "type" => "danger"]);
                        return;
                    }

                    $existeProducto["cantidad_escogida"] = $producto['cantidad'];
                    $productosEnCarrito[] = $existeProducto;
                }

                // Insertar los productos en la tabla de ventas
                foreach ($productosEnCarrito as $productoAFacturar) {
                    $generarVenta = Ventas::guardar($generarFactura, $productoAFacturar['id_producto'], $productoAFacturar['cantidad_escogida']);
                    if (!$generarVenta) {
                        Factura::eliminar($generarFactura);
                        http_response_code(400);
                        echo json_encode(["msg" => "Ha ocurrido un error al emitir la factura", "type" => "danger"]);
                        return;
                    }

                    // Actualizar stock
                    $cantidad_restante = intval($productoAFacturar['stock']) - intval($productoAFacturar['cantidad_escogida']);
                    Product::actualizarProductoPorColumna('stock', $cantidad_restante, $productoAFacturar['id_producto']);
                }

                // Mostrar factura generada
                $facturaGenerada = VentasFactura::verDetallesFactura($generarFactura);
                if ($facturaGenerada) {
                    http_response_code(200);
                    echo json_encode(["msg" => "Factura de Venta Generada Correctamente", "type" => "success", "data" => $facturaGenerada]);
                    return;
                } else {
                    http_response_code(400);
                    echo json_encode(["msg" => "Error al mostrar la factura generada, por favor póngase en contacto con el administrador", "type" => "danger"]);
                    return;
                }
            }
        }
    }
}
?>
