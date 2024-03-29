<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "sgi");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) ) {
    obtener_usuario_por_id($_GET['id']);
}
elseif($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["correo"])){
    obtener_usuario_por_correo($_GET["correo"]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    obtener_usuarios();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    actualizar_usuario($_GET['id']);
}
elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['idDelete'])){
    eliminar_usuario($_GET['idDelete']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    agregar_usuario();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    eliminar_usuario($_GET['id']);
}

// Función para obtener todos los usuarios
function obtener_usuarios() {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT * FROM usuarios");

    // Construye un array con los resultados
    $usuarios = array();
    while ($fila = mysqli_fetch_assoc($consulta)) {
        $usuarios[] = $fila;
    }

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    echo json_encode($usuarios);
}

// Función para obtener un usuario por su ID
function obtener_usuario_por_id($id) {
    global $conexion;

    // Realiza la consulta a la base de datos
    $consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($fila = mysqli_fetch_assoc($consulta)) {
        echo json_encode($fila);
    } else {
        echo json_encode(array('error' => 'No se encontró el usuario'));
    }
}

function obtener_usuario_por_correo($correo){
    global $conexion;

    $consulta = mysqli_query($conexion,"SELECT * FROM usuarios WHERE correo = '$correo'");

    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($fila = mysqli_fetch_assoc($consulta)) {
        echo json_encode($fila);
    } else {
        echo json_encode(array('error' => 'No se encontró el usuario'));
    }
}

// Función para agregar un nuevo usuario
function agregar_usuario() {
    global $conexion;

    // Obtiene los datos enviados en el cuerpo de la petición
    $datos = json_decode(file_get_contents("php://input"), true);

    // Valida que se hayan enviado los datos requeridos
    if (empty($datos['nombre_usuario']) || empty($datos['nombre_completo']) || empty($datos['correo']) || empty($datos['contra']) || empty($datos['fecha_nacimiento']) || empty($datos['direccion']) || empty($datos['numero_telefono']) || empty($datos['rol_usuario'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $nombre_usuario = mysqli_real_escape_string($conexion, $datos['nombre_usuario']);
    $nombre_completo = mysqli_real_escape_string($conexion, $datos['nombre_completo']);
    $correo = mysqli_real_escape_string($conexion, $datos['correo']);
    $contra = mysqli_real_escape_string($conexion, $datos['contra']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $datos['fecha_nacimiento']);
    $direccion = mysqli_real_escape_string($conexion, $datos['direccion']);
    $numero_telefono = mysqli_real_escape_string($conexion, $datos['numero_telefono']);
    $rol_usuario = mysqli_real_escape_string($conexion, $datos['rol_usuario']);

    // Inserta el nuevo usuario en la base de datos
    $consulta = mysqli_query($conexion, "INSERT INTO usuarios (nombre_usuario, nombre_completo, correo, contra, fecha_nacimiento, direccion, numero_telefono, rol_usuario) VALUES ('$nombre_usuario', '$nombre_completo', '$correo', '$contra', '$fecha_nacimiento', '$direccion', '$numero_telefono', '$rol_usuario')");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");
    if ($consulta) {
        echo json_encode(array('mensaje' => 'Usuario agregado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al agregar el usuario'));
    }
}

function actualizar_usuario($id) {
    global $conexion;

    // Obtiene los datos enviados en el cuerpo de la petición
    $datos = json_decode(file_get_contents("php://input"), true);

    // Valida que se hayan enviado los datos requeridos
    if (empty($datos['nombre_usuario']) || empty($datos['nombre_completo']) || empty($datos['correo']) || empty($datos['contra']) || empty($datos['fecha_nacimiento']) || empty($datos['direccion']) || empty($datos['numero_telefono']) || empty($datos['rol_usuario'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Faltan datos requeridos'));
        return;
    }

    // Escapa los datos para prevenir SQL injection
    $nombre_usuario = mysqli_real_escape_string($conexion, $datos['nombre_usuario']);
    $nombre_completo = mysqli_real_escape_string($conexion, $datos['nombre_completo']);
    $correo = mysqli_real_escape_string($conexion, $datos['correo']);
    $contra = mysqli_real_escape_string($conexion, $datos['contra']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $datos['fecha_nacimiento']);
    $direccion = mysqli_real_escape_string($conexion, $datos['direccion']);
    $numero_telefono = mysqli_real_escape_string($conexion, $datos['numero_telefono']);
    $rol_usuario = mysqli_real_escape_string($conexion, $datos['rol_usuario']);

    // Actualiza el usuario en la base de datos
    $consulta = mysqli_query($conexion, "UPDATE usuarios SET nombre_usuario = '$nombre_usuario', nombre_completo = '$nombre_completo', correo = '$correo', contra = '$contra', fecha_nacimiento = '$fecha_nacimiento', direccion = '$direccion', numero_telefono = '$numero_telefono', rol_usuario = '$rol_usuario' WHERE id = $id");

    // Devuelve una respuesta en formato JSON
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

    if ($consulta) {
        echo json_encode(array('mensaje' => 'Usuario actualizado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al actualizar el usuario'));
    }
}

function eliminar_usuario($id) {
    global $conexion;

    // Elimina el usuario de la base de datos
    $consulta = mysqli_query($conexion, "DELETE FROM usuarios WHERE id=$id");

    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization");

    if ($consulta) {
        echo json_encode(array('mensaje' => 'Usuario eliminado correctamente'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Error al eliminar el usuario'));
    }
}

?>
