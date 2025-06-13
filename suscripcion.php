<?php
include 'db_connect.php';

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_email = $_SESSION['email'];
    $plan = $_POST['plan'] ?? '';
    $nombreTarjeta = $_POST['nombreTarjeta'] ?? '';
    $numeroTarjeta = $_POST['numeroTarjeta'] ?? '';
    $mesExpiracion = $_POST['mesExpiracion'] ?? '';
    $anioExpiracion = $_POST['anioExpiracion'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    if (!$plan) {
        echo json_encode(['status' => 'error', 'message' => 'Debe seleccionar un plan.']);
        exit;
    }

    // Obtener usuario_id
    $query_usuario = "SELECT id FROM usuarios WHERE email = '$usuario_email'";
    $result_usuario = $db->query($query_usuario);
    $usuario = $result_usuario->fetchArray(SQLITE3_ASSOC);

    if (!$usuario) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        exit;
    }

    $usuario_id = $usuario['id'];

    // Validar datos para plan premium
    if ($plan === 'premium') {
        if (!$nombreTarjeta || !$numeroTarjeta || !$mesExpiracion || !$anioExpiracion || !$cvv) {
            echo json_encode(['status' => 'error', 'message' => 'Complete todos los datos de pago para el plan Premium.']);
            exit;
        }
        if (!preg_match('/^\d{16}$/', $numeroTarjeta)) {
            echo json_encode(['status' => 'error', 'message' => 'Número de tarjeta inválido.']);
            exit;
        }
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            echo json_encode(['status' => 'error', 'message' => 'CVV inválido.']);
            exit;
        }
    }

    // Calcular fechas de suscripción
    $fecha_inicio = date('Y-m-d H:i:s');
    if ($plan === 'basico') {
        $fecha_fin = date('Y-m-d H:i:s', strtotime('+7 days'));
    } else {
        // Plan premium mensual
        $fecha_fin = date('Y-m-d H:i:s', strtotime('+1 month'));
    }

    // Insertar nueva suscripción y actualizar estado
    $estado = 'pendiente'; // Pendiente hasta confirmación de pago

    // Insertar suscripción
    $query_insert = "INSERT INTO suscripciones (usuario_id, plan, fecha_inicio, fecha_fin, estado) VALUES ($usuario_id, '$plan', '$fecha_inicio', '$fecha_fin', '$estado')";
    $db->exec($query_insert);

    // Enviar notificación a WhatsApp y correo (simulado)
    $mensaje = "Nuevo pago pendiente:\nUsuario: $usuario_email\nPlan: $plan\nFecha inicio: $fecha_inicio\nFecha fin: $fecha_fin\nDatos de pago:\nNombre: $nombreTarjeta\nNúmero tarjeta: $numeroTarjeta\nMes expiración: $mesExpiracion\nAño expiración: $anioExpiracion\nCVV: $cvv";

    // Aquí se debería integrar con API de WhatsApp y correo SMTP para enviar $mensaje y captura de pago

    // Respuesta exitosa
    echo json_encode(['status' => 'success', 'message' => 'Suscripción registrada. Esperando confirmación de pago.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>
