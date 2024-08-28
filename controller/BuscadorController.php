<?php

// Requiere los archivos necesarios para el funcionamiento del controlador.
require_once __DIR__ . "/../Router.php";
require_once __DIR__ . "/../helpers/functions.php";
require_once __DIR__ . "/../model/Product.php";

class BuscadorController {

    // Método para manejar la búsqueda de productos.
    public static function buscar(Router $router) {

        // Filtra y sanitiza el parámetro de búsqueda ingresado por el usuario.
        $parametroABuscar = filter_var($_GET['q'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Elimina espacios en blanco al inicio y al final del parámetro de búsqueda.
        $parametroABuscar = trim($parametroABuscar);
        
        // Si el parámetro de búsqueda está vacío, redirige a la página principal.
        if( !$parametroABuscar ) header("Location: /");

        // Busca productos en la base de datos que coincidan con el parámetro de búsqueda.
        $resultados = Product::buscarPorParametro($parametroABuscar);

        // Renderiza la vista con los resultados de la búsqueda.
        $router->render("searchBar/verResultados", [
            "title" => "Buscando...",
            "parametroABuscar" => $parametroABuscar,
            "resultadosBusqueda" => $resultados,
        ]);

    }

}

?>
