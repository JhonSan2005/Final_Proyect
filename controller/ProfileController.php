<?php

// Requiere los archivos necesarios para el funcionamiento del controlador.

require_once __DIR__ . "/../Router.php";
require_once __DIR__ . "/../model/Usuario.php";
require_once __DIR__ . "/../helpers/functions.php";
require_once __DIR__ . "/../helpers/Alerta.php";

class ProfileController {

    public static function index(Router $router) {
        $isAuth = isAuth();

        if (!$isAuth) {
            return header("Location: /");
        }

        $user = Usuario::encontrarUsuario('id', $_SESSION['id']);

        if (!$user) {
            return header('Location: /close-session');
        }

        $router->render('profile/verPerfil', [
            "title" => "Mi Perfil",
            "profile" => $user,
            "documento" => $user['documento'],
            "nombre" => $user['nombre'],
            "correo" => $user['correo']
        ]);
    }

    public static function actualizar(Router $router) {
        if (!isAuth()) {
            header("Location: /");
            exit;
        }
    
        $resultado = null;
    
        // Crear una instancia de Alerta
        $alerta = new Alerta();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documento = $_POST['documento'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $foto_de_perfil = $_POST['foto_de_perfil'] ?? '';
            $nueva_contrasena = $_POST['nueva_contrasena'] ?? '';
            $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';
    
            // Validar campos
            $alerta->crearAlerta(empty($documento), 'danger', 'El documento no puede ir vacío');
            $alerta->crearAlerta(empty($nombre), 'danger', 'El nombre no puede ir vacío');
            $alerta->crearAlerta(!filter_var($correo, FILTER_VALIDATE_EMAIL), 'danger', 'Correo no válido');
            $alerta->crearAlerta(!empty($nueva_contrasena) && strlen($nueva_contrasena) < 8, 'danger', 'La nueva contraseña debe tener al menos 8 caracteres');
            $alerta->crearAlerta($nueva_contrasena !== $confirmar_contrasena, 'danger', 'Las contraseñas deben coincidir');
    
            // Revisar si se han agregado alertas de tipo 'danger'
            $alertas = $alerta->obtenerAlertas();
            $hayError = false;
            foreach ($alertas as $alert) {
                if ($alert['type'] === 'danger') {
                    $hayError = true;
                    break;
                }
            }
    
            if (!$hayError) {
                $id = $_SESSION['id'] ?? 0;
                $resultado = Usuario::actualizarUsuario($documento, $nombre, $correo, $foto_de_perfil, $id);
    
                if (!empty($nueva_contrasena) && $nueva_contrasena === $confirmar_contrasena) {
                    $resultado = Usuario::actualizarContrasena($nueva_contrasena, $id);
                }
    
                // Añadir una alerta de éxito si todo está bien
                if ($resultado) {
                    $alerta->crearAlerta(true, 'success', 'Datos actualizados correctamente');
                }
            }
        }
    
        $user = Usuario::encontrarUsuario('id', $_SESSION['id']);
    
        $router->render('profile/verPerfil', [
            'title' => 'Perfil Editado',
            'alertas' => $alerta->obtenerAlertas(),  // Pasar alertas a la vista
            'profile' => $user,
            'documento' => $user['documento'],
            'nombre' => $user['nombre'],
            'correo' => $user['correo']
        ]);
    }
    
    
    public static function eliminarcuenta(Router $router)
    {
        // Verificar si el usuario está autenticado
        if (!isAuth()) {
            return header("Location: /404");
        }
    
        // Obtener el ID del usuario desde la solicitud POST
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) ?? null;
    
        // Validar que se proporcionó un ID válido
        if ($id === null) {
            return header("Location: /404"); // Redirigir si no se proporciona un ID
        }
    
        // Llamar al método para eliminar la cuenta del usuario
        $result = Usuario::eliminarcuentauser($id);
    
        // Verificar si la eliminación fue exitosa
        if ($result) {
            // Redirigir a la página de inicio o mostrar un mensaje de éxito
            return header("Location: /");
        } else {
            // Manejar el caso donde la eliminación falla
            $error = "Error al eliminar la cuenta.";
            $router->render("profile/verPerfil", [
                "title" => "Perfil",
                "error" => $error
            ]);
        }
    }
    
    


 

    public static function actualizarpassword(Router $router) {
        if (!isAuth()) {
            header("Location: /");
            exit;
        }

        // Aquí iría la lógica para restablecer la contraseña
        // Por ejemplo, enviar un correo electrónico con un enlace seguro, etc.

        // Renderizar la vista para restablecer la contraseña
        $router->render('profile/contraseña', [
            'title' => 'Restablecer Contraseña'
        ]);
    }
  
    public static function actualizarAdmin(Router $router) {
        if (!isAuth()) {
            header("Location: /");
            exit;
        }
    
        $resultado = null;
    
        // Crear una instancia de Alerta
        $alerta = new Alerta();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documento = $_POST['documento'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $foto_de_perfil = $_POST['foto_de_perfil'] ?? '';
            $nueva_contrasena = $_POST['nueva_contrasena'] ?? '';
            $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';
    
            // Validar campos
            $alerta->crearAlerta(empty($documento), 'danger', 'El documento no puede ir vacío');
            $alerta->crearAlerta(empty($nombre), 'danger', 'El nombre no puede ir vacío');
            $alerta->crearAlerta(!filter_var($correo, FILTER_VALIDATE_EMAIL), 'danger', 'Correo no válido');
            $alerta->crearAlerta(!empty($nueva_contrasena) && strlen($nueva_contrasena) < 8, 'danger', 'La nueva contraseña debe tener al menos 8 caracteres');
            $alerta->crearAlerta($nueva_contrasena !== $confirmar_contrasena, 'danger', 'Las contraseñas deben coincidir');
    
            // Revisar si se han agregado alertas de tipo 'danger'
            $alertas = $alerta->obtenerAlertas();
            $hayError = false;
            foreach ($alertas as $alert) {
                if ($alert['type'] === 'danger') {
                    $hayError = true;
                    break;
                }
            }
    
            if (!$hayError) {
                $id = $_SESSION['id'] ?? 0;
                $resultado = Usuario::actualizarUsuario($documento, $nombre, $correo, $foto_de_perfil, $id);
    
                if (!empty($nueva_contrasena) && $nueva_contrasena === $confirmar_contrasena) {
                    $resultado = Usuario::actualizarContrasena($nueva_contrasena, $id);
                }
    
                // Añadir una alerta de éxito si todo está bien
                if ($resultado) {
                    $alerta->crearAlerta(true, 'success', 'Datos actualizados correctamente');
                }
            }
        }
    
        $user = Usuario::encontrarUsuario('id', $_SESSION['id']);
    
        // Renderiza la vista con los datos del usuario
        $router->render('/profile/adminPerfil', [
            'title' => 'Actualizar Perfil de Administrador',
            'alertas' => $alerta->obtenerAlertas(),  // Pasar alertas a la vista
            'resultado' => $resultado,
            'documento' => $user['documento'],
            'nombre' => $user['nombre'],
            'correo' => $user['correo'],
        ]);
    }
    
    public static function verterminos( Router $router ) {

        $router->render('/terminos/terminosCondiciones', [
            "title" => "Terminos"
        ]);

    
    

}
    
}

?>
