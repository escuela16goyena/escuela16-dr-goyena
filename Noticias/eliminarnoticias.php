<?php
session_start();
include("../ConexionBD/conexion.php");

if(!isset($_SESSION["usuario"])){
    header("Location: ../Login/logeo.php");
    exit();
}

if(isset($_GET["id"])){

    $id = $_GET["id"];

    $sql = "DELETE FROM noticias WHERE id='$id'";

    if($conn->query($sql)){
        header("Location: panel.php");
        exit();
    }else{
        echo "Error al eliminar la noticia: " . $conn->error;
    }

}else{

    header("Location: panel.php");
    exit();

}
?>