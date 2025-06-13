<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html");
    exit;
}

$tipo_plan = $_SESSION['tipo_plan'];
$fecha_fin = $_SESSION['fecha_fin'];
$estado_pago = $_SESSION['estado_pago'];

// Define las páginas a las que los usuarios básicos tienen acceso
$paginas_basicas = array("index.html", "contacto.html", "parenthood/consejos.html", "ninos/Juegos_Educativos/juegos.html");

// Obtiene la página actual
$pagina_actual = basename($_SERVER['PHP_SELF']);

// Verificar si la suscripción está activa y no ha expirado
$fecha_actual = date('Y-m-d H:i:s');
if ($estado_pago != 'activo' || $fecha_actual > $fecha_fin) {
    // Si la suscripción no está activa o ha expirado, redirigir a la página de suscripción
    header("location: suscripcion.html");
    exit;
}

// Si el usuario es básico y está intentando acceder a una página que no está en la lista de páginas básicas, redirige a la página de suscripción
if ($tipo_plan == 'basico' && !in_array($pagina_actual, $paginas_basicas)) {
    header("location: suscripcion.html");
    exit;
}
?>
