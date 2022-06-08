<?php
// Inicializa la sesión
session_start();
 
/* Verifique si el usuario ya ha iniciado sesión, si es así, 
rediríjalo a la página de bienvenida (index.php)*/
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.php");
  exit;
}

// Incluir el archivo de configuración
require_once "configuracion.php";
 
// Definir variables e inicializar con valores vacíos
$username = $password = $username_err = $password_err = "";
 
// Procesamiento de datos del formulario cuando se envía el formulario
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Comprobar si el nombre de usuario está vacío
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor ingrese su usuario.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Comprobar si la contraseña está vacía
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingrese su contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validar información del usuario
    if(empty($username_err) && empty($password_err)){
        // Preparar la consulta select
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            /* Vincular variables a la declaración preparada como parámetros, s es por la
			variable de tipo string*/
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Asignar parámetros
            $param_username = $username;
            
            // Intentar ejecutar la declaración preparada
            if(mysqli_stmt_execute($stmt)){
                // almacenar el resultado de la consulta
                mysqli_stmt_store_result($stmt);
                
                /*Verificar si existe el nombre de usuario, si es así,
				verificar la contraseña*/
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Vincular las variables del resultado
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
					//obtener los valores de la consulta
                    if(mysqli_stmt_fetch($stmt)){
						/*comprueba que la contraseña ingresada sea igual a la 
						almacenada con hash*/
                        if(password_verify($password, $hashed_password)){
                            // La contraseña es correcta, así que se inicia una nueva sesión
                            session_start();
                            
                            // se almacenan los datos en las variables de la sesión
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirigir al usuario a la página de inicio
                            header("location: index.php");
                        } else{
                            // Mostrar un mensaje de error si la contraseña no es válida
                            $password_err = "La contraseña que ha ingresado no es válida.";
                        }
                    }
                } else{
                    // Mostrar un mensaje de error si el nombre de usuario no existe
                    $username_err = "No existe cuenta registrada con ese nombre de usuario.";
                }
            } else{
                echo "Algo salió mal, por favor vuelve a intentarlo.";
            }
        }
        
        // Cerrar la sentencia de consulta
        mysqli_stmt_close($stmt);
    }
    
    // Cerrar laconexión
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<script src="https://cdn.tailwindcss.com"></script>
		<title>Inicio de sesión</title>
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

            <h2 class="text-gray-700 text-xl font-bold mb-2 pb-4">Inicio de sesión</h2>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Usuario</label>
                <input type="text" name="username"  value="<?php echo $username; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight " >
                <span class="block text-gray-700 text-sm font-bold mb-2"><?php echo $username_err; ?></span><br>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Contraseña</label>
                <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight " >
                <span class="block text-gray-700 text-sm font-bold mb-2"><?php echo $password_err; ?></span><br>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" value="Ingresar" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-green-500 border-green-800 hover:bg-green-600 active:bg-green-700 focus:ring-green-300">Iniciar Sesion</button><br>
                <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-b-2 focus:outline-none focus:ring transition text-white bg-blue-500 border-blue-800 hover:bg-blue-600 active:bg-blue-700 focus:ring-blue-300"><a href="registrar.php">Regístrarse</a></button>
            </div>
        </form>
    </section>

</body>
</html>