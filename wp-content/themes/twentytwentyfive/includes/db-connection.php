<?php
function conexionBD() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bd_proyecto";

    $conexion = new mysqli($servername, $username, $password, $dbname);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    return $conexion;
}
?>