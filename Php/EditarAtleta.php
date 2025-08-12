<?php
session_start();

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Atleta</title>
  <link rel="stylesheet" href="../Css/Registro.css">
  <style>
    body { background: linear-gradient(180deg, #4f46e5 0%, #7c3aed 100%); min-height: 100vh; }
    .container { max-width: 900px; margin: 24px auto; background: #ffffff; padding: 24px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); color:#111827; }
    .container * { color:#111827; }
    h2 { margin-bottom: 16px; color:#111827; }
    form { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    form label { font-weight: 700; color: #374151; }
    form input { padding: 12px; border: 1px solid #e5e7eb; border-radius: 10px; background:#ffffff; color:#111827; }
    .full { grid-column: 1 / -1; }
    .actions { grid-column: 1 / -1; display: flex; gap: 12px; justify-content: flex-end; }
    .btn { padding: 10px 16px; border-radius: 10px; border: none; cursor: pointer; font-weight: 700; }
    .btn-primary { background:#4f46e5; color:#fff; }
    .btn-secondary { background:#e5e7eb; color:#111827; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Editar Atleta <?= htmlspecialchars($id) ?></h2>
    <form action="ActualizarAtleta.php" method="post" novalidate>
      <input type="hidden" name="identificacion_old" value="<?= htmlspecialchars($id) ?>">

      <div class="full">
        <label for="identificacion">Identificación</label>
        <input type="text" id="identificacion" name="identificacion_new" pattern="[0-9]{9,12}" required value="<?= htmlspecialchars($id) ?>">
      </div>

      <div>
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" minlength="4" required value="<?= htmlspecialchars($row['usuario']) ?>">
      </div>

      <div>
        <label for="password">Nueva Contraseña (opcional)</label>
        <input type="password" id="password" name="password" minlength="6" placeholder="Solo si deseas cambiarla">
      </div>

      <div>
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($row['nombre']) ?>">
      </div>

      <div>
        <label for="apellido1">Primer Apellido</label>
        <input type="text" id="apellido1" name="apellido1" required value="<?= htmlspecialchars($row['apellido1']) ?>">
      </div>

      <div>
        <label for="apellido2">Segundo Apellido</label>
        <input type="text" id="apellido2" name="apellido2" required value="<?= htmlspecialchars($row['apellido2']) ?>">
      </div>

      <div>
        <label for="correo">Correo Electrónico</label>
        <input type="email" id="correo" name="correo" required value="<?= htmlspecialchars($row['correo']) ?>">
      </div>

      <div>
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" pattern="[0-9]{8}" required value="<?= htmlspecialchars($row['telefono']) ?>">
      </div>

      <div class="actions">
        <a class="btn btn-secondary" href="../Admin/CatalogoAtleta.php">Cancelar</a>
        <button class="btn btn-primary" type="submit">Guardar Cambios</button>
      </div>
    </form>
  </div>
</body>
</html>
<?php odbc_close($conn); ?>
