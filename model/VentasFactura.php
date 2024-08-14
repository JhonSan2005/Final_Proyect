<?php

require_once __DIR__ . "/../config/Conexion_db.php";

class VentasFactura extends Conexion {
    
    public static function verDetallesFactura($idFactura) {
        $conexion = Conexion::conectar();
        $query = "SELECT ventas.id, descripcion, fecha_facturacion, direccion_facturacion, ventas.id AS id_venta, ventas.id_producto AS id_producto, ventas.cantidad AS cantidad 
                  FROM `factura` 
                  INNER JOIN `ventas` ON factura.id = ventas.id_factura 
                  WHERE factura.id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('i', $idFactura);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return $resultado;
    }

    public static function obtenerComprasUsuario($usuarioId) {
        $db = Conexion::conectar();
        
        // Consulta para obtener las facturas y los productos asociados
        $sql = "SELECT 
                    f.id AS factura_id,
                    f.descripcion AS descripcion_factura,
                    f.fecha_facturacion,
                    f.direccion_facturacion,
                    f.impuesto,
                    es.estado AS estado_factura,
                    p.nombre_producto,
                    p.precio,
                    v.cantidad
                FROM 
                    factura f
                JOIN 
                    estado_factura es ON f.id_estado = es.id
                JOIN 
                    ventas v ON f.id = v.id_factura
                JOIN 
                    productos p ON v.id_producto = p.id_producto
                WHERE 
                    f.id_usuario = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Organizar los resultados en un array
        $facturas = [];
        while ($row = $result->fetch_assoc()) {
            $facturaId = $row['factura_id'];
            if (!isset($facturas[$facturaId])) {
                $facturas[$facturaId] = [
                    'descripcion_factura' => $row['descripcion_factura'],
                    'fecha_facturacion' => $row['fecha_facturacion'],
                    'direccion_facturacion' => $row['direccion_facturacion'],
                    'impuesto' => $row['impuesto'],
                    'estado_factura' => $row['estado_factura'],
                    'productos' => []
                ];
            }
            $facturas[$facturaId]['productos'][] = [
                'nombre_producto' => $row['nombre_producto'],
                'precio' => $row['precio'],
                'cantidad' => $row['cantidad']
            ];
        }

        return $facturas;
    }

    public static function getFacturasByUsuarioId($usuarioId) {
        $db = Conexion::conectar();
        $sql = "SELECT * FROM factura WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function getProductosByFacturaId($facturaId) {
        $db = Conexion::conectar();
        $sql = "SELECT p.nombre_producto, p.precio, v.cantidad
                FROM ventas v
                JOIN productos p ON v.id_producto = p.id_producto
                WHERE v.id_factura = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $facturaId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

?>
