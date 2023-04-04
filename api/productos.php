<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "sgi");
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])){
    actualizar_producto($_GET['id']);
}
elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['idDelete'])){
    eliminar_producto($_GET['idDelete']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    agregar_producto();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    obtener_producto_por_id($_GET['id']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    obtener_productos();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    actualizar_producto($_GET['id']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    eliminar_producto($_GET['id']);
}

// Función para obtener todos los productos
function obtener_productos() {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT 
                                            p.id, 
                                            p.nombre, 
                                            p.descripcion, 
                                            p.codigo_barras, 
                                            p.sku, 
                                            p.precio, 
                                            p.cantidad_stock, 
                                            p.categoria, 
                                            pr.nombre AS proveedor_nombre, 
                                            p.fecha_entrada 
                                        FROM productos p 
                                        INNER JOIN proveedores pr ON p.proveedor = pr.id;");

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
    $consulta = mysqli_query($conexion, "SELECT 
                                            p.id, 
                                            p.nombre, 
                                            p.descripcion, 
                                            p.codigo_barras, 
                                            p.sku, 
                                            p.precio, 
                                            p.cantidad_stock, 
                                            p.categoria, 
                                            pr.nombre AS proveedor_nombre, 
                                            p.fecha_entrada 
                                        FROM productos p 
                                        INNER JOIN proveedores pr ON p.proveedor = pr.id
                                        WHERE p.id = $id");

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
    if (empty($datos['nombre']) || empty($datos['descripcion']) || empty($datos['codigo_barras']) || empty($datos['sku'] || empty($datos['precio'] || empty($datos['cantidad_stock']) || empty($datos['categoria']) || empty($datos['proveedor']) || empty($datos['fecha_entrada'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $nombre = mysqli_real_escape_string($conexion, $datos['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $datos['descripcion']);
    $codigo_barras = mysqli_real_escape_string($conexion, $datos['codigo_barras']);
    $sku = mysqli_real_escape_string($conexion, $datos['sku']);
    $precio = mysqli_real_escape_string($conexion, $datos['precio']);
    $cantidad_stock = mysqli_real_escape_string($conexion, $datos['cantidad_stock']);
    $categoria = mysqli_real_escape_string($conexion, $datos['categoria']);
    $proveedor = mysqli_real_escape_string($conexion, $datos['proveedor']);
    $fecha_entrada = mysqli_real_escape_string($conexion, $datos['fecha_entrada']);


    // Inserta el nuevo producto en la base de datos
    $consulta = mysqli_query($conexion, "INSERT INTO productos 
                                                    (nombre, 
                                                    descripcion, 
                                                    codigo_barras, 
                                                    sku, precio, 
                                                    cantidad_stock, 
                                                    categoria, 
                                                    proveedor, 
                                                    fecha_entrada) 
                                                VALUES 
                                                    ('$nombre', 
                                                    $descripcion, 
                                                    $codigo_barras, 
                                                    $sku, 
                                                    $precio, 
                                                    $cantidad_stock, 
                                                    '$categoria', 
                                                    $proveedor, 
                                                    '$fecha_entrada'))");

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
   if (empty($datos['nombre']) || empty($datos['descripcion']) || empty($datos['codigo_barras']) || empty($datos['sku'] || empty($datos['precio'] || empty($datos['cantidad_stock']) || empty($datos['categoria']) || empty($datos['proveedor']) || empty($datos['fecha_entrada'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $nombre = mysqli_real_escape_string($conexion, $datos['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $datos['descripcion']);
    $codigo_barras = mysqli_real_escape_string($conexion, $datos['codigo_barras']);
    $sku = mysqli_real_escape_string($conexion, $datos['sku']);
    $precio = mysqli_real_escape_string($conexion, $datos['precio']);
    $cantidad_stock = mysqli_real_escape_string($conexion, $datos['cantidad_stock']);
    $categoria = mysqli_real_escape_string($conexion, $datos['categoria']);
    $proveedor = mysqli_real_escape_string($conexion, $datos['proveedor']);
    $fecha_entrada = mysqli_real_escape_string($conexion, $datos['fecha_entrada']);

    // Actualiza el producto en la base de datos
    $consulta = mysqli_query($conexion, "UPDATE productos SET 
                                                    nombre = '$nombre', 
                                                    descripcion = '$descripcion', 
                                                    codigo_barras = '$codigo_barras', 
                                                    sku = '$sku', 
                                                    precio = $precio, 
                                                    antidad_stock = $cantidad_stock, 
                                                    categoria = '$categoria', 
                                                    proveedor = $proveedor, 
                                                    fecha_entrada = '$fecha_entrada' 
                                                    WHERE id = $id");

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