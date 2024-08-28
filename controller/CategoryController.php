<?php

// Requiere los archivos necesarios para el funcionamiento del controlador.
require_once __DIR__ . "/../Router.php";
require_once __DIR__ . "/../model/Category.php";
require_once __DIR__ . "/../helpers/functions.php";

class CategoryController {

    // Método para mostrar la lista de categorías.
    public static function index(Router $router) {
        // Verifica si el usuario está autenticado.
        $isAuth = isAuth();

        // Si el usuario no está autenticado, lo redirige a la página principal.
        if (!$isAuth) {
            return header("Location: /");
        }

        // Obtiene las categorías desde el modelo.
        $categories = Category::verCategorias();

        // Renderiza la vista con la lista de categorías.
        $router->render('categories/verCategorias', [
            "title" => "Categorías",
            "categories" => $categories
        ]);
    }

    // Método para agregar una nueva categoría.
    public static function agregarcategoria(Router $router) {
        // Verifica si el usuario está autenticado.
        if (!isAuth()) {
            header("Location: /");
            exit;
        }

        // Instancia un objeto para gestionar alertas.
        $alertas = new Alerta;

        // Verifica si la solicitud es de tipo POST.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Filtra y sanitiza los datos enviados desde el formulario.
            $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
            $nombre_categoria = filter_input(INPUT_POST, 'nombre_categoria', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

            // Genera alertas en caso de que los campos estén vacíos.
            $alertas->crearAlerta(empty($id_categoria), 'danger', 'El ID no puede ir vacío');
            $alertas->crearAlerta(empty($nombre_categoria), 'danger', 'El nombre no puede ir vacío');

            // Si no hay alertas, intenta agregar la categoría.
            if (!$alertas->obtenerAlertas()) {
                $resultado = Category::agregarcategorias($id_categoria, $nombre_categoria);

                // Si ocurre un error al agregar, muestra una alerta de error.
                if (!$resultado) {
                    $alertas->crearAlerta(true, 'danger', 'Error al agregar la categoría');
                } else {
                    // Si se agrega correctamente, muestra una alerta de éxito.
                    $alertas->crearAlerta(false, 'success', 'Categoría agregada exitosamente');
                    // Puedes redirigir a la vista de categorías si lo deseas.
                    // header("Location: /admin/categories");
                }
            }
        }

        // Obtiene las categorías y las alertas para renderizarlas en la vista.
        $categories = Category::verCategorias();
        $alertas = $alertas->obtenerAlertas();

        // Renderiza la vista para agregar una nueva categoría.
        $router->render('categories/agregarCategoria', [
            "title" => "Agregar Categoria",
            "categories" => $categories
        ]);
    }

    // Método para ver la lista de categorías con opciones administrativas.
    public static function verCategorias(Router $router) {
        // Verifica si el usuario está autenticado.
        $isAuth = isAuth();

        // Si el usuario no está autenticado, lo redirige a la página de error 404.
        if (!$isAuth) {
            return header("Location: /404");
        }

        // Obtiene las categorías desde el modelo.
        $categorias = Category::verCategorias();

        // Renderiza la vista para administrar las categorías.
        $router->render("categories/verCategorias", [
            "title" => "Administrar Categorías",
            "categorias" => $categorias
        ]);
    }

    // Método para eliminar una categoría desde la administración.
    public static function eliminarCategoriaAdmin(Router $router) {
        // Verifica si el usuario está autenticado.
        if (!isAuth()) {
            return header("Location: /404");
        }
    
        // Filtra y sanitiza el ID de la categoría enviado como parámetro.
        $id_categoria = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? null;
    
        // Si no se proporciona un ID válido, redirige a la página de error 404.
        if ($id_categoria === null) {
            return header("Location: /404");
        }
    
        // Verifica si la categoría tiene productos asociados.
        $tieneProductos = Category::tieneProductos($id_categoria);
    
        // Si la categoría tiene productos, no permite la eliminación y muestra un error.
        if ($tieneProductos) {
            $error = "La categoría no se puede eliminar porque tiene productos asociados.";
        } else {
            // Intenta eliminar la categoría.
            $resultado = Category::eliminarCategoriaAdmin($id_categoria);
    
            // Si ocurre un error al eliminar, muestra un mensaje de error.
            if ($resultado === false) {
                $error = "Error al eliminar la categoría.";
            } else {
                $error = null; // La eliminación fue exitosa.
            }
        }
    
        // Obtiene las categorías desde el modelo.
        $categorias = Category::verCategorias();
    
        // Renderiza la vista para administrar las categorías, incluyendo cualquier error.
        $router->render("categories/verCategorias", [
            "title" => "Administrar Categorías",
            "categorias" => $categorias,
            "error" => $error
        ]);
    }
    
    // Método para actualizar la información de una categoría.
    public static function actualizarCategoria(Router $router) {
        // Verifica si el usuario está autenticado.
        if (!isAuth()) {
            header("Location: /");
            exit;
        }
    
        // Filtra y sanitiza el ID de la categoría enviado como parámetro.
        $id_categoria = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? '';
        $resultado = '';
    
        // Si el método de solicitud es POST, se actualiza la categoría.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Filtra y sanitiza el nombre de la categoría enviado en la solicitud.
            $nombre_categoria = filter_input(INPUT_POST, 'nombre_categoria', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    
            // Verifica si la categoría existe.
            if (!Category::categoriaExiste($id_categoria)) {
                $router->render('categories/actualizarCategoria', [
                    'title' => 'Categoría no encontrada',
                    'resultado' => 'Error: La categoría especificada no existe.',
                    'categorias' => Category::verCategorias() // Pasar las categorías al formulario.
                ]);
                return;
            }
    
            // Actualiza la categoría y redirige a la página de administración.
            $resultado = Category::actualizarCategoria($id_categoria, $nombre_categoria);
            header('Location: /admin/categories'); // Redirigir después de actualizar.
            exit;
        } else {
            // Carga la categoría para mostrarla en el formulario.
            $categoria = Category::encontrarCategoria($id_categoria);
    
            // Si no se encuentra la categoría, muestra un mensaje de error.
            if (!is_array($categoria)) {
                $router->render('categories/actualizarCategoria', [
                    'title' => 'Categoría no encontrada',
                    'resultado' => 'Error: La categoría no fue encontrada.',
                    'categorias' => Category::verCategorias() // Pasar las categorías al formulario.
                ]);
                return;
            }
    
            // Renderiza la vista para actualizar la categoría, mostrando la información actual.
            $router->render('categories/actualizarCategoria', [
                'title' => 'Actualizar Categoría',
                'resultado' => $resultado,
                'categoria' => $categoria,
                'categorias' => Category::verCategorias() // Pasar las categorías al formulario.
            ]);
        }
    }
}

?>
