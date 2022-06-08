<?php
// Se inicia la sesión
session_start();
 
// se desasignan todas las variables de sesión
$_SESSION = array();
 
// se destruye la sesión
session_destroy();
 
// se redirecciona a la página de inicio de sesión (login.php)
header("location: login.php");
//se sale del archivo
exit;
?>