<?php
require_once '../Php/Session.php';
requireAdmin();

$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$passwordDB = "19861997.Sr";
$cs = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$passwordDB;CharacterSet=UTF8;";

$conn = odbc_connect($cs, '', '') or die('Error de conexión');

$sql = "SELECT identificacion, usuario, nombre, apellido1, correo, telefono FROM atletas ORDER BY apellido1";
$rs  = odbc_exec($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Catálogo de Atletas – Energym</title>
  <link rel="stylesheet" href="../Css/Registro.css">
</head>
<body>
  <header>… nav admin …</header>

  <main>
    <h2>Catálogo de Atletas</h2>
    <p><a href="../Público/RegistroAtleta.html">+ Nuevo Atleta</a></p>
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Usuario</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = odbc_fetch_array($rs)): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['identificacion']); ?></td>
          <td><?php echo htmlspecialchars($row['usuario']); ?></td>
          <td><?php echo htmlspecialchars($row['nombre'].' '.$row['apellido1']); ?></td>
          <td><?php echo htmlspecialchars($row['correo']); ?></td>
          <td><?php echo htmlspecialchars($row['telefono']); ?></td>
          <td>
            <a href="InfoAtleta.php?id=<?php echo urlencode($row['identificacion']); ?>">Editar</a>
            |
            <form action="../Php/EliminarAtleta.php" method="POST" style="display:inline;" class="formEliminar">
              <input type="hidden" name="identificacion" value="<?php echo htmlspecialchars($row['identificacion']); ?>">
              <button type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </main>

  <footer>… pie de página …</footer>
  <script src="../JS/ConfirmarEliminar.js"></script>
</body>
</html>
<?php odbc_close($conn); ?>
