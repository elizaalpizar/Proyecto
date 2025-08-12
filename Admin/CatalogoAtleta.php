<?php
require_once '../Php/Session.php';
require_once '../Php/conexion.php';
requireAdmin();

$sql = "SELECT identificacion, usuario, nombre, apellido1, apellido2, correo, telefono FROM atletas ORDER BY nombre, apellido1";
$stmt = odbc_exec($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Catálogo de Atletas – Energym</title>
  <link rel="stylesheet" href="../Css/Catalogo_Atleta.css">
  <script src="../JS/ConfirmarEliminar.js" defer></script>
  <script>
    function confirmarEliminar(id){
      return confirm('¿Eliminar atleta ' + id + '? Esta acción no se puede deshacer.');
    }
  </script>
</head>
<body>
  <header>
    <nav>
      <strong>Energym Admin</strong>
      <a href="../Admin/CatalogoAtleta.php">Atletas</a>
      <a href="../Admin/Catalogo_menu(CRUD).html">Servicios</a>
      <a href="../Admin/ReservacionesAdmin.php">Reservaciones</a>
      <span style="flex:1"></span>
      <a href="../Php/logoutAdmin.php">Cerrar sesión</a>
    </nav>
  </header>
  
  <div class="container">
    <h2>Catálogo de Atletas</h2>
    
    <div class="nuevo-atleta">
      <a href="../Público/RegistroAtleta.html">+ Nuevo Atleta</a>
    </div>
    
    <table>
      <thead>
        <tr>
          <th>Identificación</th>
          <th>Usuario</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Teléfono</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($stmt): while ($row = odbc_fetch_array($stmt)): ?>
          <tr>
            <td><?= htmlspecialchars($row['identificacion']) ?></td>
            <td><?= htmlspecialchars($row['usuario']) ?></td>
            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2']) ?></td>
            <td><?= htmlspecialchars($row['correo']) ?></td>
            <td><?= htmlspecialchars($row['telefono']) ?></td>
            <td class="actions">
              <a class="btn btn-edit" href="../Php/EditarAtleta.php?identificacion=<?= urlencode($row['identificacion']) ?>">Editar</a>
              <form action="../Php/EliminarAtleta.php" method="post" onsubmit="return confirmarEliminar('<?= htmlspecialchars($row['identificacion']) ?>')" style="display: inline;">
                <input type="hidden" name="identificacion" value="<?= htmlspecialchars($row['identificacion']) ?>">
                <button class="btn btn-del" type="submit">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="6">Error al cargar atletas.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  <?php cerrarConexion($conn); ?>
</body>
</html>
