<?php
require_once __DIR__ . '/../config/Conexion_db.php';

class Usuario extends Conexion {

    public static function validarlogin($correo, $password) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("SELECT * FROM usuario WHERE correo = ? LIMIT 1");
        $consulta->bind_param('s', $correo);
        $consulta->execute();
        $resultado = $consulta->get_result()->fetch_assoc();
        return ($resultado && password_verify($password, $resultado['password'])) ? $resultado : false;
    }

    public static function encontrarUsuario($campo, $datoABuscar) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("SELECT * FROM usuario WHERE $campo = ? LIMIT 1");
        $consulta->bind_param('s', $datoABuscar);
        $consulta->execute();
        $resultado = $consulta->get_result()->fetch_assoc();
        return $resultado;
    }

    public static function registrarUsuario($documento, $nombre, $correo, $password) {
        $conexion = self::conectar();
        $id_rol = 2;
        $token = bin2hex(random_bytes(32));
    
        $passwordEncriptada = password_hash($password, PASSWORD_BCRYPT);
    
        $consulta = $conexion->prepare("INSERT INTO usuario (documento, nombre, correo, password, id_rol, token) VALUES (?, ?, ?, ?, ?, ?)");
        $consulta->bind_param('ssssis', $documento, $nombre, $correo, $passwordEncriptada, $id_rol, $token);
        $resultado = $consulta->execute();
    
        return $resultado;
    }



    public static function actualizarUsuario($documento, $nombre, $correo, $foto_perfil, $id) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("UPDATE usuario SET documento=?, nombre=?, correo=?, foto_perfil=? WHERE id=?");
        $consulta->bind_param('ssssi', $documento, $nombre, $correo, $foto_perfil, $id);
        $resultado = $consulta->execute();
        
        return $resultado;
    }

    public static function eliminarVentasPorFactura($facturaId) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("DELETE FROM ventas WHERE id_factura = ?");
        $consulta->bind_param('i', $facturaId);
        $resultado = $consulta->execute();
        return $resultado;
    }

    // Método para eliminar facturas asociadas al usuario
    public static function eliminarFacturasPorUsuario($id) {
        $conexion = self::conectar();

        // Obtener las facturas del usuario
        $consulta = $conexion->prepare("SELECT id FROM factura WHERE id_usuario = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $result = $consulta->get_result();

        // Eliminar cada factura y sus ventas asociadas
        while ($factura = $result->fetch_assoc()) {
            $facturaId = $factura['id'];
            
            // Eliminar ventas asociadas a la factura
            if (!self::eliminarVentasPorFactura($facturaId)) {
                return false;
            }

            // Eliminar la factura
            $consulta = $conexion->prepare("DELETE FROM factura WHERE id = ?");
            $consulta->bind_param('i', $facturaId);
            if (!$consulta->execute()) {
                return false;
            }
        }

        return true;
    }

    // Método para eliminar el usuario y sus facturas
    public static function eliminarcuentauser($id) {
        $conexion = self::conectar();
        $conexion->begin_transaction(); // Iniciar la transacción
        
        try {
            // Eliminar las facturas asociadas
            $facturasEliminadas = self::eliminarFacturasPorUsuario($id);
            if (!$facturasEliminadas) {
                throw new Exception('Error al eliminar las facturas.');
            }

            // Eliminar el usuario
            $consulta = $conexion->prepare("DELETE FROM usuario WHERE id = ?");
            $consulta->bind_param('i', $id);
            $resultado = $consulta->execute();
            if (!$resultado) {
                throw new Exception('Error al eliminar el usuario.');
            }

            // Confirmar la transacción
            $conexion->commit();
            return true;
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conexion->rollback();
            return false;
        }
    }


    public static function guardarToken($idUsuario, $token) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("UPDATE usuario SET token=? WHERE id=?");
        $consulta->bind_param('si', $token, $idUsuario);
        return $consulta->execute();
    }

    public static function eliminarToken($idUsuario) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("UPDATE usuario SET token=NULL WHERE id=?");
        $consulta->bind_param('i', $idUsuario);
        return $consulta->execute();
    }

    public static function actualizarpassword($idUser, $password) {
        $conexion = self::conectar();
        $passwordEncriptada = password_hash($password, PASSWORD_BCRYPT);
        $consulta = $conexion->prepare("UPDATE usuario SET password=?, token='' WHERE id=?");
        $consulta->bind_param('si', $passwordEncriptada, $idUser);
        return $consulta->execute();
    }
    

    // Método para encontrar usuario por correo
    public static function encontrarUsuarioPorCorreo($correo) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("SELECT * FROM usuario WHERE correo=?");
        $consulta->bind_param('s', $correo);
        $consulta->execute();
        $resultado = $consulta->get_result();

        return $resultado->fetch_assoc(); // Devuelve el usuario o false si no se encontró
    }
    public static function encontrarUsuarioPorToken($token) {
        $conexion = Conexion::conectar();
        $query = "SELECT * FROM usuario WHERE token = '$token' LIMIT 1";
        $resultado = $conexion->query($query)->fetch_all(MYSQLI_ASSOC);

        return $resultado;
    }
    public static function getRoleById($userId) {
        // Asumiendo que tienes una conexión a la base de datos
        global $db;
        $stmt = $db->prepare("SELECT id_rol FROM usuario WHERE id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? $row['id_rol'] : null;
    }
    public static function actualizarContrasena($nueva_contrasena, $id) {
        $conexion = self::conectar();
        // Hash de la nueva contraseña
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
    
        $consulta = $conexion->prepare("UPDATE usuario SET password=? WHERE id=?");
        $consulta->bind_param('si', $hashed_password, $id);
        $resultado = $consulta->execute();
    
        return $resultado;
    }
    
}

?>
