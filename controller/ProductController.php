<?php

require_once __DIR__ . "/../Router.php";
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/Category.php";
require_once __DIR__ . "/../helpers/functions.php";

class ProductController
{
    public static function index(Router $router)
    {
        $id_categoria = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING) ?? 'all';
    
        $productos = $id_categoria === 'all' ?
            Product::mostrarProductos() :
            Category::buscarporcategoria($id_categoria);
    
        $categorias = Category::obtenerCategorias();

        if (!$categorias) {
            echo "No se pudieron recuperar las categorías.";
            return;
        }
        $router->render('/products/verProductos', [
            "title" => "Productos",
            "productos" => $productos,
            "categorias" => $categorias
        ]);
    }
    

    public static function agregar(Router $router)
    {
        $alertas = new Alerta;
        $resultado = [];

        $categorias = Category::verCategorias() ?? [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Guardamos en variables los datos del formulario para su posterior uso
            $nombre_producto = filter_input(INPUT_POST, 'nombre_producto', FILTER_SANITIZE_STRING);
            $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $impuesto = filter_input(INPUT_POST, 'impuesto', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_SANITIZE_NUMBER_INT);
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

            // Creamos las validaciones
            $alertas->crearAlerta(!$nombre_producto, 'danger', 'El nombre no puede ir vacío');
            $alertas->crearAlerta(!$precio, 'danger', 'El precio no puede ir vacío');
            $alertas->crearAlerta(!$impuesto, 'danger', 'El impuesto no puede ir vacío');
            $alertas->crearAlerta(!$stock, 'danger', 'El stock no puede ir vacío');
            $alertas->crearAlerta(!$id_categoria, 'danger', 'La categoría no puede ir vacía');
            $alertas->crearAlerta(!$descripcion, 'danger', 'La descripción no puede ir vacía');

            // Validar y procesar la imagen
            $imagen = $_FILES['imagen'] ?? null;

            if ($imagen && isset($imagen['tmp_name']) && !empty($imagen['tmp_name'])) {
                // Crear el directorio de uploads si no existe
                if (!is_dir(__DIR__ . "/../img/uploads")) {
                    mkdir(__DIR__ . "/../img/uploads");
                }

                // Generar un nombre único para la imagen
                $nombre_unico_imagen = md5(uniqid(rand(), true)) . ".jpg";

                // Subir la imagen
                $subir_imagen = move_uploaded_file($imagen['tmp_name'], __DIR__ . "/../img/uploads/" . $nombre_unico_imagen);

                // Verificar si la imagen se subió correctamente
                if (!$subir_imagen) {
                    return $alertas->crearAlerta(true, 'danger', 'Error al subir la imagen');
                }

                $imagen_url = "/img/uploads/" . $nombre_unico_imagen;
            } else {
                $imagen_url = null; // Manejar el caso donde no se sube imagen
            }

            // Validar que no hayan alertas antes de agregar el producto
            if (!$alertas->obtenerAlertas()) {
                $resultado = Product::agregarproductos($nombre_producto, $precio, $impuesto, $stock, $id_categoria, $descripcion, $imagen_url);
                if (!$resultado) {
                    return $alertas->crearAlerta(true, 'danger', 'Ha ocurrido un error, vuelve a intentarlo');
                }
                return header("Location: /admin/products");
            }
        }

        $alertas = $alertas->obtenerAlertas();

        $router->render('products/agregarProductos', [
            "title" => "Agregar Productos",
            "alertas" => $alertas,
            "categorias" => $categorias
        ]);
    }

    public static function verProductosAdmin(Router $router)
    {
        if (!isAuth()) {
            return header("Location: /404");
        }

        $productos = Product::mostrarproductos();

        $router->render("products/verProductosAdmin", [
            "title" => "Administrar Productos",
            "productos" => $productos
        ]);
    }

    public static function eliminarProductoAdmin(Router $router)
    {
        if (!isAuth()) {
            return header("Location: /404");
        }

        $id_producto = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? null;

        if ($id_producto === null) {
            return header("Location: /404"); // Redirige si no se proporciona un ID
        }

        $result = Product::eliminarProductosAdmin($id_producto);
        if ($result !== false) {
            return header("Location: /admin/products");
        }

        $router->render("/products/actualizarProducto", [
            "title" => "Ver Productos"
        ]);
    } 

    public static function actualizarProducto(Router $router) {
        if (!isAuth()) {
            header("Location: /");
            exit;
        }
    
        $id_producto = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? '';
        $alertas = new Alerta;
        $resultado = '';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_producto = filter_input(INPUT_POST, 'nombre_producto', FILTER_SANITIZE_STRING);
            $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_STRING);
            $impuesto = filter_input(INPUT_POST, 'impuesto', FILTER_SANITIZE_STRING);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_STRING);
            $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_SANITIZE_NUMBER_INT) ?? 0;
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
            $imagen_url = filter_input(INPUT_POST, 'imagen_url', FILTER_SANITIZE_STRING) ?? '';
    
            if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {
                $imagen_url = 'ruta/a/la/imagen/' . basename($_FILES['imagen_producto']['name']);
                move_uploaded_file($_FILES['imagen_producto']['tmp_name'], $imagen_url);
            }
    
            // Verificar si la categoría existe
            if (!Category::categoriaExiste($id_categoria)) {
                $router->render('products/actualizarProducto', [
                    'title' => 'Categoría no encontrada',
                    'resultado' => 'Error: La categoría especificada no existe.',
                    'producto' => Product::encontrarProducto($id_producto),
                    'categorias' => Category::verCategorias() // Pasar las categorías al formulario
                ]);
                return;
            }
    
            $resultado = Product::actualizarProducto(
                $nombre_producto,
                $precio,
                $impuesto,
                $stock,
                $id_categoria,
                $descripcion,
                $imagen_url,
                $id_producto
            );
    
            $producto = Product::encontrarProducto($id_producto);
        } else {
            $producto = Product::encontrarProducto($id_producto);
        }
    
        if (!is_array($producto)) {
            $router->render('products/actualizarProducto', [
                'title' => 'Producto no encontrado',
                'resultado' => 'Error: El producto no fue encontrado.',
                'categorias' => Category::verCategorias() // Pasar las categorías al formulario
            ]);
            return;
        }
    
        $router->render('products/actualizarProducto', [
            'title' => 'Actualizar Producto',
            'resultado' => $resultado,
            'producto' => $producto,
            'categorias' => Category::verCategorias() // Pasar las categorías al formulario
        ]);
    }
}
?>
