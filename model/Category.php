<?php

require_once __DIR__ . "/../config/Conexion_db.php";

class Category extends Conexion {

    public static function verCategorias( int $limit = 0 ) {
        $conexion = Conexion::conectar();
        $query = "SELECT * FROM `categorias`";
        if( $limit ) $query .= " LIMIT $limit";
        $query .= ";";
        $resulado = $conexion->query($query)->fetch_all(MYSQLI_ASSOC);
        return $resulado;
    }
    public static function buscarporcategoria($id_categoria) {
        $conexion = Conexion::conectar();
        $query = "SELECT * FROM `productos` WHERE `id_categoria` = $id_categoria;";
        $resultado = $conexion->query($query)->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }
    public static function agregarcategorias($id_categoria, $nombre_categoria) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("INSERT INTO categorias (id_categoria, nombre_categoria) VALUES (?, ?)");
        $consulta->bind_param('ss', $id_categoria, $nombre_categoria);
        $resultado = $consulta->execute();

        return $resultado;
    }
    public static function eliminarCategoriaAdmin($id_categoria) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("DELETE FROM categorias WHERE id_categoria = ?");
        $consulta->bind_param('i', $id_categoria); // Cambié $id_producto a $id_categoria
        $resultado = $consulta->execute();
    
        return $resultado;
    }
    public static function categoriaExiste($id_categoria) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("SELECT COUNT(*) FROM categorias WHERE id_categoria = ?");
        $consulta->bind_param('i', $id_categoria);
        $consulta->execute();
        $consulta->bind_result($count);
        $consulta->fetch();
        
        return $count > 0;
    }
    public static function encontrarCategoria($id_categoria) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
        $consulta->bind_param('i', $id_categoria); // 'i' para entero, si el id_categoria es un entero
        $consulta->execute();
        $resultado = $consulta->get_result()->fetch_assoc();
        return $resultado;
    }
    
    public static function actualizarCategoria($id_categoria, $nombre_categoria) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("UPDATE categorias SET nombre_categoria = ? WHERE id_categoria = ?");
        $consulta->bind_param('si', $nombre_categoria, $id_categoria); // 's' para string y 'i' para entero
        $resultado = $consulta->execute();
        return $resultado;
    }
    public static function obtenerCategorias() {
        $conexion = self::conectar();
        $consulta = $conexion->query("SELECT * FROM categorias");
        return $consulta->fetch_all(MYSQLI_ASSOC);
    }

}




?>
