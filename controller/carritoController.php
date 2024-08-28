<?php

// Requiere los archivos necesarios para el funcionamiento del controlador.
require_once __DIR__ . "/../Router.php";
require_once __DIR__ . "/../model/Category.php";
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../helpers/functions.php";

class CarritoController {

    // Método para manejar la vista del carrito de compras.
    public static function index(Router $router) {
        // Lógica para obtener los productos del carrito.
        $products = [
            [
                'id' => 1,
                'nombre' => 'Llanta Xcelink 400-8 TL Sellomatic Motocarro J-9117',
                'precio' => 134235,
                'imagen' => 'https://via.placeholder.com/150',
                'cantidad' => 1
            ],
            // Agrega más productos si es necesario.
        ];

        // Renderiza la vista del carrito de compras con los productos obtenidos.
        $router->render('products/carrito', [
            "title" => "Carrito de Compras",
            "products" => $products
        ]);
    }

    // Método para obtener la información de un producto específico.
    public static function obtenerInfoProducto() {

        // Verifica si la solicitud es de tipo POST.
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            // Filtra y valida el ID del producto enviado en la solicitud.
            $idProduct = filter_var(intval($_POST['id_product'] ?? 0));

            // Si el ID del producto es inválido, devuelve un mensaje de error en formato JSON.
            if( !$idProduct ) {
                echo json_encode([ "msg" => "Error en la Consulta, Vuelve a intentarlo", "type" => "danger" ]);
                return;
            }

            // Busca el producto en la base de datos utilizando el ID proporcionado.
            $product = Product::buscarProducto('id_producto', $idProduct);

            // Si no se encuentra el producto, devuelve un mensaje de error en formato JSON.
            if( !$product ) {
                echo json_encode([ "msg" => "Producto no Encontrado", "type" => "danger" ]);
                return;
            }

            // Si el producto se encuentra, devuelve la información del producto en formato JSON.
            echo json_encode([
                "producto" => $product[0]
            ]);

        }

    }

}

?>
