<?php
session_start();

// Database connection configuration
$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$passwordDB = "19861997.Sr";
$cs = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$passwordDB;CharacterSet=UTF8;";

$conn = odbc_connect($cs, '', '');

if (empty($_GET['identificacion'])) {
  die("Falta identificación.");
}

$id = $_GET['identificacion'];
$sql = "SELECT usuario, nombre, apellido1, apellido2, correo, telefono 
        FROM atletas 
        WHERE identificacion = ?";
$stmt = odbc_prepare($conn, $sql);
odbc_execute($stmt, [$id]);

if (!$row = odbc_fetch_array($stmt)) {
  die("Atleta no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Atleta</title>
</head>
<body>
  <h2>Editar Atleta <?= htmlspecialchars($id) ?></h2>
  <form action="ActualizarAtleta.php" method="post" novalidate>
    <!-- Campo oculto para saber qué registro actualizar -->
    <input type="hidden" name="identificacion_old" value="<?= htmlspecialchars($id) ?>">

    <label for="identificacion">Identificación</label>
    <input type="text" id="identificacion" name="identificacion_new"
           pattern="[0-9]{9,12}" required
           value="<?= htmlspecialchars($id) ?>">

    <label for="usuario">Usuario</label>
    <input type="text" id="usuario" name="usuario" minlength="4" required
           value="<?= htmlspecialchars($row['usuario']) ?>">

    <label for="password">Nueva Contraseña (opcional)</label>
    <input type="password" id="password" name="password" minlength="6"
           placeholder="Solo si deseas cambiarla">

    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" required
           value="<?= htmlspecialchars($row['nombre']) ?>">

    <label for="apellido1">Primer Apellido</label>
    <input type="text" id="apellido1" name="apellido1" required
           value="<?= htmlspecialchars($row['apellido1']) ?>">

    <label for="apellido2">Segundo Apellido</label>
    <input type="text" id="apellido2" name="apellido2" required
           value="<?= htmlspecialchars($row['apellido2']) ?>">

    <label for="correo">Correo Electrónico</label>
    <input type="email" id="correo" name="correo" required
           value="<?= htmlspecialchars($row['correo']) ?>">

    <label for="telefono">Teléfono</label>
    <input type="tel" id="telefono" name="telefono" pattern="[0-9]{8}" required
           value="<?= htmlspecialchars($row['telefono']) ?>">

    <button type="submit">Guardar Cambios</button>
  </form>
</body>
</html>
<?php odbc_close($conn); ?>
