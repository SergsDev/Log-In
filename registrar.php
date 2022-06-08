<?php
// se incluye el archivo de configuración
require_once "configuracion.php";
// Definir variables e inicializar con valores vacíos
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Procesamiento de datos del formulario cuando se envía el formulario
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validar el nombre de usuario
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor ingrese un usuario.";
    } else{
        // Preparar la consulta
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular variables a la declaración preparada como parámetros
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // asignar parámetros
            $param_username = trim($_POST["username"]);
            
            // Intentar ejecutar la declaración preparada
            if(mysqli_stmt_execute($stmt)){
                /* almacenar resultado*/
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Este usuario ya fue tomado.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Al parecer algo salió mal.";
            }
        }
         
        // Declaración de cierre
        mysqli_stmt_close($stmt);
    }
    
    // Validar contraseña
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingresa una contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña al menos debe tener 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validar que se confirma la contraseña
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirma tu contraseña.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "No coincide la contraseña.";
        }
    }
    
    // Verifique los errores de entrada antes de insertar en la base de datos
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare una declaración de inserción
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular variables a la declaración preparada como parámetros
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Establecer parámetros
            $param_username = $username;
			$param_password = password_hash($password, PASSWORD_DEFAULT); // Crear una contraseña hash
            
            // Intentar ejecutar la declaración preparada
            if(mysqli_stmt_execute($stmt)){
                // Redirigir a la página de inicio de sesión (login.php)
                header("location: login.php");
            } else{
                echo "Algo salió mal, por favor inténtalo de nuevo.";
            }
        }
         
        // claración de cierre
        mysqli_stmt_close($stmt);
    }
    
    // Cerrar la conexión
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
	<script src="https://cdn.tailwindcss.com"></script>
    <title>Registro</title>
</head>
<body>

    <div class="container-lg m-0 text-center bg-slate-800 text-white p-2 font-semibold">
        <h1>Sistema de Log in Con conexion a base de datos</h1>
        <p>Sergio Andres Hernandez Rico</p>
        <p>Oscar Javier Barragan</p>
        <p>Programacion Web, 2022</p>
        <p>Unidades Tecnologicas de santander</p>
    </div>


        <section class="w-full max-w-xs text-center mx-auto ">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

        <h2 class="text-gray-700 text-xl font-bold mb-2 pb-4" >Registro</h2>
        <p class="block text-gray-700 text-sm font-bold mb-2">Por favor complete este formulario para crear una cuenta.</p>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Usuario</label>
            <input type="text" name="username"  value="<?php echo $username; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
            <span class="block text-gray-700 text-sm font-bold mb-2"><?php echo $username_err; ?></span><br>
        </div>    

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Contraseña</label>
            <input type="password" name="password"  value="<?php echo $password; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
            <span class="block text-gray-700 text-sm font-bold mb-2"><?php echo $password_err; ?></span><br>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Confirmar contraseña</label>
            <input type="password" name="confirm_password"  value="<?php echo $confirm_password; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
            <span class="block text-gray-700 text-sm font-bold mb-2"><?php echo $confirm_password_err; ?></span><br>
        </div>

        <button type="submit" value="Ingresar" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-green-500 border-green-800 hover:bg-green-600 active:bg-green-700 focus:ring-green-300">Ingresar</button>
        <button type="reset" value="Borrar" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-red-500 border-red-800 hover:bg-red-600 active:bg-red-700 focus:ring-red-300">Borrar</button>
            <p class="block text-gray-700 text-sm font-bold mb-2">¿Ya tienes una cuenta?</p>

        <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-blue-500 border-blue-800 hover:bg-blue-600 active:bg-blue-700 focus:ring-blue-300"><a href="login.php">Ingresa aquí</a>.</button>
        </form>
        </section>
</body>
</html>