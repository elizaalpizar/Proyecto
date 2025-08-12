<?php
require_once '../Php/Session.php';
verificarSesion();

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

  <footer class="footer">
    <div class="footer-content">
      <div class="footer-section">
        <h4>Energym</h4>
        <p>Transformando vidas a través del fitness</p>
      </div>
      <div class="footer-section">
        <h4>Contacto</h4>
        <p><i class="fas fa-phone"></i> +506 8957 7890</p>
        <p><i class="fas fa-envelope"></i> info@energym.com</p>
      </div>
      <div class="footer-section">
        <h4>Síguenos</h4>
        <div class="social-links">
          <a href="https://www.facebook.com/energymcr/?locale=es_LA"><i class="fab fa-facebook"></i></a>
          <a href="https://www.instagram.com/energym_cr/?hl=es"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2024 Energym. Todos los derechos reservados.</p>
    </div>
  </footer>
  <script src="../JS/ValidacionActualizar.js"></script>
</body>
</html>
