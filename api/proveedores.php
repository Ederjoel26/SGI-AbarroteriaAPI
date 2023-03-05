<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "sgi");

// Endpoint para obtener todos los proveedores

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    obtener_proveedor_por_id($_GET['id']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    obtener_proveedores();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    agregar_proveedor();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    actualizar_proveedor($_GET['id']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    eliminar_proveedor($_GET['id']);
}

// Función para obtener todos los proveedores
function obtener_proveedores() {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT * FROM proveedores");

    // Construye un array con los resultados
    $proveedores = array();
    while ($fila = mysqli_fetch_assoc($consulta)) {
        $proveedores[] = $fila;
    }

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    echo json_encode($proveedores);
}

// Función para obtener un proveedor por su ID
function obtener_proveedor_por_id($id) {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT * FROM proveedores WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($fila = mysqli_fetch_assoc($consulta)) {
        echo json_encode($fila);
    } else {
        echo json_encode(array('error' => 'No se encontró el proveedor'));
    }
}

// Función para agregar un nuevo proveedor
function agregar_proveedor() {
    global $conexion;

    // Obtiene los datos enviados en el cuerpo de la petición
    $datos = json_decode(file_get_contents("php://input"), true);

    // Valida que se hayan enviado los datos requeridos
    if (empty($datos['proveedor']) || empty($datos['nombre']) || empty($datos['producto']) || empty($datos['precio_unitario']) || empty($datos['costo_unitario']) || empty($datos['numero_telefonico'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $proveedor = mysqli_real_escape_string($conexion, $datos['proveedor']);
    $nombre = mysqli_real_escape_string($conexion, $datos['nombre']);
    $producto = mysqli_real_escape_string($conexion, $datos['producto']);
    $precio_unitario = mysqli_real_escape_string($conexion, $datos['precio_unitario']);
    $costo_unitario = mysqli_real_escape_string($conexion, $datos['costo_unitario']);
    $numero_telefonico = mysqli_real_escape_string($conexion, $datos['numero_telefonico']);

    // Inserta el nuevo proveedor en la base de datos
    $consulta = mysqli_query($conexion, "INSERT INTO proveedores (proveedor, nombre, producto, precio_unitario, costo_unitario, numero_telefonico) 
    VALUES ('$proveedor', '$nombre', '$producto', $precio_unitario, $costo_unitario, '$numero_telefonico')");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($consulta) {
        echo json_encode(array('mensaje' => 'Proveedor agregado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al agregar el proveedor'));
    }
}

function actualizar_proveedor($id) {
    global $conexion;

    // Obtiene los datos enviados en el cuerpo de la petición
    $datos = json_decode(file_get_contents("php://input"), true);

    // Valida que se hayan enviado los datos requeridos
    if (empty($datos['proveedor']) || empty($datos['nombre']) || empty($datos['producto']) || empty($datos["precio_unitario"]) || empty($datos["costo_unitario"]) || empty($datos["numero_telefonico"])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $proveedor = mysqli_real_escape_string($conexion, $datos['proveedor']);
    $nombre = mysqli_real_escape_string($conexion, $datos['nombre']);
    $producto = mysqli_real_escape_string($conexion, $datos['producto']);
    $precio_unitario = mysqli_real_escape_string($conexion, $datos["precio_unitario"]);
    $costo_unitario = mysqli_real_escape_string($conexion, $datos["costo_unitario"]);
    $numero_telefonico = mysqli_real_escape_string($conexion, $datos["numero_telefonico"]);

    // Actualiza el proveedor en la base de datos
    $consulta = mysqli_query($conexion, "UPDATE proveedores 
                                        SET proveedor = '$proveedor', nombre = '$nombre', producto = '$producto', precio_unitario = $precio_unitario, costo_unitario = $costo_unitario, numero_telefonico = '$numero_telefonico'
                                        WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($consulta) {
        echo json_encode(array('mensaje' => 'Proveedor agregado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al agregar el proveedor'));
    }
}

function eliminar_proveedor($id) {
    global $conexion;

    // Elimina el proveedor de la base de datos
    $consulta = mysqli_query($conexion, "DELETE FROM proveedores WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");

    if ($consulta) {
        echo json_encode(array('mensaje' => 'Proveedor agregado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al agregar el proveedor'));
    }
}
?>