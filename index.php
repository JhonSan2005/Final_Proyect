<?php
// Incluir los archivos necesarios
require_once './Router.php';
require_once './config/Conexion_db.php'; // Asegúrate de incluir la conexión a la base de datos
require_once './controller/BuscadorController.php';
require_once './controller/ProductController.php';
require_once './controller/HomeController.php';
require_once './controller/AuthController.php';
require_once './controller/ProfileController.php';
require_once './controller/RecoverController.php';
require_once './controller/DashboardController.php';
require_once './controller/CategoryController.php';
require_once './controller/CarritoController.php';
require_once './controller/DevolucionController.php';
require_once './controller/HistoryController.php';
require_once './controller/VentaController.php';
require_once './controller/FormaPagoController.php';


// Crear una instancia del Router
$router = new Router();

// Configuración de encabezados para CORS (ajusta según tus necesidades)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

// Verificar si la base de datos está instalada
$servername = "127.0.0.1";
$username = "root"; // Reemplaza con tu usuario de MySQL
$password = ""; // Reemplaza con tu contraseña de MySQL

try {
    // Intenta conectar con la base de datos "bd_jj"
    $conn = new PDO("mysql:host=$servername;dbname=bd_jj", $username, $password);
    // Configura el manejo de errores PDO para que lance excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Si hay un error de conexión, redirige al instalador
    header("location: instalador.php");
    exit;
}

// Registrar rutas públicas (acceso público)
$router->get('/', [HomeController::class, 'index']); // Página de inicio
$router->get('/products', [ProductController::class, 'index']); 
$router->get('/search', [BuscadorController::class, 'buscar']); // Página de búsqueda
$router->get('/category', [ProductController::class, 'category']); 
$router->get('/carrito', [CarritoController::class, 'index']);
$router->get('/devolucion', [DevolucionController::class, 'devolucion']);
$router->get('/history', [HistoryController::class, 'history']);
$router->get('/formaPago', [VentaController::class, 'index']);


$router->post('/api/search-product', [CarritoController::class, 'obtenerInfoProducto']);

// Rutas de autenticación
$router->get('/login', [AuthController::class, 'login']); 
$router->post('/login', [AuthController::class, 'login']); 
$router->get('/register', [AuthController::class, 'register']); 
$router->post('/register', [AuthController::class, 'register']); 
$router->get('/recover', [RecoverController::class, 'recover']); 
$router->post('/recover', [RecoverController::class, 'recover']); 
$router->post('/recover/recovernew', [RecoverController::class, 'actualizarPassword']);
$router->get('/recover/recovernew', [RecoverController::class, 'actualizarPassword']);
$router->get('/formaPago', [FormaPagoController::class, 'formaPago']);
$router->get('/close-session', [AuthController::class, 'closeSession']); // Cerrar sesión

// Registrar rutas privadas (acceso restringido)
$router->get('/profile', [ProfileController::class, 'index']); 
$router->post('/profile/verPerfil', [ProfileController::class, 'actualizar']); // Manejo de actualización (POST)
$router->get('/profile/contraseña', [ProfileController::class, 'actualizarpassword']); 
$router->post('/profile/contraseña', [ProfileController::class, 'actualizarpassword']); 

$router->post('/api/venta', [VentaController::class, 'vender']);

// Solo Administrador
$router->get('/admin/dashboard', [DashboardController::class, 'index']);
$router->get('/admin/tablaUser', [DashboardController::class, 'tablaUser']);

$router->get('/admin/verHistorial', [HistoryController::class, 'history']);


$router->get('/products/actualizarProducto', [ProductController::class, 'actualizarproducto']);
$router->post('/products/actualizarProducto', [ProductController::class, 'actualizarproducto']);
$router->get('/admin/products', [ProductController::class, 'verProductosAdmin']);
$router->post('/admin/products', [ProductController::class, 'eliminarproductoadmin']);
$router->get('/admin/agregarProductos', [ProductController::class, 'agregar']); 
$router->post('/admin/agregarProductos', [ProductController::class, 'agregar']); 
$router->get('/admin/orders', [DashboardController::class, 'index']);

$router->get('/admin/categories', [CategoryController::class, 'verCategorias']);
$router->post('/admin/categories', [CategoryController::class, 'eliminarCategoriaAdmin']); 
$router->get('/admin/agregarCategoria', [CategoryController::class, 'agregarCategoria']);
$router->post('/admin/agregarCategoria', [CategoryController::class, 'agregarCategoria']); 
$router->get('/categories/actualizarCategoria', [CategoryController::class, 'actualizarcategoria']);
$router->post('/categories/actualizarCategoria', [CategoryController::class, 'actualizarcategoria']);

$router->get('/misCompras', [HistoryController::class, 'verCompras']);

$router->get('/profile/devolucion', [DevolucionController::class, 'devolucion']);
$router->post('/procesarDevolucion', [DevolucionController::class, 'procesarDevolucion']);
$router->get('/procesarDevolucion', [DevolucionController::class, 'procesarDevolucion']);

$router->get('/admin/adminPerfil', [ProfileController::class, 'actualizarAdmin']);
$router->post('/admin/adminPerfil', [ProfileController::class, 'actualizarAdmin']);



// Verificar y ejecutar la ruta actual
$router->verifyRoutes();
?>
