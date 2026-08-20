<?php
session_start();
include("../ConexionBD/conexion.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: logeo.php");
    exit();
}

$usuario = $_POST["usuario"];
$contrasena = $_POST["password"];

$sql = "SELECT * FROM usuarios
        WHERE usuario='$usuario'
        AND contrasena='$contrasena'";

$resultado = $conn->query($sql);

if($resultado->num_rows > 0){

    $_SESSION["usuario"] = $usuario;

    // Redirige al inicio después de iniciar sesión
    header("Location: ../index.php");
    exit();

}else{

    echo "Usuario o contraseña incorrectos.";

}
?>