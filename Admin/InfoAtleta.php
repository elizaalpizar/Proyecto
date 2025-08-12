<?php
require_once '../Php/Session.php';
requireAdmin();

$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$passwordDB = "19861997.Sr";
$cs = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$passwordDB;CharacterSet=UTF8;";

$conn = odbc_connect($cs, '', '') or die('Error de conexión');

if (empty($_GET['id'])) {
    die("ID de atleta requerido.");
}

$id = $_GET['id'];
$sql = "SELECT identificacion, usuario, nombre, apellido1, apellido2, correo, telefono 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Atleta - Energym</title>
    <link rel="stylesheet" href="../Css/Registro.css">
</head>
<body>
    <header>
        <nav>
            <a href="CatalogoAtleta.php">← Volver al Catálogo</a>
            <a href="../Php/logoutAdmin.php">Cerrar Sesión</a>
        </nav>
    </header>

    <main>
        <h2>Información del Atleta</h2>
        
        <div class="info-card">
            <h3><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2']) ?></h3>
            
            <div class="info-grid">
                <div class="info-item">
                    <strong>Identificación:</strong>
                    <span><?= htmlspecialchars($row['identificacion']) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Usuario:</strong>
                    <span><?= htmlspecialchars($row['usuario']) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Nombre:</strong>
                    <span><?= htmlspecialchars($row['nombre']) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Primer Apellido:</strong>
                    <span><?= htmlspecialchars($row['apellido1']) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Segundo Apellido:</strong>
                    <span><?= htmlspecialchars($row['apellido2']) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Correo:</strong>
                    <span><?= htmlspecialchars($row['correo']) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Teléfono:</strong>
                    <span><?= htmlspecialchars($row['telefono']) ?></span>
                </div>
            </div>
            
            <div class="actions">
                <a href="../Php/EditarAtleta.php?identificacion=<?= urlencode($row['identificacion']) ?>" class="btn-edit">
                    Editar Atleta
                </a>
                
                <form action="../Php/EliminarAtleta.php" method="POST" style="display:inline;" class="formEliminar">
                    <input type="hidden" name="identificacion" value="<?= htmlspecialchars($row['identificacion']) ?>">
                    <button type="submit" class="btn-delete">Eliminar Atleta</button>
                </form>
            </div>
        </div>
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

    <script src="../JS/ConfirmarEliminar.js"></script>
</body>
</html>
<?php odbc_close($conn); ?>
