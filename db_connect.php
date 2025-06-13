<?php
$db_path = './base de datos/base de datos.db';

try {
  $db = new SQLite3($db_path);
} catch (Exception $e) {
  die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>
