<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["registerEmail"];
    $password = $_POST["registerPassword"];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $query = "INSERT INTO usuarios (email, password) VALUES ('$email', '$hashed_password')";

    try {
        $db->exec($query);

        // Obtener el id del usuario recién insertado
        $usuario_id = $db->lastInsertRowID();

        // Insertar suscripción básica activa con fecha de inicio actual y fecha fin una semana después
        $fecha_inicio = date('Y-m-d H:i:s');
        $fecha_fin = date('Y-m-d H:i:s', strtotime('+7 days'));
        $plan = 'basico';
        $estado = 'activo';

        $query_suscripcion = "INSERT INTO suscripciones (usuario_id, plan, fecha_inicio, fecha_fin, estado) VALUES ($usuario_id, '$plan', '$fecha_inicio', '$fecha_fin', '$estado')";
        $db->exec($query_suscripcion);

        echo "Usuario registrado exitosamente.";
    } catch (Exception $e) {
        die("Error al registrar el usuario: " . $e->getMessage());
    }
}
?>
