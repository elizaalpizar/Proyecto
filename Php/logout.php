<?php
session_start();

$_SESSION = array();

session_destroy();

header("Location: ../Público/PaginaPrincipal.html");
exit();
?>
