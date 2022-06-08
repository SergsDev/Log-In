<?php
// inicializa la sesión
session_start();
 
/* Compruebe si el usuario ha iniciado sesión; 
	de lo contrario, redirija a la página de inicio de sesión (login.php)*/
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// incluir el archivo de configuración
require_once "configuracion.php";
 
// Definir variables e inicializar con valores vacíos
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Procesamiento de datos del formulario cuando se envía el formulario
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar la nueva contraseña
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Por favor, introduzca la nueva contraseña.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "La contraseña debe tener al menos 6 caracteres.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validar la confirmación de contraseña
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor confirme la contraseña.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Las contraseñas no coinciden.";
        }
    }
        
    // Verifique los errores de entrada antes de actualizar la base de datos
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare la declaración de actualización
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular variables a la declaración preparada como parámetros
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Asignar parámetros
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Intente ejecutar la declaración preparada
            if(mysqli_stmt_execute($stmt)){
                /* Contraseña actualizada exitosamente. 
				Destruye la sesión y redirige a la página de inicio de sesión (login.php)*/
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Algo salió mal, por favor vuelva a intentarlo.";
            }
        }
        
        // Declaración de cierre
        mysqli_stmt_close($stmt);
    }
    
    // Cerrar conexión
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
	<script src="https://cdn.tailwindcss.com"></script>
    <title>Cambio de contraseña</title>
</head>
<body>

        
        <section class="w-full max-w-xs text-center mx-auto">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"> 
        
        <h2 class="text-gray-700 text-xl font-bold mb-2 pb-4">Cambio contraseña</h2>
        <p class="block text-gray-700 text-sm font-bold mb-2">Complete este formulario para restablecer su contraseña.</p>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Nueva contraseña</label>
                <input type="password" name="new_password" value="<?php echo $new_password; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                <span class="block text-gray-700 text-sm font-bold mb-2"><?php echo $new_password_err; ?></span><br>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Confirmar contraseña</label>
                <input type="password" name="confirm_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                <span class="block text-gray-700 text-sm font-bold mb-2"><?php echo $confirm_password_err; ?></span><br>
            </div>

            <div>
                <button type="submit" value="Enviar" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-green-500 border-green-800 hover:bg-green-600 active:bg-green-700 focus:ring-green-300">Enviar</button>
                <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-red-500 border-red-800 hover:bg-red-600 active:bg-red-700 focus:ring-red-300"><a class="btn btn-link" href="index.php">Cancelar</a></button>
            </div>

                <!-- <input type="submit" value="Enviar"><br>
                <a class="btn btn-link" href="index.php">Cancelar</a>
            -->
        </form>
        </section>   
</body>
</html>