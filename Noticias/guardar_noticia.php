<?php
session_start();
include("../ConexionBD/conexion.php");

if(!isset($_SESSION["usuario"])){
    header("Location: ../Login/logeo.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $titulo = $_POST["titulo"];
    $contenido = $_POST["contenido"];

    $sql = "INSERT INTO noticias (titulo, contenido)
            VALUES ('$titulo', '$contenido')";

    if($conn->query($sql)){
        header("Location: panel.php");
        exit();
    }else{
        echo "Error al guardar la noticia: " . $conn->error;
    }

}else{

    header("Location: panel.php");
    exit();

}
?>