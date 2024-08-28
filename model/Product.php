<?php

// Requiere el archivo de configuración de la base de datos
require_once __DIR__ . '/../config/Conexion_db.php';

// Define la clase Product que hereda de la clase Conexion
class Product extends Conexion {
    
    // Método para agregar un nuevo producto
    public static function agregarproductos($nombre_producto, $precio, $impuesto, $stock, $id_categoria, $descripcion, $imagen_url) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Consulta SQL para insertar un nuevo producto en la base de datos
        $consulta = $conexion->prepare("INSERT INTO productos (nombre_producto, precio, impuesto, stock, id_categoria, descripcion, imagen_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $consulta->bind_param('ssddiss', $nombre_producto, $precio, $impuesto, $stock, $id_categoria, $descripcion, $imagen_url); // Vincula los parámetros
        $resultado = $consulta->execute(); // Ejecuta la consulta

        return $resultado; // Retorna el resultado de la ejecución
    }

    // Método para mostrar productos con un límite opcional
    public static function mostrarproductos(int $limit = 0) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $consulta = "SELECT * FROM productos"; // Consulta SQL para seleccionar todos los productos
        if ($limit && $limit > 0) {
            $consulta .= " LIMIT $limit"; // Añade límite a la consulta si se especifica
        }
        $resultado = $conexion->query($consulta)->fetch_all(MYSQLI_ASSOC); // Ejecuta la consulta y obtiene los resultados

        if (!$resultado) return false; // Retorna false si no hay resultados

        return $resultado; // Retorna los resultados como un array asociativo
    }

    // Método para buscar productos por un campo específico
    public static function buscarProducto($campo, $datoABuscar) {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        // Consulta SQL para buscar productos por un campo específico
        $consulta = "SELECT * FROM `productos` WHERE `$campo` = '$datoABuscar'";
        $resultado = $conexion->query($consulta)->fetch_all(MYSQLI_ASSOC); // Ejecuta la consulta y obtiene los resultados

        if (!$resultado) return false; // Retorna false si no hay resultados

        return $resultado; // Retorna los resultados como un array asociativo
    }

    // Método para buscar productos por un parámetro con búsqueda LIKE
    public static function buscarPorParametro($parametro) {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        $parametro = "%" . $parametro . "%"; // Prepara el parámetro para la búsqueda LIKE
    
        $stmt = $conexion->prepare("SELECT * FROM productos WHERE nombre_producto LIKE ? OR descripcion LIKE ?");
        $stmt->bind_param("ss", $parametro, $parametro); // Vincula el parámetro en ambas columnas
        $stmt->execute(); // Ejecuta la consulta
        $result = $stmt->get_result(); // Obtiene el resultado
    
        return $result->fetch_all(MYSQLI_ASSOC); // Retorna los resultados como un array asociativo
    }

    // Método para contar el número total de productos
    public static function contarProductos() {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        $resultado = $conexion->query("SELECT COUNT(*) AS total FROM productos"); // Consulta SQL para contar los productos
        $fila = $resultado->fetch_assoc(); // Obtiene la fila de resultados
        return $fila['total']; // Retorna el total de productos
    }

    // Método para encontrar un producto por su ID
    public static function encontrarProducto($id_producto) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $consulta = $conexion->prepare("SELECT * FROM `productos` WHERE `id_producto` = ?"); // Consulta SQL para encontrar un producto por ID
        $consulta->bind_param('s', $id_producto); // Vincula el parámetro
        $consulta->execute(); // Ejecuta la consulta
        $resultado = $consulta->get_result()->fetch_assoc(); // Obtiene el resultado como un array asociativo
        return $resultado; // Retorna el resultado
    }

    // Método para actualizar un producto
    public static function actualizarProducto($nombre_producto, $precio, $impuesto, $stock, $id_categoria, $descripcion, $imagen_url, $id_producto) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Consulta SQL para actualizar un producto
        $consulta = $conexion->prepare("UPDATE productos SET nombre_producto=?, precio=?, impuesto=?, stock=?, id_categoria=?, descripcion=?, imagen_url=? WHERE id_producto=?");
        $consulta->bind_param('sdiiisss', $nombre_producto, $precio, $impuesto, $stock, $id_categoria, $descripcion, $imagen_url, $id_producto); // Vincula los parámetros
        $resultado = $consulta->execute(); // Ejecuta la consulta
        return $resultado; // Retorna el resultado de la ejecución
    }

    // Método para eliminar un producto y las ventas relacionadas
    public static function eliminarProductosAdmin($id_producto) {
        $conexion = self::conectar(); // Conecta a la base de datos

        // Elimina las ventas relacionadas con el producto
        $consultaVentas = $conexion->prepare("DELETE FROM ventas WHERE id_producto = ?");
        $consultaVentas->bind_param('i', $id_producto); // Vincula el parámetro
        $consultaVentas->execute(); // Ejecuta la consulta

        // Elimina el producto
        $consultaProducto = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
        $consultaProducto->bind_param('i', $id_producto); // Vincula el parámetro
        $resultado = $consultaProducto->execute(); // Ejecuta la consulta
    
        return $resultado; // Retorna el resultado de la ejecución
    }

    // Método para actualizar un campo específico de un producto
    public static function actualizarProductoPorColumna($columnaDB, $datoAActualizar, $id_producto) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Consulta SQL para actualizar un campo específico
        $consulta = $conexion->query("UPDATE `productos` SET `$columnaDB` = '$datoAActualizar' WHERE id_producto = $id_producto");
        return $consulta; // Retorna el resultado de la consulta
    }

}

?>
