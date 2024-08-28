<?php

// Incluye los archivos necesarios para el funcionamiento del controlador.
include_once __DIR__ . "/../Router.php";
include_once __DIR__ . "/../model/Usuario.php";
include_once __DIR__ . "/../helpers/functions.php";
include_once __DIR__ . "/../helpers/Alerta.php";

class AuthController {

    // Método para manejar el proceso de inicio de sesión.
    public static function login(Router $router) {
        // Verifica si el usuario ya está autenticado. Si es así, lo redirige a la página principal.
        if (isAuth()) {
            return header("Location: /");
        }
    
        // Crea una instancia de la clase Alerta para gestionar las alertas.
        $alertas = new Alerta;
    
        // Comprueba si el formulario ha sido enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Filtra y valida el correo electrónico y la contraseña ingresados por el usuario.
            $correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = filter_var($_POST['password'] ?? '');
    
            // Crea alertas si el correo o la contraseña son inválidos.
            $alertas->crearAlerta(!$correo, 'danger', 'Correo no válido');
            $alertas->crearAlerta(!$password, 'danger', 'La contraseña no puede ir vacía');
    
            // Si no hay alertas, procede a validar las credenciales del usuario.
            if (!$alertas::obtenerAlertas()) {
                $resultado = Usuario::validarlogin($correo, $password);
    
                if ($resultado) {
                    // Si la validación es exitosa, busca la información del usuario en la base de datos.
                    $usuario = Usuario::encontrarUsuario('correo', $correo);
    
                    // Inicia la sesión y guarda la información del usuario en la sesión.
                    session_start();
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['documento'] = $usuario['documento'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['correo'] = $usuario['correo'];
                    $_SESSION['id_rol'] = $usuario['id_rol']; // Guarda el rol en la sesión
    
                    // Redirige al usuario según su rol.
                    if ($_SESSION['id_rol'] == 1) {
                        header("Location: /admin/dashboard"); // Administrador
                    } else {
                        header("Location: /"); // Usuario normal
                    }
                } else {
                    // Si las credenciales son incorrectas, crea una alerta.
                    $alertas->crearAlerta(!$resultado, 'danger', 'Credenciales Incorrectas');
                }
            }
        }
    
        // Obtiene las alertas para mostrarlas en la vista.
        $alertas = $alertas::obtenerAlertas();
        // Renderiza la vista de inicio de sesión.
        $router->render('auth/login', [
            "title" => "Iniciar Sesión",
            "alertas" => $alertas
        ]);
    }
    
    // Método para manejar el proceso de registro.
    public static function register(Router $router) {

        // Verifica si el usuario ya está autenticado. Si es así, lo redirige a la página principal.
        if (isAuth()) {
            return header("Location: /");
        }
    
        // Crea una instancia de la clase Alerta para gestionar las alertas.
        $alertas = new Alerta;
    
        // Comprueba si el formulario ha sido enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Filtra y valida los datos ingresados por el usuario.
            $documento = filter_var($_POST['documento'] ?? '');
            $nombre = filter_var($_POST['nombre'] ?? '');
            $correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = filter_var($_POST['password'] ?? '');
            $password_confirmation = filter_var($_POST['password_confirmation'] ?? '');
            $termsAndConditions = filter_var($_POST['termsAndConditions'] ?? '');
    
            // Crea alertas si hay problemas con los datos ingresados.
            $alertas->crearAlerta(!$documento, 'danger', 'El documento no puede ir Vacio');
            $alertas->crearAlerta(!$nombre, 'danger', 'El nombre no puede ir Vacio');
            $alertas->crearAlerta(!$correo, 'danger', 'Correo no Valido');
            $alertas->crearAlerta(!$password, 'danger', 'El password no puede ir Vacio');
            $alertas->crearAlerta(strlen($password) < 8, 'danger', 'El password debe ser de mínimo 8 caracteres');
            $alertas->crearAlerta($password !== $password_confirmation, 'danger', 'Los passwords deben ser iguales');
            $alertas->crearAlerta($termsAndConditions !== 'on', 'danger', 'Debes aceptar los Términos y Condiciones para continuar');
    
            // Verifica si el correo ya está registrado.
            $usuarioExistente = Usuario::encontrarUsuario('correo', $correo);
            $alertas->crearAlerta($usuarioExistente, 'danger', 'El correo ya está registrado');
    
            // Si no hay alertas, procede a registrar el usuario.
            if (!$alertas::obtenerAlertas()) {
    
                $resultado = Usuario::registrarUsuario($documento, $nombre, $correo, $password);
    
                if ($resultado) {
                    // Si el registro es exitoso, busca la información del usuario y guarda sus datos en la sesión.
                    $usuario = Usuario::encontrarUsuario('correo', $correo);
    
                    session_start();
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['documento'] = $usuario['documento'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['correo'] = $usuario['correo'];
    
                    // Redirige al usuario a la página principal.
                    header("Location: /");
                } else {
                    // Si hay un error en el registro, crea una alerta.
                    $alertas->crearAlerta(!$resultado, 'danger', 'Ha ocurrido un error al registrar el usuario, por favor intentalo de nuevo');
                }
            }
        }
    
        // Obtiene las alertas para mostrarlas en la vista.
        $alertas = $alertas::obtenerAlertas();
    
        // Renderiza la vista de registro.
        $router->render('auth/register', [
            "title" => "Registrarse",
            "alertas" => $alertas
        ]);
    }
    
    // Método para manejar el cierre de sesión.
    public static function closeSession() {

        // Verifica si el usuario está autenticado. Si no lo está, lo redirige a la página principal.
        if( !isAuth() ) {
            return header("Location: /");
        }

        // Inicia la sesión, vacía las variables de sesión y destruye la sesión.
        session_start();
        $_SESSION = [];
        session_destroy();
        // Redirige al usuario a la página principal después de cerrar sesión.
        header("Location: /");
    }
    
}

?>
