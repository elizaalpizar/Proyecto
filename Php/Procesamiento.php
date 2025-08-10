<?php
session_start();
require __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['error'] = 'Completa todos los campos.';
    header('Location: login.php');
    exit;
}

// Verificar usuario en la base de datos
$stmt = $pdo->prepare('
  SELECT id, username, password 
  FROM atletas 
  WHERE username = :username 
  LIMIT 1
');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Login exitoso
    session_regenerate_id(true);
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    header('Location: ../dashboard.php');
    exit;
}

// Credenciales inválidas
$_SESSION['error'] = 'Usuario o contraseña incorrectos.';
header('Location: login.php');
exit;
