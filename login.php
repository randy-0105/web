<?php
include 'db_connect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Retrieve the user from the database
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $db->query($query);

    if ($result) {
        $user = $result->fetchArray(SQLITE3_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Obtener la suscripción activa del usuario
            $usuario_id = $user['id'];
            $query_suscripcion = "SELECT * FROM suscripciones WHERE usuario_id = $usuario_id AND estado = 'activo' ORDER BY fecha_fin DESC LIMIT 1";
            $result_suscripcion = $db->query($query_suscripcion);
            $suscripcion = $result_suscripcion->fetchArray(SQLITE3_ASSOC);

            if ($suscripcion) {
                // Password is correct, so start a new session
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['tipo_plan'] = $suscripcion['plan'];
                $_SESSION['fecha_inicio'] = $suscripcion['fecha_inicio'];
                $_SESSION['fecha_fin'] = $suscripcion['fecha_fin'];
                $_SESSION['estado_pago'] = $suscripcion['estado'];

                // Redirect to the index page or a logged-in page
                header("location: index.html");
            } else {
                echo "No se encontró una suscripción activa. Por favor suscríbase.";
            }
        } else {
            // Display an error message if password is not valid
            echo "Correo electrónico o contraseña incorrectos.";
        }
    } else {
        // Display an error message if email doesn't exist
        echo "Correo electrónico o contraseña incorrectos.";
    }
}
?>
