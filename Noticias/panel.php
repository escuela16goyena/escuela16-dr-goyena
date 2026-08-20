<?php
session_start();
include("../ConexionBD/conexion.php");

if(!isset($_SESSION["usuario"])){
    header("Location: ../Login/logeo.php");
    exit();
}

$resultado = $conn->query("SELECT * FROM noticias ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Administración</title>
  <link rel="stylesheet" href="../CSS/panel.css">

</head>

<body>

<header>

<div class="contenedor">

        <h2>Panel de Administración</h2>

        <nav>
            <a href="../index.php">Inicio</a>
            <a href="noticias.php">Ver noticias</a>
            <a href="../Login/logout.php">Cerrar sesión</a>
        </nav>

    </div>


</header>

<div class="contenedor">

<h2>Publicar noticia</h2>

<form action="guardar_noticia.php" method="POST">

<input
type="text"
name="titulo"
placeholder="Título"
required>

<textarea
name="contenido"
rows="6"
placeholder="Contenido de la noticia"
required></textarea>

<button type="submit">
Publicar
</button>

</form>

<h2>Noticias publicadas</h2>

<table>

<tr>

<th>ID</th>
<th>Título</th>
<th>Fecha</th>
<th>Acciones</th>

</tr>

<?php

while($fila = $resultado->fetch_assoc()){

?>

<tr>

<td><?php echo $fila["id"]; ?></td>

<td><?php echo $fila["titulo"]; ?></td>

<td><?php echo $fila["fecha"]; ?></td>

<td>

<a href="editarnoticias.php?id=<?php echo $fila["id"]; ?>">
    Editar
</a>

<a href="eliminarnoticias.php?id=<?php echo $fila["id"]; ?>">
    Eliminar
</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>
</html>