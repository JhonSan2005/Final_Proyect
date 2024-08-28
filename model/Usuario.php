<?php
// Requiere el archivo de configuración de la base de datos
require_once __DIR__ . '/../config/Conexion_db.php';

// Define la clase Usuario que hereda de la clase Conexion
class Usuario extends Conexion {

    // Método para validar el login de un usuario
    public static function validarlogin($correo, $password) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Prepara la consulta para obtener el usuario con el correo especificado
        $consulta = $conexion->prepare("SELECT * FROM usuario WHERE correo = ? LIMIT 1");
        $consulta->bind_param('s', $correo); // Vincula el parámetro
        $consulta->execute(); // Ejecuta la consulta
        $resultado = $consulta->get_result()->fetch_assoc(); // Obtiene el resultado

        // Verifica si la contraseña coincide
        return ($resultado && password_verify($password, $resultado['password'])) ? $resultado : false;
    }

    // Método para encontrar un usuario por un campo específico
    public static function encontrarUsuario($campo, $datoABuscar) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Prepara la consulta para buscar el usuario por el campo dado
        $consulta = $conexion->prepare("SELECT * FROM usuario WHERE $campo = ? LIMIT 1");
        $consulta->bind_param('s', $datoABuscar); // Vincula el parámetro
        $consulta->execute(); // Ejecuta la consulta
        $resultado = $consulta->get_result()->fetch_assoc(); // Obtiene el resultado
        return $resultado;
    }

    // Método para registrar un nuevo usuario
    public static function registrarUsuario($documento, $nombre, $correo, $password) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $id_rol = 2; // Rol predeterminado para nuevos usuarios
        $token = bin2hex(random_bytes(32)); // Genera un token único para el usuario
    
        $passwordEncriptada = password_hash($password, PASSWORD_BCRYPT); // Encripta la contraseña
    
        // Prepara la consulta para insertar un nuevo usuario
        $consulta = $conexion->prepare("INSERT INTO usuario (documento, nombre, correo, password, id_rol, token) VALUES (?, ?, ?, ?, ?, ?)");
        $consulta->bind_param('ssssis', $documento, $nombre, $correo, $passwordEncriptada, $id_rol, $token);
        $resultado = $consulta->execute(); // Ejecuta la consulta
    
        return $resultado;
    }

    // Método para actualizar los datos de un usuario
    public static function actualizarUsuario($documento, $nombre, $correo, $foto_perfil, $id) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Prepara la consulta para actualizar el usuario
        $consulta = $conexion->prepare("UPDATE usuario SET documento=?, nombre=?, correo=?, foto_perfil=? WHERE id=?");
        $consulta->bind_param('ssssi', $documento, $nombre, $correo, $foto_perfil, $id);
        $resultado = $consulta->execute(); // Ejecuta la consulta
        
        return $resultado;
    }

    // Método para eliminar ventas asociadas a una factura
    public static function eliminarVentasPorFactura($facturaId) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Prepara la consulta para eliminar ventas por ID de factura
        $consulta = $conexion->prepare("DELETE FROM ventas WHERE id_factura = ?");
        $consulta->bind_param('i', $facturaId); // Vincula el parámetro
        $resultado = $consulta->execute(); // Ejecuta la consulta
        return $resultado;
    }

    // Método para eliminar facturas asociadas a un usuario
    public static function eliminarFacturasPorUsuario($id) {
        $conexion = self::conectar(); // Conecta a la base de datos

        // Obtiene las facturas del usuario
        $consulta = $conexion->prepare("SELECT id FROM factura WHERE id_usuario = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $result = $consulta->get_result();

        // Elimina cada factura y sus ventas asociadas
        while ($factura = $result->fetch_assoc()) {
            $facturaId = $factura['id'];
            
            // Elimina las ventas asociadas a la factura
            if (!self::eliminarVentasPorFactura($facturaId)) {
                return false;
            }

            // Elimina la factura
            $consulta = $conexion->prepare("DELETE FROM factura WHERE id = ?");
            $consulta->bind_param('i', $facturaId);
            if (!$consulta->execute()) {
                return false;
            }
        }

        return true;
    }

    // Método para eliminar una cuenta de usuario y sus facturas
    public static function eliminarcuentauser($id) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $conexion->begin_transaction(); // Inicia una transacción
        
        try {
            // Elimina las facturas asociadas al usuario
            $facturasEliminadas = self::eliminarFacturasPorUsuario($id);
            if (!$facturasEliminadas) {
                throw new Exception('Error al eliminar las facturas.');
            }

            // Elimina el usuario
            $consulta = $conexion->prepare("DELETE FROM usuario WHERE id = ?");
            $consulta->bind_param('i', $id);
            $resultado = $consulta->execute();
            if (!$resultado) {
                throw new Exception('Error al eliminar el usuario.');
            }

            // Confirma la transacción
            $conexion->commit();
            return true;
        } catch (Exception $e) {
            // Revierte la transacción en caso de error
            $conexion->rollback();
            return false;
        }
    }

    // Método para guardar un token en la base de datos
    public static function guardarToken($idUsuario, $token) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Prepara la consulta para actualizar el token del usuario
        $consulta = $conexion->prepare("UPDATE usuario SET token=? WHERE id=?");
        $consulta->bind_param('si', $token, $idUsuario);
        return $consulta->execute(); // Ejecuta la consulta
    }

    // Método para eliminar un token de la base de datos
    public static function eliminarToken($idUsuario) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Prepara la consulta para eliminar el token del usuario
        $consulta = $conexion->prepare("UPDATE usuario SET token=NULL WHERE id=?");
        $consulta->bind_param('i', $idUsuario);
        return $consulta->execute(); // Ejecuta la consulta
    }

    // Método para actualizar la contraseña de un usuario
    public static function actualizarpassword($idUser, $password) {
        $conexion = self::conectar(); // Conecta a la base de datos
        $passwordEncriptada = password_hash($password, PASSWORD_BCRYPT); // Encripta la nueva contraseña
        // Prepara la consulta para actualizar la contraseña del usuario
        $consulta = $conexion->prepare("UPDATE usuario SET password=?, token='' WHERE id=?");
        $consulta->bind_param('si', $passwordEncriptada, $idUser);
        return $consulta->execute(); // Ejecuta la consulta
    }

    // Método para encontrar un usuario por correo
    public static function encontrarUsuarioPorCorreo($correo) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Prepara la consulta para buscar el usuario por correo
        $consulta = $conexion->prepare("SELECT * FROM usuario WHERE correo=?");
        $consulta->bind_param('s', $correo);
        $consulta->execute(); // Ejecuta la consulta
        $resultado = $consulta->get_result(); // Obtiene el resultado

        return $resultado->fetch_assoc(); // Devuelve el usuario o false si no se encontró
    }

    // Método para encontrar un usuario por token
    public static function encontrarUsuarioPorToken($token) {
        $conexion = Conexion::conectar(); // Conecta a la base de datos
        // Consulta SQL para buscar el usuario por token
        $query = "SELECT * FROM usuario WHERE token = '$token' LIMIT 1";
        $resultado = $conexion->query($query)->fetch_all(MYSQLI_ASSOC);

        return $resultado;
    }

    // Método para obtener el rol de un usuario por su ID (este método usa una conexión global, posiblemente no se debería usar así)
    public static function getRoleById($userId) {
        global $db; // Conexión global
        $stmt = $db->prepare("SELECT id_rol FROM usuario WHERE id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? $row['id_rol'] : null;
    }

    // Método para actualizar la contraseña de un usuario
    public static function actualizarContrasena($nueva_contrasena, $id) {
        $conexion = self::conectar(); // Conecta a la base de datos
        // Hash de la nueva contraseña
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
    
        // Prepara la consulta para actualizar la contraseña del usuario
        $consulta = $conexion->prepare("UPDATE usuario SET password=? WHERE id=?");
        $consulta->bind_param('si', $hashed_password, $id);
        $resultado = $consulta->execute();
    
        return $resultado;
    }
    
}

?>
