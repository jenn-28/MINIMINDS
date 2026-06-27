<?php
$conexion = mysqli_connect("localhost", "root", "")
//$conexion = mysqli_connect("127.0.0.1", "u335636780_Mini", "M1niminds")
or die("Conexion Fallida: " . mysqli_connect_error());

$conexion->set_charset("utf8");
mysqli_select_db($conexion, "u335636780_miniminds")

//mysqli_select_db($conexion, "u335636780_minimininds")
or die("Error en la selección de base de datos");
?>

