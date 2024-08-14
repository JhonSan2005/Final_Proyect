<?php

require_once __DIR__ . "/../config/Conexion_db.php";

class admin extends Conexion {

    // Método para editar el rol de un usuario
    public function editarRol($id, $id_rol) {
        $conexion = self::conectar(); // Utilizar la conexión de la clase base
        $query = "UPDATE usuario SET id_rol = ? WHERE id = ?";
        
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('ii', $id_rol, $id);
        
        if ($stmt->execute()) {
            return true; // Actualización exitosa
        } else {
            return false; // Error en la actualización
        }
    }
}
