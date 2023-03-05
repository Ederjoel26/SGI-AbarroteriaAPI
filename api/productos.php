<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "sgi");

// Endpoint para obtener todos los productos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    obtener_productos();
}

// Endpoint para agregar un nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    agregar_producto();
}

// Endpoint para obtener un producto por su ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    obtener_producto_por_id($_GET['id']);
}

// Endpoint para actualizar un producto
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    actualizar_producto($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    eliminar_producto($_GET['id']);
}

// Función para obtener todos los productos
function obtener_productos() {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT p.id, p.producto, p.cantidad, pr.nombre AS proveedor, p.costo_unitario FROM productos p INNER JOIN proveedores pr ON p.proveedor = pr.id;");

    // Construye un array con los resultados
    $productos = array();
    while ($fila = mysqli_fetch_assoc($consulta)) {
        $productos[] = $fila;
    }

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    echo json_encode($productos);
}

// Función para obtener un producto por su ID
function obtener_producto_por_id($id) {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT p.id, p.producto, p.cantidad, pr.nombre AS proveedor, p.costo_unitario FROM productos p INNER JOIN proveedores pr ON p.proveedor = pr.id WHERE p.id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($fila = mysqli_fetch_assoc($consulta)) {
        echo json_encode($fila);
    } else {
        echo json_encode(array('error' => 'No se encontró el producto'));
    }
}

// Función para agregar un nuevo producto
function agregar_producto() {
    global $conexion;

    // Obtiene los datos enviados en el cuerpo de la petición
    $datos = json_decode(file_get_contents("php://input"), true);

    // Valida que se hayan enviado los datos requeridos
    if (empty($datos['producto']) || empty($datos['cantidad']) || empty($datos['proveedor']) || empty($datos['costo_unitario'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $producto = mysqli_real_escape_string($conexion, $datos['producto']);
    $cantidad = mysqli_real_escape_string($conexion, $datos['cantidad']);
    $proveedor = mysqli_real_escape_string($conexion, $datos['proveedor']);
    $costo_unitario = mysqli_real_escape_string($conexion, $datos['costo_unitario']);


    // Inserta el nuevo producto en la base de datos
    $consulta = mysqli_query($conexion, "INSERT INTO productos (producto, cantidad, proveedor, costo_unitario) VALUES ('$producto', $cantidad, $proveedor, $costo_unitario)");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($consulta) {
        echo json_encode(array('mensaje' => 'producto agregado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al agregar el producto'));
    }
}

function actualizar_producto($id) {
    global $conexion;

    // Obtiene los datos enviados en el cuerpo de la petición
    $datos = json_decode(file_get_contents("php://input"), true);

    // Valida que se hayan enviado los datos requeridos
    if (empty($datos['producto']) || empty($datos['cantidad']) || empty($datos['proveedor']) || empty($datos['costo_unitario'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $producto = mysqli_real_escape_string($conexion, $datos['producto']);
    $cantidad = mysqli_real_escape_string($conexion, $datos['cantidad']);
    $proveedor = mysqli_real_escape_string($conexion, $datos['proveedor']);
    $costo_unitario = mysqli_real_escape_string($conexion, $datos['costo_unitario']);

    // Actualiza el producto en la base de datos
    $consulta = mysqli_query($conexion, "UPDATE productos SET producto = '$producto', cantidad = $cantidad, proveedor = $proveedor, costo_unitario = $costo_unitario WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($consulta) {
        echo json_encode(array('mensaje' => 'producto agregado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al agregar el producto'));
    }
}

function eliminar_producto($id) {
    global $conexion;

    // Elimina el producto de la base de datos
    $consulta = mysqli_query($conexion, "DELETE FROM productos WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");

    if ($consulta) {
        echo json_encode(array('mensaje' => 'producto agregado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al agregar el producto'));
    }
}

?>