<?php
session_start();
include("../ConexionBD/conexion.php");

if(!isset($_SESSION["usuario"])){
    header("Location: ../Login/logeo.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = $_POST["id"];
    $titulo = $_POST["titulo"];
    $contenido = $_POST["contenido"];

    $sql = "UPDATE noticias
            SET titulo='$titulo',
                contenido='$contenido'
            WHERE id=$id";

    if($conn->query($sql)){
        header("Location: panel.php");
        exit();
    }else{
        echo "Error al actualizar la noticia: " . $conn->error;
    }

}


if(!isset($_GET["id"])){
    header("Location: panel.php");
    exit();
}

$id = $_GET["id"];

$sql = "SELECT * FROM noticias WHERE id=$id";
$resultado = $conn->query($sql);

if($resultado->num_rows == 0){
    echo "La noticia no existe.";
    exit();
}

$noticia = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Editar Noticia</title>
<link rel="stylesheet" href="../CSS/editarnoticias.css">
</head>

<body>

<div class="contenedor">

<h2>Editar Noticia</h2>

<form method="POST">

<input
type="hidden"
name="id"
value="<?php echo $noticia["id"]; ?>">

<label>Título</label>

<input
type="text"
name="titulo"
value="<?php echo htmlspecialchars($noticia["titulo"]); ?>"
required>

<label>Contenido</label>

<textarea
name="contenido"
rows="8"
required><?php echo htmlspecialchars($noticia["contenido"]); ?></textarea>

<button type="submit">
Guardar Cambios
</button>

</form>

<br>

<a href="panel.php">
Volver al Panel
</a>

</div>

</body>
</html>