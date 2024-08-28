<?php

// Requiere el archivo de configuración de la base de datos
require_once  __DIR__ . '/../config/Conexion_db.php';

// Define la clase ProductCategory que hereda de la clase Conexion
class ProductCategory extends Conexion {

    // Método para mostrar productos junto con sus categorías
    public static function mostrarProductosCategorias() {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        // Consulta SQL para seleccionar productos y sus categorías asociadas
        $query = "SELECT productos.id_producto, productos.nombre_producto, productos.precio, productos.impuesto, productos.stock, categorias.nombre_categoria, productos.descripcion, productos.imagen_url 
                  FROM `productos` 
                  INNER JOIN `categorias` 
                  ON productos.id_categoria = categorias.id_categoria";
        $resultado = $conexion->query($query)->fetch_all(MYSQLI_ASSOC); // Ejecuta la consulta y obtiene los resultados

        return $resultado; // Retorna los resultados como un array asociativo
    }

}

?>
