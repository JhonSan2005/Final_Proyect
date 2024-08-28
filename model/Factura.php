<?php

// Requiere el archivo de configuración de la base de datos
require_once __DIR__ . '/../config/Conexion_db.php';

// Define la clase Factura que hereda de la clase Conexion
class Factura extends Conexion {

    // Método para guardar una nueva factura
    public static function guardar($descripcion, $idUsuario, $fecha_facturacion, $direccion, $impuesto) {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        // Consulta SQL para insertar una nueva factura en la base de datos
        $query = "INSERT INTO `factura`(`descripcion`, `id_estado`, `id_usuario`, `fecha_facturacion`, `direccion_facturacion`, `impuesto`) VALUES ('$descripcion','1','$idUsuario','$fecha_facturacion','$direccion','$impuesto')";
        $ejecutarConsulta = $conexion->query($query); // Ejecuta la consulta

        // Verifica si la consulta se ejecutó correctamente
        if( !$ejecutarConsulta ) return false;

        // Obtiene el ID del último registro insertado
        $resultado = $conexion->insert_id;

        return $resultado; // Retorna el ID de la nueva factura
    }

    // Método para consultar facturas por una columna específica
    public static function cosultaPorColumna($columnaDB, $datoABuscar) {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        // Consulta SQL para seleccionar facturas basadas en una columna específica
        $query = "SELECT * FROM `factura` WHERE $columnaDB = '$datoABuscar'";
        $resultado = $conexion->query($query)->fetch_all(MYSQLI_ASSOC); // Ejecuta la consulta y obtiene los resultados

        return $resultado; // Retorna el resultado como un array asociativo
    }

    // Método para eliminar una factura por ID
    public static function eliminar($id) {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        // Consulta SQL para eliminar una factura basada en su ID
        $query = "DELETE * FROM `factura` WHERE id = '$id'";
        $resultado = $conexion->query($query); // Ejecuta la consulta

        return $resultado; // Retorna el resultado de la ejecución
    }

}

?>
