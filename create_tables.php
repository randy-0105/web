<?php
include 'db_connect.php';

$query_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    nombre TEXT,
    tipo_plan TEXT DEFAULT 'basico'
)";

$query_suscripciones = "CREATE TABLE IF NOT EXISTS suscripciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    plan TEXT NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    estado TEXT DEFAULT 'activo',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
)";

try {
    $db->exec($query_usuarios);
    $db->exec($query_suscripciones);
    echo "Tablas 'usuarios' y 'suscripciones' creadas correctamente.";
} catch (Exception $e) {
    die("Error al crear las tablas: " . $e->getMessage());
}

?>
