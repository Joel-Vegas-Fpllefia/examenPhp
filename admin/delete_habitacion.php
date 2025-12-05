<?php
session_start();
require_once('../db/config.php');

if(!isset($_SESSION['id_user'])){
    header('Location: ../index.php');
    exit();
}
if($_SESSION['rol'] != 'Administrador'){
    header('Location: ../no:permisos.php');
    exit();
}
$id = $_GET['id'];
$stmt = $mysqli -> prepare("DELETE FROM HABITACIONS WHERE id_habitacio  = ?");
if(!$stmt){
    die("ERROR AL PREPARAR LA QUERY"); 
}
$stmt -> bind_param("i",$id);
if($stmt -> execute()){
    header('Location: ../admin_panel.php');
    exit();
}
?>