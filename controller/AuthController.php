<?php

include_once __DIR__ . "/../Router.php";
include_once __DIR__ . "/../model/Usuario.php";
include_once __DIR__ . "/../helpers/functions.php";
include_once __DIR__ . "/../helpers/Alerta.php";

class AuthController {

    public static function login(Router $router) {
        if (isAuth()) {
            return header("Location: /");
        }
    
        $alertas = new Alerta;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = filter_var($_POST['password'] ?? '');
    
            $alertas->crearAlerta(!$correo, 'danger', 'Correo no válido');
            $alertas->crearAlerta(!$password, 'danger', 'La contraseña no puede ir vacía');
    
            if (!$alertas::obtenerAlertas()) {
                $resultado = Usuario::validarlogin($correo, $password);
    
                if ($resultado) {
                    $usuario = Usuario::encontrarUsuario('correo', $correo);
    
                    session_start();
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['documento'] = $usuario['documento'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['correo'] = $usuario['correo'];
                    $_SESSION['id_rol'] = $usuario['id_rol']; // Guarda el rol en la sesión
    
                    // Redirige según el id_rol
                    if ($_SESSION['id_rol'] == 1) {
                        header("Location: /admin/dashboard"); // Administrador
                    } else {
                        header("Location: /"); // Usuario normal
                    }
                } else {
                    $alertas->crearAlerta(!$resultado, 'danger', 'Credenciales Incorrectas');
                }
            }
        }
    
        $alertas = $alertas::obtenerAlertas();
        $router->render('auth/login', [
            "title" => "Iniciar Sesión",
            "alertas" => $alertas
        ]);
    }
    

    public static function register( Router $router ) {

        if( isAuth() ) {
            return header("Location: /");
        }

        $alertas = new Alerta;

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
            // Guardamos en variables los datos del formulario para su posterior uso
            $documento = filter_var($_POST['documento'] ?? '');
            $nombre = filter_var($_POST['nombre'] ?? '');
            $correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = filter_var($_POST['password'] ?? '');
            $password_confirmation = filter_var($_POST['password_confirmation'] ?? '');
            $termsAndConditions = filter_var($_POST['termsAndConditions'] ?? '');

            // Creamos las validaciones
            $alertas->crearAlerta( !$documento, 'danger', 'El documento no puede ir Vacio' );
            $alertas->crearAlerta( !$nombre, 'danger', 'El nombre no puede ir Vacio' );
            $alertas->crearAlerta( !$correo , 'danger', 'Correo no Valido' );
            $alertas->crearAlerta( !$password, 'danger', 'El password no puede ir Vacio' );
            $alertas->crearAlerta( strlen($password) < 8, 'danger', 'El password debe ser de minimo 8 caracteres' );
            $alertas->crearAlerta( $password !== $password_confirmation, 'danger', 'Los passwords deben ser iguales' );
            $alertas->crearAlerta( $termsAndConditions !== 'on', 'danger', 'Debes aceptar los Terminos y Condiciones para Continuar' );

            // Validamos que no hayan Alertas
            if( !$alertas::obtenerAlertas() ) {

                $resultado = Usuario::registrarUsuario($documento, $nombre, $correo, $password);

                if( $resultado ) {

                    // Consultamos el nuevo usuario que acabamos de crear para guardar sus datos en la sesion
                    $usuario = Usuario::encontrarUsuario('correo', $correo);

                    // Iniciamos la sesion
                    session_start();

                    // Guardamos los datos del usuario en la sesion actual
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['documento'] = $usuario['documento'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['correo'] = $usuario['correo'];

                    // Redireccionamos a la pagina que queramos que vea
                    header("Location: /");

                } else {
                    $alertas->crearAlerta(!$resultado, 'danger', 'Ha ocurrido un error al registrar el usuario, por favor intentalo denuevo');
                }

            }

        }

        $alertas = $alertas->obtenerAlertas();


        $router->render('auth/register', [
            "title" => "Registrarse",
            "alertas" => $alertas
        ]);

    }
    
    public static function closeSession() {

        if( !isAuth() ) {
            return header("Location: /");
        }

        session_start();
        $_SESSION = [];
        session_destroy();
        header("Location: /");
    }
    

    
}

?>