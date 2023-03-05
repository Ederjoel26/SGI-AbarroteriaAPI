<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "sgi");

// Endpoint para obtener todos los notas

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    obtener_nota_por_id($_GET['id']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    obtener_notas();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    agregar_nota();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    actualizar_nota($_GET['id']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    eliminar_nota($_GET['id']);
}

// Función para obtener todos los notas
function obtener_notas() {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT * FROM notas");

    // Construye un array con los resultados
    $notas = array();
    while ($fila = mysqli_fetch_assoc($consulta)) {
        $notas[] = $fila;
    }

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    echo json_encode($notas);
}

// Función para obtener un nota por su ID
function obtener_nota_por_id($id) {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT * FROM notas WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    
    if ($fila = mysqli_fetch_assoc($consulta)) {
        echo json_encode($fila);
    } else {
        echo json_encode(array('error' => 'No se encontró el nota'));
    }
}

// Función para agregar un nuevo nota
function agregar_nota() {
    global $conexion;

    // Obtiene los datos enviados en el cuerpo de la petición
    $datos = json_decode(file_get_contents("php://input"), true);

    // Valida que se hayan enviado los datos requeridos
    if (empty($datos['nota']) || empty($datos['fecha_limite']) || empty($datos['descripcion']) || empty($datos['realizado'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $nota = mysqli_real_escape_string($conexion, $datos['nota']);
    $fecha_limite = mysqli_real_escape_string($conexion, $datos['fecha_limite']);
    $descripcion = mysqli_real_escape_string($conexion, $datos['descripcion']);
    $realizado = mysqli_real_escape_string($conexion, $datos['realizado']);

    // Inserta el nuevo nota en la base de datos
    $consulta = mysqli_query($conexion, "INSERT INTO notas (nota, fecha_limite, descripcion, realizado) VALUES ('$nota', '$fecha_limite', '$descripcion', '$realizado')");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($consulta) {
        echo json_encode(array('mensaje' => 'nota agregado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al agregar el nota'));
    }
}

function actualizar_nota($id) {
    global $conexion;

    // Obtiene los datos enviados en el cuerpo de la petición
    $datos = json_decode(file_get_contents("php://input"), true);

    // Valida que se hayan enviado los datos requeridos
    if (empty($datos['nota']) || empty($datos['fecha_limite']) || empty($datos['descripcion']) || empty($datos['realizado'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $nota = mysqli_real_escape_string($conexion, $datos['nota']);
    $fecha_limite = mysqli_real_escape_string($conexion, $datos['fecha_limite']);
    $descripcion = mysqli_real_escape_string($conexion, $datos['descripcion']);
    $realizado = mysqli_real_escape_string($conexion, $datos['realizado']);

    // Actualiza el nota en la base de datos
    $consulta = mysqli_query($conexion, "UPDATE notas SET nota='$nota', fecha_limite='$fecha_limite', descripcion='$descripcion', realizado='$realizado' WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($consulta) {
        echo json_encode(array('mensaje' => 'nota actualizado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al actualizar el nota'));
    }
}

function eliminar_nota($id) {
    global $conexion;

    // Elimina el nota de la base de datos
    $consulta = mysqli_query($conexion, "DELETE FROM notas WHERE id=$id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($consulta) {
        echo json_encode(array('mensaje' => 'nota eliminado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al eliminar el nota'));
    }
}
?>
