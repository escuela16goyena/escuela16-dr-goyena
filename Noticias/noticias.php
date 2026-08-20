<?php
include("../ConexionBD/conexion.php");
?>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias - Escuela Primaria N°16</title>
    <link rel="stylesheet" href="../CSS/vernoticias.css">
</head>
<body>

<header>

    <h1>Noticias de la Escuela</h1>

</header>

<<div class="contenedor">

<div class="lista-noticias">

<?php

$sql = "SELECT * FROM noticias ORDER BY fecha DESC";

$resultado = $conn->query($sql);

if($resultado->num_rows > 0){

    while($fila = $resultado->fetch_assoc()){

        echo "<div class='noticia'>";

        echo "<h2>".$fila["titulo"]."</h2>";

        echo "<p class='fecha'>".$fila["fecha"]."</p>";

        echo "<p>".$fila["contenido"]."</p>";

        echo "</div>";

    }

}else{

    echo "<h2>No hay noticias publicadas.</h2>";

}

?>

</div>

<a href="../index.php" class="boton">Volver al Inicio</a>

<?php if(isset($_SESSION["usuario"])): ?>

<a href="panel.php" class="boton">Publicar noticia</a>

<?php endif; ?>

</div>

</body>
</html>