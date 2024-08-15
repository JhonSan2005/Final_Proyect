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



    public static function actualizarUsuario($documento, $nombre, $correo, $password, $id) {
        $conexion = self::conectar();
        
        // Si la contraseña está vacía, no se actualiza
        $query = "UPDATE usuario SET documento=?, nombre=?, correo=?";
        if ($password !== '') {
            $passwordEncriptada = password_hash($password, PASSWORD_BCRYPT);
            $query .= ", password=?";
        }
        $query .= " WHERE id=?";
        
        $consulta = $conexion->prepare($query);
        if ($password !== '') {
            $consulta->bind_param('ssssii', $documento, $nombre, $correo, $passwordEncriptada, $id);
        } else {
            $consulta->bind_param('sssi', $documento, $nombre, $correo, $id);
        }
        $resultado = $consulta->execute();
        
        return $resultado;
    }
    
    public static function eliminarcuentauser($documuento) {
        $conexion = self::conectar();
        $consulta = $conexion->prepare("DELETE FROM usuario WHERE documento = ?");
        $consulta->bind_param('i', $id);
        $resultado = $consulta->execute();
    
        return $resultado;
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
    public static function eliminarUsuarioAdmin($documento) {
        $conexion = Conexion::conectar();
        $query = "DELETE FROM usuario WHERE documento = '$documento' AND id_rol = 2 LIMIT 1";
        $resultado = $conexion->query($query);
    
        if (!$resultado) {
            die("Error en la consulta: " . $conexion->error);
        }
    
        return $resultado;
    }
    
    
}
?>
