<?php
// Se inicializa la sesión
session_start();
 
/* Se comprueba si el usuario ha iniciado sesión, si no, se redirecciona
 a la página de inicio de sesión (login.php)*/
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<script src="https://cdn.tailwindcss.com"></script>
		<title>Bienvenido</title>  
	</head>
	<body>
		<div class="container-lg m-0 text-center bg-slate-800 text-white p-2 font-semibold">

		<h1>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Has iniciado sesion correctamente</h1>
		</div>
 
		<div>
			<button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-red-500 border-red-800 hover:bg-red-600 active:bg-red-700 focus:ring-red-300"><a href="logout.php">Cerrar sesión</a><br></button>
			<button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-yellow-500 border-yellow-800 hover:bg-yellow-600 active:bg-yellow-700 focus:ring-yellow-300"><a href="reset-password.php" >Cambiar contraseña</a></button>
		</div>
	</body>
</html>