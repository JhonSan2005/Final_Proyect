<?php

// Requiere el archivo de configuración de la base de datos
require_once __DIR__ . "/../config/Conexion_db.php";

// Define la clase admin que hereda de la clase Conexion
class admin extends Conexion {

    // Método para editar el rol de un usuario
    public function editarRol($id, $id_rol) {
        // Utiliza la conexión a la base de datos de la clase base
        $conexion = self::conectar();
        
        // Define la consulta SQL para actualizar el rol de un usuario
        $query = "UPDATE usuario SET id_rol = ? WHERE id = ?";
        
        // Prepara la consulta
        $stmt = $conexion->prepare($query);
        
        // Vincula los parámetros a la consulta
        $stmt->bind_param('ii', $id_rol, $id);
        
        // Ejecuta la consulta y verifica si fue exitosa
        if ($stmt->execute()) {
            return true; // Actualización exitosa
        } else {
            return false; // Error en la actualización
        }
    }
}
?>
