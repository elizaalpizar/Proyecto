<?php
require_once '../Php/Session.php';
verificarSesion();

// Database connection configuration
$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$passwordDB = "19861997.Sr";
$cs = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$passwordDB;CharacterSet=UTF8;";

$conn = odbc_connect($cs, '', '') or die('Error de conexión');

$stmt = odbc_prepare($conn, "SELECT usuario, nombre, apellido1, apellido2, correo, telefono 
                             FROM atletas WHERE identificacion = ?");
odbc_execute($stmt, [$_SESSION['identificacion']]);
$row = odbc_fetch_array($stmt);
odbc_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Mi Perfil – Energym</title>
  <link rel="stylesheet" href="../Css/Registro.css">
</head>
<body>
  <header>… nav atleta …</header>

  <main>
    <h2>Mi Perfil</h2>
    <form id="formActualizar" action="../Php/ActualizarAtleta.php" method="POST" novalidate>
      <label for="identificacion">Identificación</label>
      <input type="text" id="identificacion" name="identificacion"
             value="<?php echo htmlspecialchars($_SESSION['identificacion']); ?>" readonly />

      <label for="usuario">Usuario</label>
      <input type="text" id="usuario" name="usuario" required minlength="4"
             value="<?php echo htmlspecialchars($row['usuario']); ?>" />

      <label for="password">Nueva Contraseña</label>
      <input type="password" id="password" name="password" minlength="6"
             placeholder="Dejar en blanco para no cambiar" />

      <label for="nombre">Nombre</label>
      <input type="text" id="nombre" name="nombre" required
             value="<?php echo htmlspecialchars($row['nombre']); ?>" />

      <label for="apellido1">Primer Apellido</label>
      <input type="text" id="apellido1" name="apellido1" required
             value="<?php echo htmlspecialchars($row['apellido1']); ?>" />

      <label for="apellido2">Segundo Apellido</label>
      <input type="text" id="apellido2" name="apellido2" required
             value="<?php echo htmlspecialchars($row['apellido2']); ?>" />

      <label for="correo">Correo Electrónico</label>
      <input type="email" id="correo" name="correo" required
             value="<?php echo htmlspecialchars($row['correo']); ?>" />

      <label for="telefono">Teléfono</label>
      <input type="tel" id="telefono" name="telefono" required pattern="[0-9]{8}"
             value="<?php echo htmlspecialchars($row['telefono']); ?>" />

      <button type="submit">Actualizar Datos</button>
    </form>

    <div id="mensajeError" role="alert"></div>
  </main>

  <footer>… pie de página …</footer>
  <script src="../JS/ValidacionActualizar.js"></script>
</body>
</html>
