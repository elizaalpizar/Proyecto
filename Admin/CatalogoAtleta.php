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
  <link rel="stylesheet" href="../Css/Registro.css">
  <style>
    .container { max-width: 1100px; margin: 24px auto; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { text-align: left; padding: 10px; border-bottom: 1px solid #eee; }
    .btn { padding: 6px 10px; border: none; border-radius: 8px; cursor: pointer; color:#fff; }
    .btn-edit { background:#0ea5e9; }
    .btn-del { background:#ef4444; }
    .actions { display: flex; gap: 8px; }
  </style>
  <script src="../JS/ConfirmarEliminar.js" defer></script>
  <script>
    function confirmarEliminar(id){
      return confirm('¿Eliminar atleta ' + id + '? Esta acción no se puede deshacer.');
    }
  </script>
  </head>
<body>
  <header style="background:#111827; color:#fff; padding:12px 16px;">
    <nav style="display:flex; gap:16px; align-items:center;">
      <strong>Energym Admin</strong>
      <a href="../Admin/CatalogoAtleta.php" style="color:#fff; text-decoration:none;">Atletas</a>
      <a href="../Admin/Catalogo_menu(CRUD).html" style="color:#fff; text-decoration:none;">Servicios</a>
      <a href="../Admin/ReservacionesAdmin.php" style="color:#fff; text-decoration:none;">Reservaciones</a>
      <span style="flex:1"></span>
      <a href="../Php/logoutAdmin.php" style="color:#fca5a5; text-decoration:none;">Cerrar sesión</a>
    </nav>
  </header>
  <div class="container">
    <h2>Catálogo de Atletas</h2>
    <p><a href="../Público/RegistroAtleta.html">+ Nuevo Atleta</a></p>
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
              <form action="../Php/EliminarAtleta.php" method="post" onsubmit="return confirmarEliminar('<?= htmlspecialchars($row['identificacion']) ?>')">
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
