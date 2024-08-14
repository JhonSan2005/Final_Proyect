<?php

require_once __DIR__ . '/../config/Conexion_db.php';

class Ventas extends Conexion {

    public static function guardar($idFactura, $idProducto, $cantidadProducto) {
        $conexion = Conexion::conectar();
        $query = "INSERT INTO `ventas`(`id_factura`, `id_producto`, `cantidad`) VALUES ('$idFactura','$idProducto','$cantidadProducto')";
        $resultado = $conexion->query($query);

        return $resultado;
    }
    public static function procesarDevolucion($id_factura, $id_usuario) {
        // Conectar a la base de datos
        $conexion = Conexion::conectar();
    
        // Consultar la fecha de facturación de la factura ingresada, asegurando que pertenece al usuario
        $query = "SELECT fecha_facturacion FROM factura WHERE id = ? AND id_usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('ii', $id_factura, $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Cerrar la conexión
        $stmt->close();
        $conexion->close();
    
        return $result; // Retorna el resultado de la consulta
    }
}
   



?>