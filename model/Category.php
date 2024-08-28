<?php

// Requiere el archivo de configuración de la base de datos
require_once __DIR__ . "/../config/Conexion_db.php";

// Define la clase Category que hereda de la clase Conexion
class Category extends Conexion {

    // Método para ver categorías, con un límite opcional
    public static function verCategorias(int $limit = 0) {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        $query = "SELECT * FROM `categorias`"; // Consulta para obtener todas las categorías
        if ($limit) $query .= " LIMIT $limit"; // Agrega un límite a la consulta si se especifica
        $query .= ";"; // Finaliza la consulta
        $resulado = $conexion->query($query)->fetch_all(MYSQLI_ASSOC); // Ejecuta la consulta y obtiene los resultados
        return $resulado; // Retorna el resultado
    }

    // Método para buscar productos por categoría
    public static function buscarporcategoria($id_categoria) {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        $query = "SELECT * FROM `productos` WHERE `id_categoria` = $id_categoria;"; // Consulta para obtener productos por categoría
        $resultado = $conexion->query($query)->fetch_all(MYSQLI_ASSOC); // Ejecuta la consulta y obtiene los resultados
        return $resultado; // Retorna el resultado
    }

    // Método para agregar una nueva categoría
    public static function agregarcategorias($id_categoria, $nombre_categoria) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $consulta = $conexion->prepare("INSERT INTO categorias (id_categoria, nombre_categoria) VALUES (?, ?)"); // Prepara la consulta para insertar una nueva categoría
        $consulta->bind_param('ss', $id_categoria, $nombre_categoria); // Vincula los parámetros a la consulta
        $resultado = $consulta->execute(); // Ejecuta la consulta

        return $resultado; // Retorna el resultado de la ejecución
    }

    // Método para eliminar una categoría por ID
    public static function eliminarCategoriaAdmin($id_categoria) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $consulta = $conexion->prepare("DELETE FROM categorias WHERE id_categoria = ?"); // Prepara la consulta para eliminar una categoría
        $consulta->bind_param('i', $id_categoria); // Vincula el ID de la categoría a eliminar
        $resultado = $consulta->execute(); // Ejecuta la consulta
    
        return $resultado; // Retorna el resultado de la ejecución
    }

    // Método para verificar si una categoría existe
    public static function categoriaExiste($id_categoria) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $consulta = $conexion->prepare("SELECT COUNT(*) FROM categorias WHERE id_categoria = ?"); // Prepara la consulta para verificar la existencia de una categoría
        $consulta->bind_param('i', $id_categoria); // Vincula el ID de la categoría
        $consulta->execute(); // Ejecuta la consulta
        $consulta->bind_result($count); // Vincula el resultado a la variable $count
        $consulta->fetch(); // Obtiene el resultado
        
        return $count > 0; // Retorna verdadero si la categoría existe, de lo contrario, falso
    }

    // Método para encontrar una categoría por ID
    public static function encontrarCategoria($id_categoria) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $consulta = $conexion->prepare("SELECT * FROM categorias WHERE id_categoria = ?"); // Prepara la consulta para buscar una categoría por ID
        $consulta->bind_param('i', $id_categoria); // Vincula el ID de la categoría
        $consulta->execute(); // Ejecuta la consulta
        $resultado = $consulta->get_result()->fetch_assoc(); // Obtiene el resultado como un array asociativo
        return $resultado; // Retorna el resultado
    }

    // Método para actualizar una categoría
    public static function actualizarCategoria($id_categoria, $nombre_categoria) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $consulta = $conexion->prepare("UPDATE categorias SET nombre_categoria = ? WHERE id_categoria = ?"); // Prepara la consulta para actualizar una categoría
        $consulta->bind_param('si', $nombre_categoria, $id_categoria); // Vincula los parámetros a la consulta
        $resultado = $consulta->execute(); // Ejecuta la consulta
        return $resultado; // Retorna el resultado de la ejecución
    }

    // Método para obtener todas las categorías
    public static function obtenerCategorias() {
        $conexion = self::conectar(); // Conecta a la base de datos
        $consulta = $conexion->query("SELECT * FROM categorias"); // Ejecuta la consulta para obtener todas las categorías
        return $consulta->fetch_all(MYSQLI_ASSOC); // Retorna los resultados como un array asociativo
    }

    // Método para verificar si una categoría tiene productos asociados
    public static function tieneProductos($id_categoria) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $query = "SELECT COUNT(*) FROM productos WHERE id_categoria = ?"; // Prepara la consulta para contar productos asociados a una categoría
        $stmt = $conexion->prepare($query); // Prepara la consulta
        $stmt->bind_param('i', $id_categoria); // Vincula el ID de la categoría
        $stmt->execute(); // Ejecuta la consulta
        $stmt->bind_result($count); // Vincula el resultado a la variable $count
        $stmt->fetch(); // Obtiene el resultado
        $stmt->close(); // Cierra la declaración
        return $count > 0; // Retorna verdadero si hay productos asociados, de lo contrario, falso
    }
}

?>
