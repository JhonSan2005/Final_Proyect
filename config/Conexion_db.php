<?php
class Conexion {
    private static $servername = 'localhost';
    private static $username = 'root';
    private static $password = '';
    private static $dbname = 'bd_jj';

    // Configurar los parámetros de conexión
    public static function configurar($servername, $username, $password, $dbname) {
        self::$servername = $servername;
        self::$username = $username;
        self::$password = $password;
        self::$dbname = $dbname;
    }

    // Establecer la conexión con la base de datos
    public static function conectar() {
        // Crear una nueva conexión
        $conexion = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);

        // Comprobar si ocurrió un error de conexión
        if ($conexion->connect_error) {
            die("Connection failed: " . $conexion->connect_error);
        }

        return $conexion; // Retornar la conexión para su uso posterior
    }
    
}
?>
